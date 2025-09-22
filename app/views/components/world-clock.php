<?php
$timeZones = array(
  array("Việt Nam", "Asia/Ho_Chi_Minh"),
  array("Singapore", "Asia/Singapore")
);
$title = 'world time';
?>

<style>
  #world-clock .time {
    font-weight: bold;
    font-size: 24px;
    letter-spacing: 0.1rem;
    text-transform: uppercase;
  }
</style>
<div class="card mb-4" id="world-clock">
  <div class="card-body p-3">
    <?php
    foreach ($timeZones as $zone) {
      $dateTime = new DateTime('now', new DateTimeZone($zone[1]));

      echo '<div class="d-flex align-items-center justify-content-between mb-2">
          <div class="zone text-gray-700">' . $zone[0] . '</div>
          <div class="time text-gray-900">' . $dateTime->format('g:i a') . '</div>
        </div>';
    }
    ?>
  </div>
</div>