<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><?= $title ?> Page</h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkoutModal">
    <i class="ri-add-line me-1"></i> Add New
  </button>
</div>

<!-- Week Tabs -->
<ul class="nav nav-pills mb-4" id="workoutTabs" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#monday" type="button">Mon</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tuesday" type="button">Tue</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#wednesday" type="button">Wed</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#thursday" type="button">Thu</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#friday" type="button">Fri</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#saturday" type="button">Sat</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#sunday" type="button">Sun</button></li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="workoutTabsContent">

  <!-- Monday -->
  <div class="tab-pane fade show active" id="monday">

    <!-- Workout List -->
    <div class="vstack gap-2">
      <!-- Workout Item -->
      <div class="card border-0 shadow-sm overflow-hidden">
        <div class="row g-0">
          <!-- Thumbnail -->
          <div class="col-md-4 position-relative">
            <img src="https://images.pexels.com/photos/4761791/pexels-photo-4761791.jpeg?auto=compress&cs=tinysrgb&w=800"
              class="img-fluid h-100 object-fit-cover"
              alt="Workout Thumbnail"
              data-bs-toggle="modal"
              data-bs-target="#lightboxModal">
            <span class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 small rounded-end">
              Chest Day
            </span>
          </div>

          <!-- Details -->
          <div class="col-md-8 p-3 d-flex flex-column justify-content-between">
            <div>
              <h5 class="fw-bold mb-1">Bench Press</h5>
              <p class="text-muted small mb-2"><i class="ri-time-line me-1"></i>4 Sets • 12 Reps • 60kg</p>
              <p class="text-secondary small mb-3">Focus on full range of motion. Keep form strict and stable.</p>

              <!-- Sets Progress -->
              <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold small text-muted">Sets:</span>
                <button class="btn btn-success btn-sm rounded-circle"><i class="ri-check-line"></i></button>
                <button class="btn btn-outline-success btn-sm rounded-circle"><i class="ri-check-line"></i></button>
                <button class="btn btn-outline-success btn-sm rounded-circle"><i class="ri-check-line"></i></button>
                <button class="btn btn-outline-success btn-sm rounded-circle"><i class="ri-check-line"></i></button>
              </div>
            </div>

            <!-- Action buttons -->
            <div class="d-flex justify-content-between align-items-center mt-3">
              <div class="text-muted small"><i class="ri-fire-line me-1 text-danger"></i>Est. 80 kcal</div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary"><i class="ri-edit-line"></i></button>
                <button class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Another Workout -->
      <div class="card border-0 shadow-sm overflow-hidden">
        <div class="row g-0">
          <div class="col-md-4 position-relative">
            <img src="https://images.pexels.com/photos/414029/pexels-photo-414029.jpeg?auto=compress&cs=tinysrgb&w=800"
              class="img-fluid h-100 object-fit-cover"
              alt="Workout Thumbnail"
              data-bs-toggle="modal"
              data-bs-target="#lightboxModal">
            <span class="position-absolute top-0 start-0 bg-success text-white px-3 py-1 small rounded-end">
              Cardio
            </span>
          </div>
          <div class="col-md-8 p-3 d-flex flex-column justify-content-between">
            <div>
              <h5 class="fw-bold mb-1">Treadmill Run</h5>
              <p class="text-muted small mb-2"><i class="ri-time-line me-1"></i>30 min • 8 km/h</p>
              <p class="text-secondary small mb-3">Maintain steady pace and controlled breathing throughout the session.</p>

              <!-- Sets Progress -->
              <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold small text-muted">Sets:</span>
                <button class="btn btn-outline-success btn-sm rounded-circle"><i class="ri-check-line"></i></button>
                <button class="btn btn-outline-success btn-sm rounded-circle"><i class="ri-check-line"></i></button>
                <button class="btn btn-outline-secondary btn-sm rounded-circle"><i class="ri-checkbox-blank-line"></i></button>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
              <div class="text-muted small"><i class="ri-fire-line me-1 text-danger"></i>Est. 180 kcal</div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary"><i class="ri-edit-line"></i></button>
                <button class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Tuesday -->
  <div class="tab-pane fade" id="tuesday">
    <div class="alert alert-light border text-muted">No workouts planned yet. Click <b>Add New</b> to create one 💪</div>
  </div>

  <!-- Other days -->
  <div class="tab-pane fade" id="wednesday">
    <div class="alert alert-light border text-muted">No workouts planned yet.</div>
  </div>
  <div class="tab-pane fade" id="thursday">
    <div class="alert alert-light border text-muted">No workouts planned yet.</div>
  </div>
  <div class="tab-pane fade" id="friday">
    <div class="alert alert-light border text-muted">No workouts planned yet.</div>
  </div>
  <div class="tab-pane fade" id="saturday">
    <div class="alert alert-light border text-muted">No workouts planned yet.</div>
  </div>
  <div class="tab-pane fade" id="sunday">
    <div class="alert alert-light border text-muted">No workouts planned yet.</div>
  </div>

</div>

<!-- Modal: Add Workout -->
<div class="modal fade" id="addWorkoutModal" tabindex="-1" aria-labelledby="addWorkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="ri-add-line me-1 text-primary"></i>Add New Workout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label fw-semibold">Workout Title</label>
            <input type="text" class="form-control" placeholder="e.g., Morning Run, Chest Day">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea class="form-control" rows="3" placeholder="Briefly describe your workout"></textarea>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Day</label>
              <select class="form-select">
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
                <option>Sunday</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Time</label>
              <input type="time" class="form-control">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Upload Thumbnails</label>
            <input type="file" class="form-control" multiple>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Duration (min)</label>
              <input type="number" class="form-control" placeholder="e.g., 45">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Category</label>
              <select class="form-select">
                <option>Cardio</option>
                <option>Strength</option>
                <option>Flexibility</option>
                <option>Core</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Sets</label>
              <input type="number" class="form-control" placeholder="e.g., 4">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Reps</label>
              <input type="number" class="form-control" placeholder="e.g., 12">
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-success">
              <i class="ri-save-3-line me-1"></i>Save Workout
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Lightbox -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 bg-dark text-center">
      <img src="https://images.pexels.com/photos/4761791/pexels-photo-4761791.jpeg?auto=compress&cs=tinysrgb&w=1200"
        class="img-fluid rounded" alt="Workout Lightbox">
      <div class="p-3 text-white small">Swipe or click arrows to view more thumbnails</div>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
