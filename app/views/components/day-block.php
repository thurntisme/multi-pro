<?php


$schedule = [
  [
    'day' => 'Monday',
    'events' => [
      [
        'startTime' => '09:00',
        'endTime' => '10:00',
        'content' => 'Morning Standup Meeting'
      ],
      [
        'startTime' => '13:00',
        'endTime' => '14:00',
        'content' => 'Lunch with Team'
      ],
      [
        'startTime' => '20:00',
        'endTime' => '22:00',
        'content' => 'English learning'
      ],
    ]
  ],
  [
    'day' => 'Tuesday',
    'events' => [
      [
        'startTime' => '10:00',
        'endTime' => '11:30',
        'content' => 'Project Planning'
      ],
      [
        'startTime' => '15:00',
        'endTime' => '16:00',
        'content' => 'Client Meeting'
      ],
      [
        'startTime' => '20:00',
        'endTime' => '22:00',
        'content' => 'English learning'
      ],
    ]
  ],
  [
    'day' => 'Wednesday',
    'events' => [
      [
        'startTime' => '11:00',
        'endTime' => '12:00',
        'content' => 'Team Retrospective'
      ],
      [
        'startTime' => '14:00',
        'endTime' => '15:00',
        'content' => 'Coding Session'
      ],
      [
        'startTime' => '20:00',
        'endTime' => '22:00',
        'content' => 'English learning'
      ],
    ]
  ],
  [
    'day' => 'Thursday',
    'events' => [
      [
        'startTime' => '09:30',
        'endTime' => '10:30',
        'content' => 'Design Review'
      ],
      [
        'startTime' => '12:00',
        'endTime' => '13:00',
        'content' => 'Lunch with Client'
      ],
      [
        'startTime' => '20:00',
        'endTime' => '22:00',
        'content' => 'English learning'
      ],
    ]
  ],
  [
    'day' => 'Friday',
    'events' => [
      [
        'startTime' => '10:00',
        'endTime' => '11:00',
        'content' => 'Sprint Review'
      ],
      [
        'startTime' => '13:00',
        'endTime' => '14:00',
        'content' => 'Weekly Team Meeting'
      ],
      [
        'startTime' => '20:00',
        'endTime' => '22:00',
        'content' => 'English learning'
      ],
    ]
  ],
  [
    'day' => 'Saturday',
    'events' => [
      [
        'startTime' => '09:00',
        'endTime' => '10:00',
        'content' => 'Weekend Planning'
      ],
      [
        'startTime' => '12:00',
        'endTime' => '13:00',
        'content' => 'Family Lunch'
      ],
      [
        'startTime' => '20:00',
        'endTime' => '22:00',
        'content' => 'English learning'
      ],
    ]
  ],
  [
    'day' => 'Sunday',
    'events' => [
      [
        'startTime' => '10:00',
        'endTime' => '11:00',
        'content' => 'Morning Jog'
      ],
      [
        'startTime' => '14:00',
        'endTime' => '15:00',
        'content' => 'Relax and Read'
      ],
      [
        'startTime' => '20:00',
        'endTime' => '22:00',
        'content' => 'English learning'
      ],
    ]
  ],
];

function findCurrentEvent($events, $currentTime)
{
  foreach ($events as $event) {
    $eventStart = strtotime($event['startTime']);
    $eventEnd = strtotime($event['endTime']);
    $currentTimestamp = strtotime($currentTime);

    if ($currentTimestamp >= $eventStart && $currentTimestamp <= $eventEnd) {
      return $event;
    }
  }

  // If no current event, return the next upcoming event
  foreach ($events as $event) {
    $eventStart = strtotime($event['startTime']);
    $currentTimestamp = strtotime($currentTime);

    if ($eventStart > $currentTimestamp) {
      return $event;
    }
  }

  return null; // Return null if no upcoming event found
}

$currentSchedule = getCurrentDaySchedule($schedule);
$currentTime = date('H:i'); // Get the current time in HH:MM format

// Custom comparison function for sorting by startTime
function compareByStartTime($a, $b)
{
  return strcmp($a['startTime'], $b['startTime']);
}

function isCurrentEvent($event)
{
  $currentTimestamp = strtotime(date('H:i'));

  $eventStart = strtotime($event['startTime']);
  $eventEnd = strtotime($event['endTime']);

  if ($currentTimestamp >= $eventStart && $currentTimestamp <= $eventEnd) {
    return true;
  }
  return false;
}

function getTimePercentagePassed($event)
{
  $currentTimestamp = strtotime(date('H:i'));
  $startTimestamp = strtotime($event['startTime']);
  $endTimestamp = strtotime($event['endTime']);

  // Calculate total duration and elapsed time in seconds
  $totalDuration = $endTimestamp - $startTimestamp;
  $elapsedTime = $currentTimestamp - $startTimestamp;

  if ($elapsedTime < 0) {
    return 0;
  }

  if ($currentTimestamp > $endTimestamp) {
    return 0;
  }

  // Calculate the percentage passed
  $percentagePassed = ($elapsedTime / $totalDuration) * 100;

  return number_format($percentagePassed, 2);
}

?>

<style>
  #day-block .progress {
    height: 50px;
    position: relative;
    background-color: transparent;
  }

  #day-block .progress .progress-text {
    font-size: 16px;
    position: absolute;
    width: 100%;
    height: 100%;
    padding: .75rem 1.25rem;
  }

  #day-block .progress .progress-text i {
    font-size: 4px;
  }
</style>

<ul class="list-group" id="day-block">
  <?php
  if (count($currentSchedule) > 0) {
    foreach ($currentSchedule as $schedule) {
      if (isCurrentEvent($schedule)) { ?>
        <li class="list-group-item p-0">
          <div class="progress rounded-0">
            <div class="progress-bar bg-gray-200" role="progressbar" style="width: <?= getTimePercentagePassed($schedule) ?>%;" aria-valuenow="<?= getTimePercentagePassed($schedule) ?>" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="absolute progress-text text-gray-900 d-flex align-items-center"><?= $schedule['content'] ?> <i class="fas fa-circle mx-2"></i> <?= getTimeLeft($schedule['endTime']) ?></div>
          </div>
        </li>
      <?php } else { ?>
        <li class="list-group-item"><span class="text-gray-900 mr-2"><?= $schedule['startTime'] ?> - <?= $schedule['endTime'] ?></span> <?= $schedule['content'] ?></li>
      <?php }
      ?>
  <?php }
  } ?>
</ul>