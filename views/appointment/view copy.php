<?php
use app\components\MyUrl;

/* @var $this yii\web\View */

$timezone = new DateTimeZone('Europe/Istanbul');
$now = new DateTime('now', $timezone);
$currentHour = $now->format('H');
$dayWidth = 100 / count($days);

function eventPrint($event, $tabIndex, $pixPerHour, $days)
{
    $top = ($event['start']->format('H') * 60 + $event['start']->format('i')) * $pixPerHour / 60;    
    $height = ($event['duration']) * $pixPerHour / 60;
    $dayWidth = 100 / count($days);
    $left = $dayWidth * $event['day'];

    return <<<HTML
        <div
            class="event-box draggable"
            draggable="true"
            id="event-{$event['title']}"
            style="top: {$top}px; height: {$height}px; width: calc({$dayWidth}% - 40px); left: {$left}%;"
            role="button"
            data-day="{$event['day']}"
            data-bs-toggle="popover"
            data-bs-placement="top"
            data-bs-trigger="focus"
            data-bs-title="Dismissible popover"
            data-bs-content="And here's some amazing content. It's very engaging. Right?"
            tabindex="{$tabIndex}"
        ><span class="event-title">{$event['title']}</span>
        </div>
        HTML;
}

?>

<div id="info-box" style="z-index: 10000; display: none; position: fixed; top: 0px; left: 200px; height: 40px; background: red; color: white; padding: 3px;">
    Drag info will appear here.
</div>
<div id="info-box" style="z-index: 10000; display: block; position: fixed; top: 0px; left: 600px; height: 40px; background: red; color: white; padding: 3px;">
    <button onclick="fetchEvents();">Fetch All Events</button>
</div>

<div class="calendar-container">
    <div class="row p-0 m-0">
        <?php foreach ($days as $key=>$day) : ?>
            <div class="col row px-2" style="width: calc(100% / <?= count($days) ?>); padding: 0; margin: 0;">
                <div class="col text-center text-muted" id="day<?= $key ?>"><?= $day ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="scroll-container" style="height: <?= $pixPerHour*24 ?>px;">
        <div id="timeslot-container">
            <?php foreach ($days as $key=>$day) : ?>
                <div class="day-box" style="left: <?= $key * $dayWidth ?>%; width: <?= $dayWidth ?>%;"></div>
            <?php endforeach; ?>
            <div class="row p-0 m-0">
                <?php for ($t = 0; $t < count($days); $t++) : ?>
                    <div class="col" style="width: calc(100% / <?= count($days) ?>); padding: 0; margin: 0;">
                        <?php for ($hour = 0; $hour < 24; $hour++) : ?>
                            <div class="timeslot" style="height: <?= $pixPerHour ?>px;">
                                <small><?= sprintf("%02d:00", $hour) ?></small>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>        
        </div>
        <div id="events-container">
            <?php /*foreach ($events as $key=>$event) {
                echo eventPrint($event, $key+1, $pixPerHour+5, $days); // switched to dynamically json loaded version
            } */?>
        </div>
    </div>
</div>

<?php

$this->registerCss(<<<CSS
        .scroll-container {
            overflow-y: auto;
            display: flex;
            position: relative;

        }
        .day-box {
            position: absolute;
            top: 0;
            bottom: 0;
            background-color: transparent;
            border-right: 1px solid #dee2e6;
            z-index: 3;
            pointer-events: none;
            text-align: center;
            color: #aaaaaa;
        }
        #timeslot-container {
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f9fa;
            position: absolute;
            z-index: 1;
            font-size: 0.875rem;
        }
        #events-container {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 4;
            /*margin-left: 40px;*/
        }
        .timeslot {
            padding: 0;
            border-bottom: 1px solid #dee2e6;
            color: #aaaaaa;
        }
        .event-box {
            z-index: 5;
            display: block;
            position: absolute;
            left: 0;
            right: 0;
            color: #333;
            background-color: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-decoration: none;
            font-size: 0.875rem;
            margin: 0 5px 0 35px;
            box-sizing: border-box;
        }
        .event-box:hover {
            background-color: #0056b3;
            color: #ffffff;
        }
        .popover {
            background-color: #ffffff;
            color: #212529;
            border-color: #ced4da;
        }
        .popover .popover-body {
            color: #212529;
        }
        .popover .popover-header {
            background-color: #e91010;
            color: #f9f0f7;
            border-bottom-color: #dee2e6;
        }
        .popover .arrow::before {
            border-bottom-color: #ced4da;
        }
        .popover .arrow::after {
            border-bottom-color: #ffffff;
        }
        .draggable {
            cursor: pointer;
        }
