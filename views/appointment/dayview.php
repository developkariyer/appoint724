<?php
use app\components\MyUrl;

/* @var $this yii\web\View */
/* @var $pixPerHour int */
/* @var $days array */
/* @var $events array */

// $days are in PHP DateTime format. Convert them to strings as DD MonthName YYYY WeekdayName

$formatter = new IntlDateFormatter(
    'tr_TR',                     
    IntlDateFormatter::FULL,    
    IntlDateFormatter::NONE,    
    'Europe/Istanbul',          
    IntlDateFormatter::GREGORIAN,
    'EEEE'                      
);

$aSundayDate = new DateTime('2024-01-07', new DateTimeZone('Europe/Istanbul'));
$daysOfWeek = [];
for ($t = 0; $t<7; $t++) {
    $daysOfWeek[$t] = $formatter->format($aSundayDate);
    $aSundayDate->modify("+1 day");
}

$timezone = new DateTimeZone('Europe/Istanbul'); //TODO customer timezone will be used
$today = new DateTime('now', $timezone);

foreach ($showDays as $key=>$showDay) {
    $days[$key] = $showDay->format('Y-m-d');
}

$dayCount = count($days);

$nowMinutes = $today->format('H') * 60 + $today->format('i');
$dayWidth = 100 / $dayCount;

?>

<div id="info-box" style="z-index: 10000; display: none; position: fixed; top: 0px; left: 200px; height: 40px; background: red; color: white; padding: 3px;">
    Drag info will appear here.
</div>
<div id="info-box" style="z-index: 10000; display: block; position: fixed; top: 0px; left: 600px; height: 40px; background: red; color: white; padding: 3px;">
    <button onclick="fetchEvents();">Fetch All Events</button>
    <button onclick="redrawEvents();">Redraw All Events</button>
    <button onclick="findIslands();">Find Islands</button>
</div>

<div class="calendar-container">
    <div class="row p-0 m-0">
        <?php foreach ($showDays as $key => $day) : ?>
            <div class="col row px-2<?= ($day == $today) ? ' bg-info flashing' : '' ?>" style="width: calc(100% / <?= $dayCount ?>); padding: 0; margin: 0;">
                <div class="col text-center text-muted" id="day<?= $key ?>"><?= $day->format('Y-m-d') ?><br><?= $daysOfWeek[$day->format('w')] ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="scroll-container" style="height: <?= $pixPerHour*24 ?>px;">
        <div id="timeslot-container">
            <?php foreach ($days as $key=>$day) : ?>
                <div class="day-box" style="left: <?= $key * $dayWidth ?>%; width: <?= $dayWidth ?>%;"></div>
            <?php endforeach; ?>
            <div class="row p-0 m-0">
                <?php for ($t = 0; $t < $dayCount; $t++) : ?>
                    <div class="col" style="width: calc(100% / <?= $dayCount ?>); padding: 0; margin: 0;">
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
$currentHourLineWidth = in_array($today, $showDays) ? $dayWidth : 0;
$currentHourLineLeft = $currentHourLineWidth ? $dayWidth * array_search($today, $showDays) : 0;

$this->registerCss(<<<CSS
    .scroll-container {
        overflow-y: auto;
        display: flex;
        position: relative;
    }
    #currentHourLine {
        position: absolute;
        width: {$currentHourLineWidth}%;
        height: 1px;
        background-color: red;
        left: {$currentHourLineLeft}%;
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
    @keyframes flash {
        0%, 100% {
            background-color: #f0f0f0; 
        }
        50% {
            background-color: #ff3333; 
        }
    }
    .flashing {
        animation: flash 2s linear 2;
    }
CSS);
?>
<script>

