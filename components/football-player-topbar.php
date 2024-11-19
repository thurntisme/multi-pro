<?php
$slug = getLastSegmentFromUrl();
?>

<div class="d-flex align-items-center flex-wrap gap-2">
  <div class="flex-grow-1">
    <a class="btn btn-<?= $slug !== 'football-manager' ? 'soft-' : '' ?>info add-btn" href="<?= home_url("football-manager") ?>"><i
        class="ri-add-fill me-1 align-bottom"></i> Home
    </a>
    <a class="btn btn-<?= $slug !== 'my-club' ? 'soft-' : '' ?>info add-btn" href="<?= home_url("football-manager/my-club") ?>"><i
        class="ri-add-fill me-1 align-bottom"></i> My Club
    </a>
    <a class="btn btn-<?= $slug !== 'my-players' ? 'soft-' : '' ?>info add-btn" href="<?= home_url("football-manager/my-players") ?>"><i
        class="ri-add-fill me-1 align-bottom"></i> My Players
    </a>
  </div>
  <div class="flex-shrink-0">
    <div class="hstack text-nowrap gap-2">
      <a class="btn btn-<?= $slug !== 'transfer' ? 'soft-' : '' ?>info add-btn"
        href="<?= home_url("football-manager/transfer") ?>"><i
          class="ri-add-fill me-1 align-bottom"></i> Transfer
      </a>
      <a class="btn btn-<?= $slug !== 'store' ? 'soft-' : '' ?>info add-btn"
        href="<?= home_url("football-manager/store") ?>"><i
          class="ri-add-fill me-1 align-bottom"></i> Store
      </a>
      <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown"
        aria-expanded="false" class="btn btn-soft-info"><i class="ri-more-2-fill"></i>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
        <li><a class="dropdown-item" href="#">All</a></li>
        <li><a class="dropdown-item" href="#">Last Week</a></li>
        <li><a class="dropdown-item" href="#">Last Month</a></li>
        <li><a class="dropdown-item" href="#">Last Year</a></li>
      </ul>
    </div>
  </div>
</div>