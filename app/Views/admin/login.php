<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Enova Admission App</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="auth-body d-flex align-items-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="text-center mb-4">
        <div class="auth-logo bg-dark"><i class="bi bi-shield-lock-fill"></i></div>
        <h2 class="fw-bold mt-2">Admin Portal</h2>
        <p class="text-muted">Enova Admission App Administration</p>
      </div>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2"><?= esc($error) ?></div>
          <?php endif ?>
          <form action="<?= base_url('admin/login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label class="form-label fw-semibold">Admin Email</label>
              <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-dark fw-semibold">Sign In as Admin</button>
            </div>
          </form>
        </div>
      </div>
      <p class="text-center mt-3"><a href="<?= base_url('/') ?>" class="text-muted small">Back to Student Portal</a></p>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
