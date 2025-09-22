<?php
require_once 'controllers/EventController.php';

$pageTitle = "Calendar";

$eventController = new EventController();
$list = $eventController->listEvents('month');

ob_start();
?>

<div class="row">
    <div class="col-12">
        <?php
        include_once DIR . '/components/alert.php';
        ?>
        <div class="row">
            <div class="col-xl-3">
                <div class="card card-h-100">
                    <div class="card-body">
                        <a class="btn btn-primary w-100"
                            href="<?= App\Helpers\NetworkHelper::home_url("app/calendar/new") ?>"><i
                                class="mdi mdi-plus"></i> Create New Event</a>
                    </div>
                </div>
                <div>
                    <h5 class="mb-1">Upcoming Events</h5>
                    <p class="text-muted">Don't miss scheduled events</p>
                    <div class="pe-2 me-n1 mb-3" data-simplebar style="height: 400px">
                        <div id="upcoming-event-list"></div>
                    </div>
                </div>
                <!--end card-->
            </div> <!-- end col-->

            <div class="col-xl-9">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
        <!--end row-->

        <div style='clear:both'></div>

        <!-- Add New Event MODAL -->
        <div class="modal fade" id="event-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-info-subtle">
                        <h5 class="modal-title" id="modal-title">Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form class="needs-validation" name="event-form" id="form-event" novalidate>
                            <div class="text-end">
                                <a data-url="<?= App\Helpers\NetworkHelper::home_url('app/calendar/edit?id=') ?>"
                                    href="#" id="btn-edit-event" class="btn btn-sm btn-soft-primary">Edit</a>
                            </div>
                            <div class="event-details">
                                <div class="d-flex mb-2">
                                    <div class="flex-grow-1 d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="ri-calendar-event-line text-muted fs-16"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="d-block fw-semibold mb-0" id="event-start-date-tag"></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-time-line text-muted fs-16"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="d-block fw-semibold mb-0"><span id="event-timepicker1-tag"></span> -
                                            <span id="event-timepicker2-tag"></span></h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-map-pin-line text-muted fs-16"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="d-block fw-semibold mb-0"><span id="event-location-tag"></span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-discuss-line text-muted fs-16"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="d-block text-muted mb-0" id="event-description-tag"
                                            style="word-break: break-all;"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row event-form">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select class="form-select d-none" name="category" id="event-category" required>
                                            <option value="bg-danger-subtle">Danger</option>
                                            <option value="bg-success-subtle">Success</option>
                                            <option value="bg-primary-subtle">Primary</option>
                                            <option value="bg-info-subtle">Info</option>
                                            <option value="bg-dark-subtle">Dark</option>
                                            <option value="bg-warning-subtle">Warning</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a valid event category</div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Event Name</label>
                                        <input class="form-control d-none" placeholder="Enter event name" type="text"
                                            name="title" id="event-title" required value="" />
                                        <div class="invalid-feedback">Please provide a valid event name</div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label>Event Date</label>
                                        <div class="input-group d-none">
                                            <input type="text" id="event-start-date"
                                                class="form-control flatpickr flatpickr-input" placeholder="Select date"
                                                readonly required>
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
                                                <div class="input-group d-none">
                                                    <input id="timepicker1" type="text"
                                                        class="form-control flatpickr flatpickr-input"
                                                        placeholder="Select start time" readonly>
                                                    <span class="input-group-text"><i class="ri-time-line"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label">End Time</label>
                                                <div class="input-group d-none">
                                                    <input id="timepicker2" type="text"
                                                        class="form-control flatpickr flatpickr-input"
                                                        placeholder="Select end time" readonly>
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
                                            <input type="text" class="form-control d-none" name="event-location"
                                                id="event-location" placeholder="Event location">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <input type="hidden" id="eventid" name="eventid" value="" />
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control d-none" id="event-description"
                                            placeholder="Enter a description" rows="3" spellcheck="false"></textarea>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                            <div class="hstack gap-2 justify-content-end">
                                <button type="submit" class="btn btn-success" id="btn-save-event">Add Event</button>
                            </div>
                        </form>
                    </div>
                </div> <!-- end modal-content-->
            </div> <!-- end modal dialog-->
        </div> <!-- end modal-->
        <!-- end modal-->
    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<script type="text/javascript"
    src="<?= App\Helpers\NetworkHelper::home_url('assets/libs/fullcalendar/index.global.min.js') ?>"></script>
<script type="text/javascript">
    const eventData = '<?= json_encode($list) ?>';
    const events = JSON.parse(eventData);
    const calendar_edit_page = '<?= App\Helpers\NetworkHelper::home_url("app/calendar/edit?id=") ?>';
</script>
<script type="text/javascript" src="<?= App\Helpers\NetworkHelper::home_url('assets/js/pages/calendar.js') ?>"></script>
<?php
$additionJs = ob_get_clean();
