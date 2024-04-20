<?php
use yii\bootstrap5\Popover;

$pixPerHour = 40;

function displayTimeSlots() {
    $timezone = new DateTimeZone('Europe/Istanbul');
    $now = new DateTime('now', $timezone);
    $currentHour = $now->format('H');

    for ($hour = 0; $hour < 24; $hour++) {
        if ($currentHour == $hour) {
            echo "<div class='timeslot bg-success text-white'><small>" . sprintf("%02d:00", $hour) . "</small></div>";
        } else {
            echo "<div class='text-muted timeslot'><small>" . sprintf("%02d:00", $hour) . "</small></div>";
        }
    }
}

function displayEvents($pixPerHour = 40) {
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
        <a tabindex="0" class="event btn btn-sm"
        style="position: absolute; top: <?= $top ?>px; height: <?= $height ?>px;"
        role="button" data-bs-toggle="popover" 
        data-bs-placement="top"
        data-bs-trigger="hover focus" data-bs-title="Dismissible popover" 
        data-bs-content="And here's some amazing content. It's very engaging. Right?"><?= $event['title'] ?></a>
        <?php
    }
}

?>

<div class="calendar-container">
    <div class="scroll-container">
        <div id="timeslot-container">
            <?php displayTimeSlots(); ?>
        </div>
        <div id="events-container">
            <?php displayEvents($pixPerHour); ?>
        </div>
        <div id="events-container"">
            <?php displayEvents($pixPerHour); ?>
        </div>
    </div>
</div>

<?php

$this->registerCss(<<<CSS
        .scroll-container {
            overflow-y: auto;
            display: flex;
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
            border: 1px solid #dee2e6;
        }
        #timeslot-container {
            flex: 0 0 50px; 
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        #events-container {
            z-index: 1;
            flex-grow: 1;
            position: relative;
            margin-right: 1rem;
            margin-left: 1rem;
        }
        .timeslot {
            height: {$pixPerHour}px;
            padding: 0px;
            border-bottom: 1px solid #dee2e6;
        }
        .event {
            z-index: 2;
            display: flex;
            position: absolute;
            left: 0;
            right: 0;
            background-color: #007bff;
            color: white;
            padding: 0.3rem;
            border-radius: 0.3rem;
            align-items: start;
            justify-content: start;
            text-align: left;
        }
        .event.btn:hover {
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
});
</script>