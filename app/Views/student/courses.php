<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row mb-4">
  <div class="col">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Available Courses</li>
      </ol>
    </nav>
    <h3 class="fw-bold">Available Courses for You</h3>
    <p class="text-muted">
      Based on your <strong><?= esc($bio['twelfth_stream']) ?></strong> stream
      and <strong><?= esc($bio['twelfth_percentage']) ?>%</strong> marks
      — here are the courses you're eligible for.
    </p>
  </div>
</div>

<?php if (empty($courses)): ?>
  <div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    No courses available matching your stream and percentage at this time.
  </div>
<?php else: ?>

<form action="<?= base_url('student/apply') ?>" method="post" id="applyForm">
  <?= csrf_field() ?>
  <input type="hidden" name="course_id" id="selectedCourseId">

  <div class="row g-4 mb-4">
    <?php foreach ($courses as $course): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm course-card h-100" data-id="<?= $course['id'] ?>">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-primary"><?= esc($course['code']) ?></span>
              <span class="badge bg-light text-dark border"><?= esc($course['stream']) ?></span>
            </div>
            <h5 class="card-title fw-bold"><?= esc($course['name']) ?></h5>
            <p class="card-text text-muted small"><?= esc($course['description']) ?></p>
            <hr class="my-2">
            <div class="row text-center g-0">
              <div class="col">
                <div class="small text-muted">Duration</div>
                <div class="fw-bold"><?= esc($course['duration_years']) ?> yrs</div>
              </div>
              <div class="col border-start">
                <div class="small text-muted">Min. %</div>
                <div class="fw-bold"><?= esc($course['min_percentage']) ?>%</div>
              </div>
              <div class="col border-start">
                <div class="small text-muted">Seats</div>
                <div class="fw-bold"><?= esc($course['seats']) ?></div>
              </div>
            </div>
          </div>
          <div class="card-footer bg-white border-0 pb-3">
            <button type="button" class="btn btn-outline-primary w-100 btn-select-course" data-id="<?= $course['id'] ?>" data-name="<?= esc($course['name']) ?>">
              <i class="bi bi-check2-circle me-1"></i>Select This Course
            </button>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold">Confirm Application</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>You are about to apply for:</p>
          <div class="alert alert-primary fw-semibold" id="selectedCourseName"></div>
          <p class="text-muted small">Once submitted, you cannot change your course selection. Make sure this is your final choice.</p>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary fw-semibold px-4">
            <i class="bi bi-send me-1"></i>Confirm & Apply
          </button>
        </div>
      </div>
    </div>
  </div>

</form>

<?php endif ?>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$(function () {
  $('.btn-select-course').on('click', function () {
    const id   = $(this).data('id');
    const name = $(this).data('name');
    $('#selectedCourseId').val(id);
    $('#selectedCourseName').text(name);
    new bootstrap.Modal('#confirmModal').show();
  });
});
</script>
<?= $this->endSection() ?>
