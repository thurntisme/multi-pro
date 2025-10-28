<?php
ob_start();
?>
<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

<style>
    #calendar {
        background: #fff;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 600;
        color: #212529;
    }

    .fc-button {
        border-radius: 6px !important;
    }

    .fc-event {
        cursor: pointer;
        border-radius: 6px !important;
    }
</style>
<?php
$additionCss = ob_get_clean();
ob_start(); ?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?> Page</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="ri-add-line me-1"></i> Add New
    </button>
</div>

<div id="calendar"></div>

<!-- Modal Add/Edit Event -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="eventForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="eventTitle" class="form-control" placeholder="Event Title" required>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="eventStartDate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" id="eventEndDate" class="form-control" required>
                        </div>
                    </div>

                    <div class="row g-2 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" id="eventStartTime" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Time</label>
                            <input type="time" id="eventEndTime" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Color</label>
                        <input type="color" id="eventColor" class="form-control form-control-color" value="#0d6efd">
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const addEventModal = new bootstrap.Modal(document.getElementById('addEventModal'));
        const eventForm = document.getElementById('eventForm');

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            editable: true,
            height: '80vh',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [{
                    title: 'Morning Exercise',
                    start: '2025-10-20T07:00:00',
                    end: '2025-10-24T09:00:00',
                    color: '#198754'
                },
                {
                    title: 'Team Meeting',
                    start: '2025-10-24T10:00:00',
                    color: '#0d6efd'
                },
                {
                    title: 'Dinner with friends',
                    start: '2025-10-25T19:00:00',
                    color: '#dc3545'
                }
            ],
            dateClick: function(info) {
                document.getElementById('eventDate').value = info.dateStr;
                document.getElementById('eventTitle').value = '';
                document.getElementById('eventColor').value = '#0d6efd';
                eventModal.show();
            },
            eventClick: function(info) {
                alert(`📌 ${info.event.title}\n🗓 ${info.event.start.toLocaleString()}`);
            }
        });

        calendar.render();

        eventForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const title = document.getElementById('eventTitle').value;
            const date = document.getElementById('eventDate').value;
            const color = document.getElementById('eventColor').value;

            if (title && date) {
                calendar.addEvent({
                    title,
                    start: date,
                    color
                });
                eventModal.hide();
                eventForm.reset();
            }
        });
    });
</script>
<?php
$additionJs = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
