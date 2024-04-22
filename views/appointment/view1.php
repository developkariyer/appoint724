<?php
use app\components\MyUrl;
use yii\bootstrap5\Popover;

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
            <?php foreach ($events as $key=>$event) {
                echo eventPrint($event, $key+1, $pixPerHour+5, $days);
            } ?>
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

async function fetchEvents() {
    const response = await fetch('<?= MyUrl::to(['appointment/events/demo']) ?>');
    const events = await response.json();
    addEventsToDOM(events);
}

function addEventsToDOM(events) {
    const eventsContainer = document.getElementById('events-container');

    events.forEach(event => {
        const startTime = new Date(event.start.date);
        const top = (startTime.getHours() * 60 + startTime.getMinutes()) * <?= $pixPerHour ?> / 60;
        const height = event.duration * <?= $pixPerHour ?> / 60;
        const dayWidth = 100 / 7; /* TODO to be calculated based on number of days */
        const left = dayWidth * event.day;
        const eventDiv = document.createElement('div');
        eventDiv.className = 'event-box draggable';
        eventDiv.draggable = true;
        eventDiv.id = 'event'+event.id;
        eventDiv.style.cssText = `top: ${top}px; height: ${height}px; width: calc(${dayWidth}% - 40px); left: ${left}%;`;
        eventDiv.setAttribute('role', 'button');
        eventDiv.setAttribute('data-day', event.day);
        eventDiv.setAttribute('data-bs-toggle', 'popover');
        eventDiv.setAttribute('data-bs-placement', 'top');
        eventDiv.setAttribute('data-bs-trigger', 'focus');
        eventDiv.setAttribute('data-bs-title', event.title);
        eventDiv.setAttribute('data-bs-content', event.title);

        const spanTitle = document.createElement('span');
        spanTitle.className = 'event-title';
        spanTitle.textContent = event.title;
        eventDiv.appendChild(spanTitle);

        eventsContainer.appendChild(eventDiv);

        // Initialize Bootstrap Popover for this element
        new bootstrap.Popover(eventDiv);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    var popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    var popoverList = Array.from(popoverTriggerList).map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            container: 'body',
            boundary: 'viewport',
        });
    });
    var draggedElement = null;
    const dayCount = <?= count($days) ?>;
    const eventsContainer = document.getElementById('events-container');
    const pixPerHour = <?= $pixPerHour ?>;

    fetchEvents();

    function setEventPosition(e, element) {
        const dayWidth = eventsContainer.offsetWidth / dayCount;
        const rect = eventsContainer.getBoundingClientRect();
        let top = e.clientY - rect.top;
        let left = e.clientX - rect.left;

        console.log(left, dayWidth);

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
        /*titleSpan.textContent = `${eventDay} ${eventHours}:${eventMinutes}`;*/
        const popoverInstance = bootstrap.Popover.getInstance(element);
        if (popoverInstance) {
            popoverInstance.setContent({
                '.popover-body': `${eventDay} ${eventHours}:${eventMinutes}`
            });
            popoverInstance.update();
        }
    }

    document.querySelectorAll('.draggable').forEach(function(item) {
        item.addEventListener('dragstart', function(e) {
            draggedElement = e.target; 
            e.dataTransfer.effectAllowed = 'move';

            var popoverInstance = bootstrap.Popover.getInstance(draggedElement);
            if (popoverInstance) {
                popoverInstance.hide(); 
            }
            const infoBox = document.getElementById('info-box');
            infoBox.style.display = 'block';
            infoBox.textContent = draggedElement.querySelector('.event-title').innerHTML;
        });

        item.addEventListener('drag', function(e) {
            // Log mouse position during the drag, if available
            if (e.clientX > 0 && e.clientY > 0) {
                const dayWidth = eventsContainer.offsetWidth / dayCount;
                const rect = eventsContainer.getBoundingClientRect();
                const top = e.clientY - rect.top; - (draggedElement.offsetHeight / 2);
                const left = e.clientX - rect.left;
                const day = Math.floor(left / dayWidth);
                const dayDivId = `day${day}`;
                const eventDay = document.getElementById(dayDivId).innerHTML;
                const startMinute = Math.floor((top / pixPerHour) * 60 /5)*5;
                const eventHours = Math.floor(startMinute / 60).toString().padStart(2, '0');
                const eventMinutes = (startMinute % 60).toString().padStart(2, '0');
                const infoBox = document.getElementById('info-box');

                infoBox.textContent = draggedElement.querySelector('.event-title').innerHTML+` is being moved to ${eventDay} ${eventHours}:${eventMinutes}`;
            }
        });

        item.addEventListener('dragend', function(e) {
            e.preventDefault();
            setEventPosition(e, draggedElement);
            const infoBox = document.getElementById('info-box');
            infoBox.style.display = 'none';
            const popoverInstance = bootstrap.Popover.getOrCreateInstance(draggedElement);
            popoverInstance.show();
        });
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

    /* recalculate event positions when called (triggered below by debounce) */
    function updateEventPositions() {
        const dayWidth = eventsContainer.offsetWidth / dayCount;
        document.querySelectorAll('.event-box').forEach(function(item) {
            const day = item.dataset.day;
            item.style.left = `${day * dayWidth}px`;
        });
    }

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

    document.addEventListener('DOMContentLoaded', function() {
        // Get all resizable events
        const resizableEvents = document.querySelectorAll('.resizable-event');

        // Add resize functionality to each event
        resizableEvents.forEach(function(resizable) {
            const resizeHandle = resizable.querySelector('.resize-handle');

            let isResizing = false;

            resizeHandle.addEventListener('mousedown', function(e) {
                e.preventDefault();
                isResizing = true;
                window.addEventListener('mousemove', handleMouseMove);
                window.addEventListener('mouseup', stopResize);
            });

            function handleMouseMove(e) {
                if (isResizing) {
                    // Calculate the new dimensions
                    const width = e.clientX - resizable.getBoundingClientRect().left;
                    const height = e.clientY - resizable.getBoundingClientRect().top;
                    resizable.style.width = `${width}px`;
                    resizable.style.height = `${height}px`;
                }
            }

            function stopResize(e) {
                isResizing = false;
                window.removeEventListener('mousemove', handleMouseMove);
                window.removeEventListener('mouseup', stopResize);
            }
        });
    });


});

</script>