CSS);
?>
<script>

const MyApp = {

    dayCount : <?= count($days) ?>,
    eventsContainer : document.getElementById('events-container'),
    collisionMap : [],
    collisionCounts : [],
    maxCollision: 1,
    eventsList : [],
    pixPerHour : <?= $pixPerHour ?>,
    dayWidth : <?= $dayWidth ?>,


        /*****************************/
       /*                           */
      /*   function declarations   */
     /*                           */
    /*****************************/

    fetchAllEvents: async function () {
        const response = await fetch('<?= MyUrl::to(['appointment/events/demo']) ?>');
        this.eventsList = await response.json();
        this.initializeEvents();
    },

    initializeEvents: function (events) {
        this.deleteEvents();
        this.createCollisionMap();
        this.solveEventCollisions();
        this.setEventPositions();
        this.createUpdateEventObjects();
    },

    deleteEvents: function () {
        this.eventsContainer.innerHTML = '';
    },

    maxCollidedEvents: function () {
        const starts = [];
        const ends = [];
        for (const event of this.eventList) {
            starts.push(event.startMinute);
            ends.push(event.endMinute);
        }
        starts.sort((a, b) => a - b);  
        ends.sort((a, b) => a - b);  
        let activeEvents = 0, maxCount = 0;
        let i = 0, j = 0;
        while (i < starts.length) {
            if (starts[i] < ends[j]) {
                activeEvents++;
                maxCount = Math.max(maxCount, activeEvents); 
                i++;
            } else { 
                activeEvents--;
                j++;
            }
        }
        return maxCount;
    },

    assignEventsToColumns: function() {
        this.eventList.sort((a, b) => a.startMinute - b.startMinute);
        this.eventColumns = [];

        for (const event of this.eventList) {
            let columnFound = false;
            for (let i = 0; i < this.eventColumns.length; i++) {
                const lastEventInColumn = this.eventColumns[i][this.eventColumns[i].length - 1];
                if ((!lastEventInColumn || lastEventInColumn.endMinute <= event.startMinute)) {
                    this.eventColumns[i].push(event);
                    columnFound = true;
                    break; 
                }
            }
            if (!columnFound) {
                const newColumn = [];
                const numEmptySlots = Math.max(0, Math.ceil((event.startMinute - 0) / this.minuteInterval) - 1); // Adapt 'minuteInterval' accordingly

                for (let i = 0; i < numEmptySlots; i++) { 
                    newColumn.push(null);
                }

                newColumn.push(event); // Add the event
                this.eventColumns.push(newColumn); 
            }
        }
        },


    createCollisionMap: function () {
        // TODO: days will be integrated later. For now, run for one day only
        this.collisionMap = [];
        this.collisionCounts = Array(1440).fill(0);

        this.eventsList.forEach((event, index) => {
            const startTime = new Date(event.start.date);
            event.startMinute = startTime.getHours() * 60 + startTime.getMinutes();
            event.endMinute = event.startMinute + event.duration;
            event.column = 0;
            event.columnCount = 1;
            event.columnSet = false;
            event.collisions = [];
            for (let t = event.startMinute; t < event.endMinute; t++) {
                if (!this.collisionMap[t]) {
                    this.collisionMap[t] = [];
                }
                this.collisionMap[t].push(index);
                this.collisionCounts[t]++;
            }
        });

        maxCollision = 1;
        this.collisionMap.forEach((slot, index) => {
            maxCollision = Math.max(maxCollision, this.collisionCounts[index]);
            if (slot && slot.length > 1) {
                slot.forEach(index => {
                    this.eventsList[index].collisions =  [...new Set([...this.eventsList[index].collisions, ...slot])];
                });
            }
        });

        console.log(maxCollision);

        this.eventsList.forEach(event => {
            event.collisions.forEach(collided => {
                event.columnCount = Math.max(event.columnCount, this.eventsList[collided].collisions.length);
                this.eventsList[collided].columnCount = event.columnCount;
            });
            console.log(event);
        });
    },

    solveEventCollisions: function () {
        maxCollision = 1;
        this.collisionMap.forEach((slot, index) => {
            slotCollision = this.collisionCounts[index];
            if (slot && slotCollision > 1) {
                maxCollision = Math.max(maxCollision, slotCollision);
                let occupiedColumns = new Array(maxCollision).fill(false);
                slot.forEach(index => {
                    if (this.eventsList[index].columnSet) {
                        occupiedColumns[this.eventsList[index].column] = true;
                    }
                });
                slot.forEach(index => {
                    if (!this.eventsList[index].columnSet) {
                        let assignedColumn = 0;
                        while (occupiedColumns[assignedColumn]) {
                            assignedColumn++;
                        }
                        occupiedColumns[assignedColumn] = true;
                        this.eventsList[index].column = assignedColumn;
                        this.eventsList[index].columnSet = true;
                    }
                });
            }
        });
    },

    setEventPositions: function () {
        let tabIndex = 1;
        this.eventsList.forEach((event, index) => {
            event.id = index;
            event.startTime = new Date(event.start.date);
            event.top = (event.startTime.getHours() * 60 + event.startTime.getMinutes()) * this.pixPerHour / 60;
            event.height = event.duration * this.pixPerHour / 60;
            event.left = this.dayWidth * event.day + event.column * this.dayWidth / event.columnCount;
            event.width = this.dayWidth / event.columnCount;
            event.widthMargin = Math.floor(40/event.columnCount)+(event.columnCount ? 2 : 0);
            event.leftMargin = event.column * Math.floor(40/event.columnCount);
            event.tabIndex = tabIndex++;
        });
    },

    createUpdateEventObjects: function () {
        this.eventsList.forEach(event => {
            this.eventToDOM(event);
        });
        this.initializePopovers()
    },

    eventToDOM: function (event) {
        const eventDiv = document.createElement('div');
        eventDiv.className = 'event-box draggable';
        eventDiv.id = 'event'+event.id;
        eventDiv.style.cssText = `top: ${event.top}px; height: ${event.height}px; width: calc(${event.width}% - ${event.widthMargin}px); left: calc(${event.left}% - ${event.leftMargin}px); padding: 0px;`;
        eventDiv.setAttribute('role', 'button');
        eventDiv.setAttribute('draggable', true);
        eventDiv.setAttribute('data-day', event.day);
        eventDiv.setAttribute('data-slotorder', 0);
        eventDiv.setAttribute('data-bs-toggle', 'popover');
        eventDiv.setAttribute('data-bs-placement', 'top');
        eventDiv.setAttribute('data-bs-trigger', 'focus');
        eventDiv.setAttribute('data-bs-title', event.title);
        eventDiv.setAttribute('data-bs-content', event.startTime.toTimeString()+` columnCount: ${event.columnCount} column: ${event.column}`);
        eventDiv.setAttribute('tabIndex', event.tabIndex);

        const spanTitle = document.createElement('span');
        spanTitle.className = 'event-title';
        spanTitle.textContent = event.title;
        eventDiv.appendChild(spanTitle);

        this.eventsContainer.appendChild(eventDiv);
    },

    initializePopovers: function () {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        popoverTriggerList.forEach(popoverTriggerEl => {
            const existingPopover = bootstrap.Popover.getInstance(popoverTriggerEl);
            if (existingPopover) {
                existingPopover.dispose();
            }
            new bootstrap.Popover(popoverTriggerEl);
        });
    },
}

