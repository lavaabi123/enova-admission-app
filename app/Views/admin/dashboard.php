<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

    <h3 class="fw-bold mb-4"><?= esc($title) ?></h3>

    <!-- Stats -->
    <div class="row g-3 mb-4">
      <?php
        $statCards = [
          ['Total Applications', $stats['total'],        'clipboard',          'primary'],
          ['Pending',            $stats['pending'],       'hourglass-split',    'warning'],
          ['Under Review',       $stats['under_review'],  'search',             'info'],
          ['Approved',           $stats['approved'],      'check-circle',       'success'],
          ['Rejected',           $stats['rejected'],      'x-circle',           'danger'],
          ['Students',           $stats['students'],      'people',             'secondary'],
        ];
      ?>
      <?php foreach ($statCards as [$label, $val, $icon, $color]): ?>
        <div class="col-md-2 col-sm-4 col-6">
          <div class="card border-0 shadow-sm text-center py-3">
            <div class="text-<?= $color ?> fs-3"><i class="bi bi-<?= $icon ?>"></i></div>
            <div class="fw-bold fs-4"><?= $val ?></div>
            <div class="text-muted small"><?= $label ?></div>
          </div>
        </div>
      <?php endforeach ?>
    </div>

    <!-- Recent Applications -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold border-0 d-flex justify-content-between">
        <span><i class="bi bi-clock-history text-primary me-2"></i>Recent Applications</span>
        <a href="<?= base_url('admin/applications') ?>" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>App No.</th><th>Student</th><th>Course</th><th>Status</th><th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent as $app): ?>
                <tr>
                  <td class="fw-semibold text-primary"><?= esc($app['application_no']) ?></td>
                  <td><?= esc($app['student_name']) ?><br><small class="text-muted"><?= esc($app['email']) ?></small></td>
                  <td><?= esc($app['course_name']) ?></td>
                  <td><?= status_badge($app['status']) ?></td>
                  <td class="small text-muted"><?= format_date($app['applied_at']) ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

<?= $this->endSection() ?>
