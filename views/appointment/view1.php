<?php
use yii\bootstrap5\Popover;

$timezone = new DateTimeZone('Europe/Istanbul');
$now = new DateTime('now', $timezone);
$currentHour = $now->format('H');
$dayWidth = 100 / count($days);

function eventPrint($event, $tabIndex, $pixPerHour, $days) 
{
    $top = ($event['start']->format('H') * 60 + $event['start']->format('i')) * $pixPerHour / 60;
    $height = ($event['duration']) * $pixPerHour / 60;
    $width = 100 / count($days);

    return <<<HTML
        <a
            tabindex="{$tabIndex}"
            class="event-box draggable"
            draggable="true"
            id="event-{$event['title']}"
            style="top: {$top}px; height: {$height}px; width: calc({$width}% - 40px);"
            role="button"
            data-day="0"
            data-bs-toggle="popover"
            data-bs-placement="top"
            data-bs-trigger="focus" data-bs-title="Dismissible popover"
            data-bs-content="And here's some amazing content. It's very engaging. Right?"
        >{$event['title']}</a>
        HTML;
}

?>
<div class="calendar-container">
    <div class="row p-0 m-0">
        <?php foreach ($days as $key=>$day) : ?>
            <div class="col row px-2" style="width: calc(100% / <?= count($days) ?>); padding: 0; margin: 0;">
                <div class="col text-center text-muted"><?= $day ?></div>
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
                echo eventPrint($event, $key+1, $pixPerHour, $days);
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

    function setEventPosition(e, element, container) {
        const dayWidth = eventsContainer.offsetWidth / dayCount;
        const rect = container.getBoundingClientRect();
        let top = e.clientY - rect.top - (element.offsetHeight / 2);
        let left = e.clientX - rect.left - (element.offsetWidth / 2);

        if (top<0) top = 0;
        if (left<0) left = 0;

        let day = Math.floor(left / dayWidth);
        left = day * dayWidth;
        /* let startTime with 5 minutes interval, not 5 hour */
        let startMinute = Math.floor((top / pixPerHour) * 60 / 15) * 15;
        top = startMinute * pixPerHour / 60;

        element.dataset.day = day;
        element.style.top = `${top}px`;
        element.style.left = `${left}px`;
        console.log(top, left, day, dayCount, dayWidth);
    }

    function updateEventPositions() {
        const dayWidth = eventsContainer.offsetWidth / dayCount;
        document.querySelectorAll('.event-box').forEach(function(item) {
            const day = item.dataset.day;
            item.style.left = `${day * dayWidth}px`;
        });
    }

    document.querySelectorAll('.draggable').forEach(function(item) {
        item.addEventListener('dragstart', function(e) {
            draggedElement = e.target; 
            e.dataTransfer.effectAllowed = 'move';

            var popoverInstance = bootstrap.Popover.getInstance(draggedElement);
            if (popoverInstance) {
                popoverInstance.hide(); 
            }
        });

        item.addEventListener('dragend', function(e) {
            e.preventDefault();
            setEventPosition(e, draggedElement, document.getElementById('events-container'));
        });
    });

    eventsContainer.addEventListener('dragover', function(e) {
        e.preventDefault(); // Necessary to allow dropping
        e.dataTransfer.dropEffect = 'move';
    });

    eventsContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        if (draggedElement) {
            setEventPosition(e, draggedElement, this);
        }
    });

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