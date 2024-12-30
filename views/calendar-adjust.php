<?php
global $priorities, $status;
$pageTitle = "Event";

require_once 'controllers/EventController.php';
$eventController = new EventController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $eventController->viewEvent($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record') {
                $eventController->deleteEvent();
            }
        } else {
            if ($modify_type === "new") {
                var_dump($_POST);
                die();
                $eventController->createEvent();
            }
            if ($modify_type === "edit") {
                $eventController->updateEvent();
            }
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "calendar", "post_id" => $postData['id'], 'modify_type' => $modify_type));
        ?>
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="calendar">
            <?php csrfInput() ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <?= generateFormControl("type", "type", $postData['type'] ?? array_keys($event_types)[0], "", "select", "Event Type", $event_types) ?>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Event Title</label>
                                    <input class="form-control" placeholder="Enter event title" type="text" name="title" id="event-title" required value="<?= $postData['title'] ?? '' ?>" />
                                    <?php if (!empty($post_id)) { ?>
                                        <input type="hidden" name="event_id" value="<?= $post_id ?>">
                                    <?php } ?>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label>Event Date</label>
                                    <div class="input-group">
                                        <input type="text" id="event-start-date" name="date_range" class="form-control flatpickr flatpickr-input" placeholder="Select date" readonly required>
                                        <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12" id="event-time">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Start Time</label>
                                            <div class="input-group">
                                                <input id="start_time" name="start_time" type="text" class="form-control flatpickr flatpickr-input" placeholder="Select start time" value="<?= $postData['start_time'] ?? '' ?>" readonly>
                                                <span class="input-group-text"><i class="ri-time-line"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">End Time</label>
                                            <div class="input-group">
                                                <input id="end_time" name="end_time" type="text" class="form-control flatpickr flatpickr-input" placeholder="Select end time" value="<?= $postData['end_time'] ?? '' ?>" readonly>
                                                <span class="input-group-text"><i class="ri-time-line"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="event-location">Location</label>
                                    <div>
                                        <input type="text" class="form-control" name="location" id="event-location" placeholder="Event location" value="<?= $postData['location'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" id="event-description" name="description" placeholder="Enter a description" rows="3" spellcheck="false"><?= $postData['description'] ?? '' ?></textarea>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <div class="text-center mb-4">
                            <button type="submit"
                                class="btn btn-success w-sm"><?= $modify_type === "new" ? "Create" : "Save" ?></button>
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include_once DIR . '/components/modal-delete.php';

$pageContent = ob_get_clean();

ob_start(); ?>
<script type="text/javascript" src="<?= home_url('assets/libs/fullcalendar/index.global.min.js') ?>"></script>
<script type="text/javascript">
    flatpickr($("#event-start-date"), {
        altInput: true,
        altFormat: "j F Y",
        dateFormat: "Y-m-d",
        mode: "range",
        minDate: "today",
    });
    flatpickr($("#start_time"), {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
    flatpickr($("#end_time"), {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
</script>
<?php
$additionJs = ob_get_clean();

include 'layout.php';