function fetchEvents() { MyApp.fetchAllEvents();}

document.addEventListener('DOMContentLoaded', function () {

    MyApp.fetchAllEvents();
    
    const eventsContainer = document.getElementById('events-container');
    const pixPerHour = <?= $pixPerHour ?>;

    let offsetX, offsetY;

    function setEventPosition(e, element) {
        const dayWidth = eventsContainer.offsetWidth / MyApp.dayCount;
        const rect = eventsContainer.getBoundingClientRect();
        let top = e.clientY - rect.top - offsetY;
        let left = e.clientX - rect.left - offsetX;

        if (top<0) top = 0;
        if (left<0) left = 0;

        const day = Math.floor(left / dayWidth);
        const startMinute = Math.floor((top / pixPerHour) * 60 /5)*5;
        top = startMinute * pixPerHour / 60;
        left = day * dayWidth;

        const dayDivId = `day${day}`;
        const eventHours = Math.floor(startMinute / 60).toString().padStart(2, '0');
        const eventMinutes = (startMinute % 60).toString().padStart(2, '0');
        const eventDay = document.getElementById(dayDivId).innerHTML;

        element.dataset.day = day;
        element.style.top = `${top}px`;
        element.style.left = `${left}px`;

        const titleSpan = element.querySelector('.event-title');
        const popoverInstance = bootstrap.Popover.getInstance(element);
        if (popoverInstance) {
            popoverInstance.setContent({
                '.popover-body': `${eventDay} ${eventHours}:${eventMinutes}`
            });
            popoverInstance.update();
        }
    }

    /* recalculate event positions when called (triggered below by debounce) */
    function updateEventPositions() {
        const dayWidth = eventsContainer.offsetWidth / MyApp.dayCount;
        document.querySelectorAll('.event-box').forEach(function(item) {
            const day = item.dataset.day;
            item.style.left = `${day * dayWidth}px`;
        });
    }
    
    function handleDragStart(e) {
        draggedElement = e.target; 
        e.dataTransfer.effectAllowed = 'move';
        var popoverInstance = bootstrap.Popover.getInstance(draggedElement);
        if (popoverInstance) {
            popoverInstance.hide(); 
        }
        const infoBox = document.getElementById('info-box');
        infoBox.style.display = 'block';
        infoBox.textContent = draggedElement.querySelector('.event-title').innerHTML;
        offsetX = e.clientX - e.target.getBoundingClientRect().left;
        offsetY = e.clientY - e.target.getBoundingClientRect().top;
    }

    function handleDrag(e) {
        if (e.clientX > 0 && e.clientY > 0) {
            const dayWidth = eventsContainer.offsetWidth / MyApp.dayCount;
            const rect = eventsContainer.getBoundingClientRect();
            let top = e.clientY - rect.top - offsetY; - (draggedElement.offsetHeight / 2);
            let left = e.clientX - rect.left - offsetX;
            if (left<0) left=0;
            if (top<0) top=0;
            if (top > pixPerHour*24) top = pixPerHour*24;
            let day = Math.floor(left / dayWidth);
            if (day>MyApp.dayCount-1) day = MyApp.dayCount-1;
            const dayDivId = `day${day}`;
            const eventDay = document.getElementById(dayDivId).innerHTML;
            const startMinute = Math.floor((top / pixPerHour) * 60 /5)*5;
            const eventHours = Math.floor(startMinute / 60).toString().padStart(2, '0');
            const eventMinutes = (startMinute % 60).toString().padStart(2, '0');
            const infoBox = document.getElementById('info-box');
            infoBox.textContent = draggedElement.querySelector('.event-title').innerHTML+` is being moved to ${eventDay} ${eventHours}:${eventMinutes}`;
        }
    }

    function handleDragEnd(e) {
        e.preventDefault();
        setEventPosition(e, draggedElement);
        const infoBox = document.getElementById('info-box');
        infoBox.style.display = 'none';
        const popoverInstance = bootstrap.Popover.getOrCreateInstance(draggedElement);
        popoverInstance.show();
    }

        /***********************/
       /*                     */
      /*   event listeners   */
     /*                     */
    /***********************/


    eventsContainer.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('draggable')) {
            handleDragStart(e);
        }
    });

    eventsContainer.addEventListener('drag', function(e) {
        if (e.target.classList.contains('draggable')) {
            handleDrag(e);
        }
    });

    eventsContainer.addEventListener('dragend', function(e) {
        if (e.target.classList.contains('draggable')) {
            handleDragEnd(e);
        }
    });

    eventsContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    });

    eventsContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        if (draggedElement) {
            setEventPosition(e, draggedElement, this);
        }
    });

    /* Trigger updateEventPositions when window resizes */
    window.addEventListener('resize', debounce(updateEventPositions, 250));
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            }, wait);
            if (immediate && !timeout) func.apply(context, args);
        };
    }

});

</script>