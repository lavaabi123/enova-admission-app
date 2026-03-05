<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Admission Portal') ?> — Enova Admission App</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('css/app.css?v=1.0') ?>">
  <?= $this->renderSection('head') ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= base_url('student/dashboard') ?>">
      <i class="bi bi-mortarboard-fill me-2"></i>Enova Admission App
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if (session()->get('student_logged_in')): ?>
          <li class="nav-item">
            <a class="nav-link <?= uri_string() === 'student/dashboard' ? 'active' : '' ?>" href="<?= base_url('student/dashboard') ?>"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i><?= esc(session()->get('user_name')) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= base_url('student/biodata') ?>"><i class="bi bi-person-lines-fill me-2"></i>My Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Flash Messages -->
<div class="container mt-3">
  <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
    <?php if ($msg = session()->getFlashdata($type)): ?>
      <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : 'info-circle') ?> me-2"></i>
        <?= esc($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif ?>
  <?php endforeach ?>
</div>

<!-- Main Content -->
<main class="container py-4">
  <?= $this->renderSection('content') ?>
</main>

<!-- Footer -->
<footer class="bg-light border-top py-3 mt-auto">
  <div class="container text-center text-muted small">
    &copy; <?= date('Y') ?> Enova Admission App Portal. All rights reserved.
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('js/app.js?v=1') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
