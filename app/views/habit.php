<?php
ob_start(); ?>
<style>
    .habit-item {
        border-left: 4px solid transparent;
        transition: 0.2s;
    }

    .habit-item.active {
        border-left-color: #198754;
        background: #e9f8ef;
        color: var(--vz-list-group-color);
    }

    .habit-item.upcoming {
        border-left-color: #ffc107;
        background: #fff8e5;
        color: var(--vz-list-group-color);
    }

    .time-badge {
        width: 80px;
        text-align: center;
    }

    .timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #6c757d;
        margin: 0 10px;
    }

    .timeline-dot.active {
        background: #198754;
    }

    .timeline-dot.upcoming {
        background: #ffc107;
    }
</style>
<?php
$additionCss = ob_get_clean();

ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?> Page</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHabitModal">
        <i class="ri-add-line me-1"></i> Add New
    </button>
</div>

<!-- Habit List -->
<div id="habitList" class="list-group">
    <!-- Example habits -->
    <div class="list-group-item d-flex align-items-center habit-item active">
        <div class="time-badge fw-semibold text-success">07:00</div>
        <div class="timeline-dot active"></div>
        <div class="flex-grow-1">
            <div class="fw-bold">Morning Exercise</div>
            <small class="text-muted">Repeat: Daily</small>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-secondary me-1 editHabit"><i class="ri-edit-2-line"></i></button>
            <button class="btn btn-sm btn-outline-danger deleteHabit"><i class="ri-delete-bin-6-line"></i></button>
        </div>
    </div>

    <div class="list-group-item d-flex align-items-center habit-item upcoming">
        <div class="time-badge fw-semibold text-warning">09:00</div>
        <div class="timeline-dot upcoming"></div>
        <div class="flex-grow-1">
            <div class="fw-bold">Breakfast</div>
            <small class="text-muted">Repeat: Daily</small>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-secondary me-1 editHabit"><i class="ri-edit-2-line"></i></button>
            <button class="btn btn-sm btn-outline-danger deleteHabit"><i class="ri-delete-bin-6-line"></i></button>
        </div>
    </div>

    <div class="list-group-item d-flex align-items-center habit-item">
        <div class="time-badge fw-semibold text-muted">20:30</div>
        <div class="timeline-dot"></div>
        <div class="flex-grow-1">
            <div class="fw-bold">Read a book</div>
            <small class="text-muted">Repeat: Monday, Wednesday, Friday</small>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-secondary me-1 editHabit"><i class="ri-edit-2-line"></i></button>
            <button class="btn btn-sm btn-outline-danger deleteHabit"><i class="ri-delete-bin-6-line"></i></button>
        </div>
    </div>

    <div class="list-group-item d-flex align-items-center habit-item">
        <div class="time-badge fw-semibold text-muted">22:00</div>
        <div class="timeline-dot"></div>
        <div class="flex-grow-1">
            <div class="fw-bold">Sleep</div>
            <small class="text-muted">Repeat: Daily</small>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-secondary me-1 editHabit"><i class="ri-edit-2-line"></i></button>
            <button class="btn btn-sm btn-outline-danger deleteHabit"><i class="ri-delete-bin-6-line"></i></button>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addHabitModal" tabindex="-1" aria-labelledby="habitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="habitForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="habitModalLabel">Add New Habit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="habitTitle" class="form-control" placeholder="e.g. Drink Water" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" id="habitTime" class="form-control" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Repeat</label>
                        <select id="habitRepeat" class="form-select">
                            <option value="daily">Daily</option>
                            <option value="specific">Specific Days</option>
                        </select>
                    </div>

                    <div id="specificDays" class="mb-3 d-none">
                        <label class="form-label">Select Days</label>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Mon" id="dayMon">
                                <label class="form-check-label" for="dayMon">Mon</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Tue" id="dayTue">
                                <label class="form-check-label" for="dayTue">Tue</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Wed" id="dayWed">
                                <label class="form-check-label" for="dayWed">Wed</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Thu" id="dayThu">
                                <label class="form-check-label" for="dayThu">Thu</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Fri" id="dayFri">
                                <label class="form-check-label" for="dayFri">Fri</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Sat" id="daySat">
                                <label class="form-check-label" for="daySat">Sat</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Sun" id="daySun">
                                <label class="form-check-label" for="daySun">Sun</label>
                            </div>
                        </div>
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
ob_start(); ?>
<script>
    $(function() {
        // Toggle day selection visibility
        $('#habitRepeat').on('change', function() {
            if ($(this).val() === 'specific') {
                $('#specificDays').removeClass('d-none');
            } else {
                $('#specificDays').addClass('d-none');
            }
        });

        // Highlight “active” and “upcoming”
        function updateHighlights() {
            const now = new Date();
            const currentTime = now.getHours() * 60 + now.getMinutes();

            $('.habit-item').each(function() {
                const timeText = $(this).find('.time-badge').text();
                const [hour, minute] = timeText.split(':').map(Number);
                const habitTime = hour * 60 + minute;

                $(this).removeClass('active upcoming');
                $(this).find('.timeline-dot').removeClass('active upcoming');

                if (Math.abs(habitTime - currentTime) <= 30) {
                    $(this).addClass('active');
                    $(this).find('.timeline-dot').addClass('active');
                } else if (habitTime - currentTime > 0 && habitTime - currentTime <= 60) {
                    $(this).addClass('upcoming');
                    $(this).find('.timeline-dot').addClass('upcoming');
                }
            });
        }

        updateHighlights();
    })
</script>
<?php
$additionJs = ob_get_clean();

$pageContent = ob_get_clean();
include_once LAYOUTS_DIR . 'dashboard.php';
