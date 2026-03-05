<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title) ?> — Enova Admission App Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
<div class="d-flex min-vh-100">
  <nav class="admin-sidebar d-flex flex-column p-3">
    <div class="text-center mb-4 py-2">
      <i class="bi bi-mortarboard-fill fs-2 text-white"></i>
      <div class="text-white fw-bold mt-1">Enova Admission App Admin</div>
    </div>
    <ul class="nav flex-column gap-1">
  <li>
    <a href="<?= base_url('admin/dashboard') ?>"
       class="nav-link text-white-50 admin-nav-link <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
  </li>
  <li>
    <a href="<?= base_url('admin/applications') ?>"
       class="nav-link text-white-50 admin-nav-link <?= str_starts_with(uri_string(), 'admin/applications') ? 'active' : '' ?>">
      <i class="bi bi-clipboard-check me-2"></i>Applications
    </a>
  </li>
  <li>
    <a href="<?= base_url('admin/students') ?>"
       class="nav-link text-white-50 admin-nav-link <?= uri_string() === 'admin/students' ? 'active' : '' ?>">
      <i class="bi bi-people me-2"></i>Students
    </a>
  </li>
</ul>
    <div class="mt-auto"><a href="<?= base_url('admin/logout') ?>" class="nav-link text-white-50"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></div>
  </nav>

  <div class="flex-grow-1 p-4 overflow-auto">

    <?php if ($msg = session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show"><?= esc($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif ?>
    <?php if ($msg = session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show"><?= esc($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif ?>


<?= $this->renderSection('content') ?>


 </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$('.btn-update').on('click', function () {
  const id      = $(this).data('id');
  const status  = $(this).data('status');
  const remarks = $(this).data('remarks');
  const name    = $(this).data('name');

  $('#updateForm').attr('action', '<?= base_url('admin/applications/update/') ?>' + id);
  $('#updateForm select[name=status]').val(status);
  $('#updateForm textarea[name=remarks]').val(remarks);
  $('#modalStudentName').text(name);
  new bootstrap.Modal('#updateModal').show();
});

// Live search filter
$('#studentSearch').on('input', function () {
  const q = $(this).val().toLowerCase();
  $('#studentsTable tbody tr').each(function () {
    const text = $(this).text().toLowerCase();
    $(this).toggle(text.includes(q));
  });
});
</script>
</body>
</html>
