<?php
use app\components\MyUrl;

/* @var $this yii\web\View */
/* @var $pixPerHour int */
/* @var $days array */
/* @var $events array */

// $days are in PHP DateTime format. Convert them to strings as DD MonthName YYYY WeekdayName

$formatter = new IntlDateFormatter(
    'tr_TR',                     // Locale
    IntlDateFormatter::FULL,     // Date type
    IntlDateFormatter::NONE,     // Time type, none because we only want the date
    'Europe/Istanbul',           // Timezone (optional if you want timezone specific formatting)
    IntlDateFormatter::GREGORIAN,// Calendar type
    'EEEE'                       // Pattern (EEEE is the full name of the day)
);

foreach ($days as $key=>$day) {
    $dayNames[$key] = $formatter->format($day);
    $days[$key] = $day->format('d M Y');
}

$timezone = new DateTimeZone('Europe/Istanbul'); //TODO customer timezone will be used
$now = new DateTime('now', $timezone);
$nowMinutes = $now->format('H') * 60 + $now->format('i');
$dayWidth = 100 / count($days);

?>

<div id="info-box" style="z-index: 10000; display: none; position: fixed; top: 0px; left: 200px; height: 40px; background: red; color: white; padding: 3px;">
    Drag info will appear here.
</div>
<div id="info-box" style="z-index: 10000; display: block; position: fixed; top: 0px; left: 600px; height: 40px; background: red; color: white; padding: 3px;">
    <button onclick="fetchEvents();">Fetch All Events</button>
    <button onclick="redrawEvents();">Redraw All Events</button>
</div>

<div class="calendar-container">
    <div class="row p-0 m-0">
        <?php foreach ($dayNames as $key=>$day) : ?>
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
        <div id="currentHourLine"></div>
        <div id="events-container"></div>
    </div>
</div>

<?php

$currentHourLineTop = floor($nowMinutes * $pixPerHour / 60);

