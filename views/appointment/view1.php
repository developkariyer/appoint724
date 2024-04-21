<?php
use yii\bootstrap5\Popover;

$pixPerHour = 40;
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

function displayDayBoxes($days) {
    $dayWidth = 100 / count($days);
    foreach ($days as $day) {
        echo "<div class='day-box' style='left: ". array_search($day, $days)*$dayWidth ."%; width: $dayWidth%;'><small>$day</small></div>";
    }
}

function displayTimeSlots() {
    $timezone = new DateTimeZone('Europe/Istanbul');
    $now = new DateTime('now', $timezone);
    $currentHour = $now->format('H');

    for ($hour = 0; $hour < 24; $hour++) {
        if ($currentHour == $hour) {
            echo "<div class='timeslot bg-success text-white'><small>" . sprintf("%02d:00", $hour) . "</small></div>";
        } else {
            echo "<div class='timeslot'><small>" . sprintf("%02d:00", $hour) . "</small></div>";
        }
    }
}

function displayEvents($pixPerHour = 40, $dayCount) {
    $events = [
        [
            'title' => 'Morning Meeting',
            'start' => DateTime::createFromFormat('H:i', '03:00'),
            'duration' => 50,
        ],
        [
            'title' => 'Morning Meeting Morning Meeting Morning Meeting Morning Meeting Morning Meeting ',
            'start' => DateTime::createFromFormat('H:i', '05:00'),
            'duration' => 50,
        ],
        [
            'title' => 'Morning Meeting',
            'start' => DateTime::createFromFormat('H:i', '07:00'),
            'duration' => 50,
        ],
        [
            'title' => 'Morning Meeting',
            'start' => DateTime::createFromFormat('H:i', '09:00'),
            'duration' => 50,
        ],
        [
            'title' => 'Morning Meeting',
            'start' => DateTime::createFromFormat('H:i', '11:00'),
            'duration' => 50,
        ],
        [
            'title' => 'Morning Meeting',
            'start' => DateTime::createFromFormat('H:i', '13:00'),
            'duration' => 50,
        ],
        [
            'title' => 'Lunch Break',
            'start' => DateTime::createFromFormat('H:i', '15:30'),
            'duration' => 90,
        ],
    ];

    foreach ($events as $event) {
        $top = ($event['start']->format('H') * 60 + $event['start']->format('i') + rand(-20, 20))*$pixPerHour/60;
        $height = ($event['duration']+rand(0,30))*$pixPerHour/60;
        ?>
        <a tabindex="0" class="event-box draggable"  draggable="true" id="event-<?= htmlspecialchars($event['title']) ?>"
            style="top: <?= htmlspecialchars($top) ?>px; 
                height: <?= htmlspecialchars($height) ?>px; 
                width: calc(<?= 100/$dayCount ?>% - 40px);"
            role="button" data-bs-toggle="popover" 
            data-bs-placement="top"
            data-bs-trigger="focus" data-bs-title="Dismissible popover" 
            data-bs-content="And here's some amazing content. It's very engaging. Right?"
        ><?= htmlspecialchars($event['title']) ?></a>

        <?php
    }
}

?>

<div class="calendar-container">
    <div class="scroll-container">
        <div id="timeslot-container">
            <?php displayDayBoxes($days); ?>
            <?php displayTimeSlots(); ?>
        </div>
        <div id="events-container">
            <?php displayEvents($pixPerHour, count($days)); ?>
        </div>
    </div>
</div>

<?php

$pixPerHour24 = $pixPerHour * 24;

$this->registerCss(<<<CSS
        .scroll-container {
            overflow-y: auto;
            display: flex;
            position: relative;
            height: {$pixPerHour24}px;

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
            height: {$pixPerHour}px;
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

    function setPosition(e, element, container) {
        const rect = container.getBoundingClientRect();
        const top = e.clientY - rect.top - (element.offsetHeight / 2);
        const left = e.clientX - rect.left - (element.offsetWidth / 2);

        if (top<0) top = 0;
        if (left<0) left = 0;

        element.style.top = `${top}px`;
        element.style.left = `${left}px`;
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
            setPosition(e, draggedElement, document.getElementById('events-container'));
        });
    });

    const eventsContainer = document.getElementById('events-container');
    eventsContainer.addEventListener('dragover', function(e) {
        e.preventDefault(); // Necessary to allow dropping
        e.dataTransfer.dropEffect = 'move';
    });

    eventsContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        if (draggedElement) {
            setPosition(e, draggedElement, this);
        }
    });

});

</script>