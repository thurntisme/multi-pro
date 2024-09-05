<?php
function activeClassName($url)
{
  $currentUrl = isset($_GET['url']) ? $_GET['url'] : '';
  $urlParts = explode('/', trim($currentUrl, '/'));
  $parentPath = count($urlParts) > 0 ? $urlParts[0] : '';
  return $parentPath === $url ? 'active' : '';
}
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= home_url('') ?>">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3"><?= $commonController->getSiteName() ?></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item <?= activeClassName("") ?>">
    <a class="nav-link" href="<?= home_url('') ?>">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    General
  </div>

  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item <?= activeClassName("projects") ?>">
    <a class="nav-link" href="<?= home_url('projects') ?>">
      <i class="fas fa-project-diagram"></i>
      <span>Projects</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <div class="sidebar-heading">
    Account
  </div>

  <li class="nav-item <?= activeClassName("upgrade") ?>">
    <a class="nav-link" href="<?= home_url('upgrade') ?>">
      <i class="fas fa-arrow-circle-up"></i>
      <span>Upgrade</span></a>
  </li>

  <li class="nav-item <?= activeClassName("settings") ?>">
    <a class="nav-link" href="<?= home_url('settings') ?>">
      <i class="fas fa-cogs"></i>
      <span>Settings</span></a>
  </li>

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline mt-auto">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->