$this->registerCss(<<<CSS
        .scroll-container {
            overflow-y: auto;
            display: flex;
            position: relative;

        }
        #currentHourLine {
            position: absolute;
            width: 100%;
            height: 1px;
            background-color: red;
            left: 0px;
            z-index: 4;
            top: {$currentHourLineTop}px;
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
            padding: 0px 0px;
            border-radius: 2px;
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

    dayNames : <?= json_encode($dayNames) ?>,
    dayCount : <?= count($days) ?>,
    eventsContainer : document.getElementById('events-container'),
    eventList : [],
    eventColumns : [],
    pixPerHour : <?= $pixPerHour ?>,
    dayWidth : <?= $dayWidth ?>,


        /*****************************/
       /*                           */
      /*   function declarations   */
     /*                           */
    /*****************************/

    fetchAllEvents: async function () {
        const response = await fetch('<?= MyUrl::to(['appointment/events/demo']) ?>');
        this.eventList = await response.json();
        this.convertEventTimes(this.eventList);
        this.initializeEvents();
    },    

    convertEventTimes: function (eList) {
        eList.forEach(event => {
            if (event.startMinute === undefined) {
                let startDate = new Date(event.start);
                let endDate = new Date(event.end);
                event.startMinute = startDate.getHours() * 60 + startDate.getMinutes();
                event.endMinute = endDate.getHours() * 60 + endDate.getMinutes();
            }
        });
    },

    syncAllEvents: async function () {
        const response = await fetch('<?= MyUrl::to(['appointment/events/demo']) ?>');
        newEventList = await response.json();
        this.convertEventTimes(newEventList);
        newEventList.forEach(newEevent => {
            const event = this.eventList.find(event => event.id === newEvent.id);
            if (event) {
                if (event.startMinute !== newEvent.startMinute || event.endMinute !== newEvent.endMinute || event.day !== newEvent.day || event.title !== newEvent.title) {
                    event.changed = true;
                    event.startMinute = newEvent.startMinute;
                    event.endMinute = newEvent.endMinute;
                    event.day = newEvent.day;
                    event.title = newEvent.title;
                }
            } else {
                newEvent.changed = true;
                this.eventList.push(newEvent);
            }
        });
    },

    initializeEvents: function (events) {
        this.deleteEvents();
        this.assignEventsToColumns()
        this.setEventPositions();
        this.createUpdateEventObjects(true);
    },

    updateEvent: function(id, day, startMinute) {
        const event = this.eventList.find(event => event.id === id);
        event.day = this.dayNames[day];
        const duration = event.endMinute - event.startMinute;
        event.startMinute = startMinute;
        event.endMinute = startMinute + duration;

        this.assignEventsToColumns();
        this.setEventPositions();
        this.createUpdateEventObjects();
    },

    deleteEvents: function () {
        this.eventsContainer.innerHTML = '';
    },

    assignEventsToColumns: function() {
        function eventsOverlap(event1, event2) {
            return event1.startMinute < event2.endMinute && event2.startMinute < event1.endMinute;
        }

        /*this.eventList.sort((a, b) => a.startMinute - b.startMinute);*/

        currentColumnLength = [];

        this.dayNames.forEach(day => {
            if (typeof this.eventColumns[day] === 'undefined') {
                this.eventColumns[day] = [];
            }
            const dayEvents = this.eventList.filter(event => event.day === day);
            dayEvents.sort((a, b) => a.startMinute - b.startMinute);
            currentColumnLength[day] = this.eventColumns[day].length;
            this.eventColumns[day] = [];
            for (const event of dayEvents) {
                let columnFound = false;
                for (let i = 0; i < this.eventColumns[day].length; i++) {
                    const lastEventInColumn = this.eventColumns[day][i][this.eventColumns[day][i].length - 1];
                    if (lastEventInColumn && lastEventInColumn.endMinute <= event.startMinute) {
                        this.eventColumns[day][i].push(event);
                        columnFound = true;
                        break; 
                    }
                }
                if (!columnFound) {
                    this.eventColumns[day].push([event]);
                }
            }

            const dayColumns = this.eventColumns[day];

            allChanged = (currentColumnLength[day] !== dayColumns.length) ? true : false;
            for (let i = 0; i < dayColumns.length; i++) {
                const column = dayColumns[i];
                for (let j = 0; j < column.length; j++) {
                    const event = column[j];
                    event.changed = allChanged || event.changed || event.column !== i ? true : false;
                    event.column = i;
                    let expandLeft = 0;
                    for (let k = i - 1; k >= 0; k--) {
                        let canExpand = true;
                        for (let m = 0; m < dayColumns[k].length; m++) {
                            if (eventsOverlap(event, dayColumns[k][m])) {
                                canExpand = false;
                                break;
                            }
                        }
                        if (!canExpand) { break; }
                        expandLeft++;
                    }
                    event.changed = event.changed || (event.spanLeft !== expandLeft) ? true : false;
                    event.spanLeft = expandLeft;
                    let expandRight = 0;
                    for (let k = i + 1; k < dayColumns.length; k++) {
                        let canExpand = true;
                        for (let m = 0; m < dayColumns[k].length; m++) {
                            if (eventsOverlap(event, dayColumns[k][m])) {
                                canExpand = false;
                                break;
                            }
                        }
                        if (!canExpand) { break; }
                        expandRight++; 
                    }
                    event.changed = allChanged || event.changed || (event.spanRight !== expandRight) ? true : false;
                    event.spanRight = expandRight;
                }
            }
        });
    },

    setEventPositions: function () {
        let tabIndex = 1;
        this.dayNames.forEach((day, key)=> {
            const dayColumns = this.eventColumns[day];
            const columnCount = dayColumns.length;
            dayColumns.forEach((column, columnIndex) => {
                column.forEach((event, index) => {
                    event.top = event.startMinute * this.pixPerHour / 60;
                    event.height = (event.endMinute - event.startMinute) * this.pixPerHour / 60;
                    event.left = this.dayWidth * key + (columnIndex - event.spanLeft) * this.dayWidth / columnCount;
                    event.width = (1+event.spanRight) * this.dayWidth / columnCount;
                    event.widthMargin = Math.floor((1+event.spanLeft+event.spanRight)*40/columnCount)+(columnCount ? 2 : 0);
                    event.leftMargin = Math.floor(columnIndex *  40/columnCount);
                    event.text = Math.floor(event.startMinute/60).toString().padStart(2, '0')+':'+(event.startMinute%60).toString().padStart(2, '0');                    
                    event.tabIndex = tabIndex++;
                });
            });
        });
    },

    createUpdateEventObjects: function (create = false) {
        this.eventList.forEach(event => {
            if (create) {
                this.createEventObject(event);
            } else {
                this.updateEventObject(event);
            }
        });
        this.initializePopovers()
    },

    createEventObject: function (event) {
        const eventDiv = document.createElement('div');
        eventDiv.className = 'event-box draggable';
        eventDiv.id = event.id;
        eventDiv.style.cssText = `top: ${event.top}px; height: ${event.height}px; width: calc(${event.width}% - ${event.widthMargin}px); left: calc(${event.left}% - ${event.leftMargin}px);`;
        eventDiv.setAttribute('role', 'button');
        eventDiv.setAttribute('draggable', true);
        eventDiv.setAttribute('data-day', event.day);
        eventDiv.setAttribute('data-slotorder', 0);
        eventDiv.setAttribute('data-bs-toggle', 'popover');
        eventDiv.setAttribute('data-bs-placement', 'top');
        eventDiv.setAttribute('data-bs-trigger', 'focus');
        eventDiv.setAttribute('data-bs-title', event.title);
        eventDiv.setAttribute('data-bs-content', event.text+
            ` spanLeft:${event.spanLeft} spanRight:${event.spanRight} column:${event.column}`);
        eventDiv.setAttribute('tabIndex', event.tabIndex);

        const spanTitle = document.createElement('span');
        spanTitle.className = 'event-title';
        spanTitle.textContent = event.title;
        eventDiv.appendChild(spanTitle);

        this.eventsContainer.appendChild(eventDiv);
        event.changed = false;
    },

    updateEventObject: function (event) {
        // find the event object in the DOM using id = 'event'+event.id
        const eventDiv = document.getElementById(event.id);
        if (!eventDiv) {
            this.createEventObject(event);
            return;
        }
        eventDiv.style.cssText = `top: ${event.top}px; height: ${event.height}px; width: calc(${event.width}% - ${event.widthMargin}px); left: calc(${event.left}% - ${event.leftMargin}px); padding: 0px;`;
        eventDiv.setAttribute('data-bs-content', event.text+` spanLeft:${event.spanLeft} spanRight:${event.spanRight} columnCount:${event.columnCount} column:${event.column}`);
        eventDiv.setAttribute('tabIndex', event.tabIndex);
        event.changed = false;
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
function redrawEvents() { MyApp.initializeEvents();}

document.addEventListener('DOMContentLoaded', function () {

    MyApp.fetchAllEvents();
    
    const eventsContainer = document.getElementById('events-container');
    const pixPerHour = <?= $pixPerHour ?>;

    let offsetX, offsetY;

    function reportEventPosition(e, element) {
        const dayWidth = eventsContainer.offsetWidth / MyApp.dayCount;
        const rect = eventsContainer.getBoundingClientRect();
        let top = e.clientY - rect.top - offsetY;
        let left = e.clientX - rect.left - offsetX;

        if (top<0) top = 0;
        if (left<0) left = 0;

        const day = Math.floor(left / dayWidth);
        const startMinute = Math.floor((top / pixPerHour) * 60 /5)*5;

        MyApp.updateEvent(element.id, day, startMinute);
    }

    /* recalculate event positions when called (triggered below by debounce) */
    function updateEventPositions() {
        return;
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
        reportEventPosition(e, draggedElement);
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

});

</script>