const MyApp = {

        /*****************************/
       /*                           */
      /*   variable declarations   */
     /*                           */
    /*****************************/

    dateList : <?= json_encode($days) ?>,
    dayNames : <?= json_encode($daysOfWeek) ?>,
    dayCount : <?= $dayCount ?>,
    eventsContainer : document.getElementById('events-container'),
    eventList : [],
    eventColumns : [],
    pixPerHour : <?= $pixPerHour ?>,
    dayWidth : <?= $dayWidth ?>,
    islands : [],

        /*****************************/
       /*                           */
      /*   function declarations   */
     /*                           */
    /*****************************/

    fetchAllEvents: async function () {
        const response = await fetch('<?= MyUrl::to(['appointment/events/demo', 'cache' => true]) ?>');
        this.eventList = await response.json();
        this.convertEventTimes(this.eventList);
        this.initializeEvents();
    },    

    calculateMinutes: function (event) {
        event.startMinute = event.startDate.getHours() * 60 + event.startDate.getMinutes();
        event.endMinute = event.endDate.getHours() * 60 + event.endDate.getMinutes();
        if (event.startDate.getDate() !== event.endDate.getDate()) {
            event.endMinute = 1440;
        }
    },

    findDateColumn: function(searchDate) {
        for (let i = 0; i < this.dateList.length; i++) {
            if (searchDate.slice(0, 10) === this.dateList[i]) {
                return i;
            }
        }
        return -1;
    },

    convertEventTimes: function (eList) {
        eList.forEach(event => {
            event.startDate = new Date(event.start);
            event.endDate = new Date(event.end);
            event.day = this.findDateColumn(event.start);
            this.calculateMinutes(event);
        });
    },

    initializeEvents: function (events) {
        this.deleteEvents();
        this.findIslands();
        this.assignIslandsToColumns();
        this.expandIslandColumns();
        this.setIslandEventPositions();
        this.deleteEvents();
        this.createUpdateEventObjects(true);
    },

    deleteEvents: function () {
        this.eventsContainer.innerHTML = '';
    },

    updateEvent: function(id, day, startMinute) {
        function toISOStringWithoutTZ(date) {
            const pad = (number) => number < 10 ? '0' + number : number;
            const year = date.getFullYear();
            const month = pad(date.getMonth() + 1);
            const day = pad(date.getDate());
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());
            const seconds = pad(date.getSeconds());
            return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
        }
        const event = this.eventList.find(event => event.id === id);
        const duration = event.endDate.getTime() - event.startDate.getTime();
        const newStartDate = new Date(this.dateList[day]);
        newStartDate.setHours(Math.floor(startMinute / 60), startMinute % 60, 0, 0);
        const newEndDate = new Date(newStartDate.getTime() + duration);
        event.startDate = newStartDate;
        event.endDate = newEndDate;
        event.start = toISOStringWithoutTZ(newStartDate);
        event.end = toISOStringWithoutTZ(newEndDate);
        event.day = this.findDateColumn(event.start);
        this.calculateMinutes(event);
        this.updateEventObject(event, true);
        this.findIslands();
        this.assignIslandsToColumns();
        this.expandIslandColumns();
        this.setIslandEventPositions();
        this.createUpdateEventObjects(false);
    },

    findIslands: function () {
        this.eventList.sort((a, b) => a.startDate - b.startDate);
        this.islands = [];
        let cursor = this.eventList[0].startDate;
        let day = this.eventList[0].day;
        this.eventList.forEach(event => {
            if (event.startDate >= cursor || event.day !== day) {
                island = [event];
                this.islands.push(island);
                cursor = event.endDate;
            } else {
                if (event.endDate > cursor) {
                    cursor = event.endDate;
                } 
                island.push(event);
            }
            day = event.day;
        });
    },

    assignIslandsToColumns: function () {
        this.islands.forEach(island => {
            let islandColumns = 0;
            island['columns'] = [];
            island.sort((a, b) => a.startMinute - b.startMinute);
            for (let i = 0; i < island.length; i++) {
                let columnFound = false;
                for (let j = 0; j < island['columns'].length; j++) {
                    const lastEventInColumn = island['columns'][j][island['columns'][j].length - 1];
                    if (lastEventInColumn && lastEventInColumn.endMinute <= island[i].startMinute) {
                        island['columns'][j].push(island[i]);
                        columnFound = true;
                        break;
                    }
                }
                if (!columnFound) {
                    island['columns'].push([island[i]]);
                }
            }
        });
    },

    expandIslandColumns: function () {
        function eventsOverlap(event1, event2) {
            return event1.startMinute < event2.endMinute && event2.startMinute < event1.endMinute;
        }
        this.islands.forEach((island, index) => {
            for (let i = 0; i < island['columns'].length; i++) {
                const column = island['columns'][i];
                for (let j = 0; j < column.length; j++) {
                    const event = column[j];
                    event.spanLeft = 0;
                    event.spanRight = 0;
                    let expandLeft = 0;
                    let expandRight = 0;
                    for (let k = i - 1; k >= 0; k--) {
                        let canExpand = true;
                        for (let m = 0; m < island['columns'][k].length; m++) {
                            eol = eventsOverlap(event, island['columns'][k][m]);
                            if (eol) {
                                canExpand = false;
                                break;
                            }
                        }
                        if (!canExpand) { break; }
                        expandLeft++;
                    }
                    for (let k = i + 1; k < island['columns'].length; k++) {
                        let canExpand = true;
                        for (let m = 0; m < island['columns'][k].length; m++) {
                            eol = eventsOverlap(event, island['columns'][k][m]);
                            if (eol) {
                                canExpand = false;
                                break;
                            }
                        }
                        if (!canExpand) { break; }
                        expandRight++;
                    }
                    event.spanLeft = expandLeft;
                    event.spanRight = expandRight;
                }
            }
        });
    },

    setIslandEventPositions: function () {
        let tabIndex = 1;
        this.islands.forEach((island, islandIndex) => {
            island['columns'].forEach((column, columnIndex) => {
                column.forEach(event => {
                    key = event.day;
                    columnCount = island['columns'].length;
                    event.island = islandIndex;
                    event.column = columnIndex;
                    event.top = event.startMinute * this.pixPerHour / 60;
                    event.height = (event.endMinute>event.startMinute) ? 
                            (event.endMinute - event.startMinute) * this.pixPerHour / 60 : (1440-event.startMinute) * this.pixPerHour / 60;
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
            if (event.day>=0) {
                if (create) {
                    this.createEventObject(event, false);
                } else {
                    this.updateEventObject(event);
                }
            }
        });
        this.initializePopovers()
    },

    createEventObject: function (event, flashing = true) {
        const eventDiv = document.createElement('div');
        eventDiv.className = flashing ? 'event-box draggable flashing' : 'event-box draggable';
        eventDiv.id = event.id;
        eventDiv.style.cssText = `top: ${event.top}px; height: ${event.height}px; width: calc(${event.width}% - ${event.widthMargin}px); left: calc(${event.left}% - ${event.leftMargin}px);`;
        eventDiv.setAttribute('role', 'button');
        eventDiv.setAttribute('draggable', true);
        eventDiv.setAttribute('data-day', event.day);
        eventDiv.setAttribute('data-slotorder', 0);
        eventDiv.setAttribute('data-bs-toggle', 'popover');
        eventDiv.setAttribute('data-bs-placement', 'top');
        eventDiv.setAttribute('data-bs-trigger', 'focus hover');
        eventDiv.setAttribute('data-bs-title', event.title);
        eventDiv.setAttribute('data-bs-content', event.text+
            ` spanLeft:${event.spanLeft} spanRight:${event.spanRight} column:${event.column} island:${event.island}`);
        eventDiv.setAttribute('tabIndex', event.tabIndex);
        const spanTitle = document.createElement('span');
        spanTitle.className = 'event-title';
        spanTitle.textContent = event.title;
        eventDiv.appendChild(spanTitle);
        this.eventsContainer.appendChild(eventDiv);
        event.changed = false;
    },

    updateEventObject: function (event, forceDelete = false) {
        const eventDiv = document.getElementById(event.id);
        if (eventDiv) {
            if (forceDelete) {
                eventDiv.remove();
            } else {
                eventDiv.style.cssText = `top: ${event.top}px; height: ${event.height}px; width: calc(${event.width}% - ${event.widthMargin}px); left: calc(${event.left}% - ${event.leftMargin}px); padding: 0px;`;
                eventDiv.setAttribute('data-bs-content', event.text+` spanLeft:${event.spanLeft} spanRight:${event.spanRight} columnCount:${event.columnCount} column:${event.column}`);
                eventDiv.setAttribute('tabIndex', event.tabIndex);
                event.changed = false;
                return;
            }
        }
        this.createEventObject(event);
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
function findIslands() { MyApp.findIslands();}

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