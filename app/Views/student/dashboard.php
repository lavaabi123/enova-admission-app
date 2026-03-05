<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4 align-items-center">
  <div class="col">
    <h3 class="fw-bold mb-0">Welcome, <?= esc(session()->get('user_name')) ?></h3>
  </div>
  <?php if ($application): ?>
    <div class="col-auto">
      <span class="text-muted small">
        <i class="bi bi-arrow-repeat me-1"></i>Auto-refreshing every 30s &nbsp;
        <span id="dashCountdown" class="fw-semibold text-primary"></span>
      </span>
    </div>
  <?php endif ?>
</div>

<!-- Progress Steps -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body py-3">
    <div class="row text-center g-0">
      <?php
        $step1 = true;
        $step2 = ! empty($bioData);
        $step3 = ! empty($application);
        $step4 = $step3 && in_array($application['status'] ?? '', ['approved','rejected']);
      ?>
      <?php foreach ([
        ['Register', 'person-check', $step1],
        ['Bio Data',  'file-person',  $step2],
        ['Applied',   'send',         $step3],
        ['Decision',  'award',        $step4],
      ] as $i => [$label, $icon, $done]): ?>
        <div class="col">
          <div class="step-circle mx-auto <?= $done ? 'done' : '' ?>">
            <i class="bi bi-<?= $icon ?>"></i>
          </div>
          <small class="d-block mt-1 <?= $done ? 'text-success fw-semibold' : 'text-muted' ?>"><?= $label ?></small>
        </div>
        <?php if ($i < 3): ?>
          <div class="col-auto align-self-start pt-2">
            <div class="step-line <?= $done ? 'done' : '' ?>"></div>
          </div>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="row g-4">

  <!-- LEFT: Profile Card -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center py-4">
        <div class="avatar-circle mx-auto mb-3">
          <?php if (! empty($bioData['photo'])): ?>
            <img src="<?= base_url('uploads/' . $bioData['photo']) ?>" alt="Photo"
                 class="rounded-circle" width="80" height="80" style="object-fit:cover">
          <?php else: ?>
            <i class="bi bi-person-fill fs-2 text-primary"></i>
          <?php endif ?>
        </div>
        <h5 class="fw-bold mb-0"><?= esc(session()->get('user_name')) ?></h5>
        <p class="text-muted small"><?= esc(session()->get('user_email')) ?></p>
        <hr>
        <?php if ($bioData): ?>
          <div class="text-start small">
            <p class="mb-1"><i class="bi bi-geo-alt me-2 text-muted"></i><?= esc($bioData['city']) ?>, <?= esc($bioData['state']) ?></p>
            <p class="mb-1"><i class="bi bi-book me-2 text-muted"></i>12th: <?= esc($bioData['twelfth_stream']) ?></p>
            <p class="mb-0"><i class="bi bi-percent me-2 text-muted"></i><?= esc($bioData['twelfth_percentage']) ?>%</p>
          </div>
          <a href="<?= base_url('student/biodata') ?>" class="btn btn-sm btn-outline-primary mt-3 w-100">
            <i class="bi bi-pencil me-1"></i>Edit Profile
          </a>
        <?php else: ?>
          <p class="text-warning small mb-2"><i class="bi bi-exclamation-triangle me-1"></i>Profile incomplete</p>
          <a href="<?= base_url('student/biodata') ?>" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-arrow-right me-1"></i>Complete Profile
          </a>
        <?php endif ?>
      </div>
    </div>
  </div>

  <!-- RIGHT: Application / Status / CTA -->
  <div class="col-md-8">

    <?php if ($application): ?>

      <!-- Application Summary -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold border-0">
          <i class="bi bi-clipboard-check text-primary me-2"></i>Application Summary
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="info-block">
                <span class="label">Application No.</span>
                <span class="value fw-bold text-primary"><?= esc($application['application_no']) ?></span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-block">
                <span class="label">Status</span>
                <span class="value" id="dashStatusBadge"><?= status_badge($application['status']) ?></span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-block">
                <span class="label">Course Applied</span>
                <span class="value"><?= esc($application['course_name']) ?> (<?= esc($application['course_code']) ?>)</span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-block">
                <span class="label">Applied On</span>
                <span class="value"><?= format_date($application['applied_at']) ?></span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-block">
                <span class="label">Duration</span>
                <span class="value"><?= esc($application['duration_years']) ?> Years</span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-block">
                <span class="label">Last Updated</span>
                <span class="value" id="dashUpdatedAt"><?= format_date($application['updated_at']) ?></span>
              </div>
            </div>
          </div>

          <?php if ($application['remarks']): ?>
            <div class="alert alert-info mt-3 mb-0 py-2 small" id="dashRemarks">
              <i class="bi bi-chat-dots me-2"></i>
              <strong>Remarks:</strong> <?= esc($application['remarks']) ?>
            </div>
          <?php else: ?>
            <div id="dashRemarks"></div>
          <?php endif ?>
        </div>
      </div>

      <!-- Status Timeline -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold border-0">
          <i class="bi bi-activity text-primary me-2"></i>Application Progress
        </div>
        <div class="card-body pb-2">
          <?php
            $stages = [
              ['pending',      'Application Submitted', 'send',         'Your application was received successfully.'],
              ['under_review', 'Under Review',           'search',       'Our admissions team is reviewing your documents.'],
              ['approved',     'Decision Made',          'check-circle', $application['status'] === 'approved'
                  ? 'Congratulations! You have been admitted.'
                  : ($application['status'] === 'rejected'
                      ? 'Your application was not accepted this time.'
                      : 'Awaiting final decision.')],
            ];
            $order   = ['pending' => 0, 'under_review' => 1, 'approved' => 2, 'rejected' => 2];
            $current = $order[$application['status']] ?? 0;
          ?>
          <div class="status-timeline" id="dashTimeline">
            <?php foreach ($stages as $i => [$stageKey, $stageLabel, $icon, $desc]): ?>
              <div class="timeline-item <?= $i < $current ? 'done' : ($i === $current ? 'active' : '') ?>">
                <div class="timeline-icon">
                  <i class="bi bi-<?= $icon ?>"></i>
                </div>
                <div class="timeline-body">
                  <h6 class="mb-0 fw-semibold"><?= $stageLabel ?></h6>
                  <p class="text-muted small mb-0"><?= $desc ?></p>
                </div>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>

    <?php elseif ($bioData): ?>

      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="bi bi-send display-4 text-primary"></i>
          <h5 class="mt-3 fw-bold">Ready to Apply?</h5>
          <p class="text-muted">Your profile is complete. Browse eligible courses and submit your application.</p>
          <a href="<?= base_url('student/courses') ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Browse Courses
          </a>
        </div>
      </div>

    <?php else: ?>

      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="bi bi-person-lines-fill display-4 text-warning"></i>
          <h5 class="mt-3 fw-bold">Complete Your Profile First</h5>
          <p class="text-muted">Fill in your bio data and upload your certificates to proceed.</p>
          <a href="<?= base_url('student/biodata') ?>" class="btn btn-warning btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Fill Bio Data
          </a>
        </div>
      </div>

    <?php endif ?>

  </div>

</div>

<?= $this->endSection() ?>

<?php if ($application): ?>
<?= $this->section('scripts') ?>
<script>
(function () {
  let countdown = 30;

  const badges = {
    pending:      '<span class="badge bg-warning">Pending</span>',
    under_review: '<span class="badge bg-info">Under Review</span>',
    approved:     '<span class="badge bg-success">Approved</span>',
    rejected:     '<span class="badge bg-danger">Rejected</span>',
  };

  const timelineDesc = {
    approved: 'Congratulations! You have been admitted.',
    rejected: 'Your application was not accepted this time.',
    default:  'Awaiting final decision.',
  };

  const statusOrder = { pending: 0, under_review: 1, approved: 2, rejected: 2 };

  function updateTimeline(status) {
    const current = statusOrder[status] ?? 0;
    $('#dashTimeline .timeline-item').each(function (i) {
      $(this).removeClass('done active');
      if (i < current) $(this).addClass('done');
      else if (i === current) $(this).addClass('active');

      // Update decision text dynamically
      if (i === 2) {
        $(this).find('p').text(timelineDesc[status] || timelineDesc.default);
      }
    });
  }

  function pollStatus() {
    $.getJSON('<?= base_url("student/status/check") ?>', function (data) {
      if (!data.status) return;

      // Update badge
      if (badges[data.status]) {
        $('#dashStatusBadge').html(badges[data.status]);
      }

      // Update last updated
      if (data.updated_at) {
        $('#dashUpdatedAt').text(data.updated_at);
      }

      // Update remarks
      if (data.remarks) {
        $('#dashRemarks').html(
          '<div class="alert alert-info mt-3 mb-0 py-2 small">' +
          '<i class="bi bi-chat-dots me-2"></i><strong>Remarks:</strong> ' +
          $('<div>').text(data.remarks).html() + '</div>'
        );
      }

      // Update timeline
      updateTimeline(data.status);
    });
  }

  function tick() {
    $('#dashCountdown').text('Next check in ' + countdown + 's');
    countdown--;
    if (countdown < 0) {
      countdown = 30;
      pollStatus();
    }
  }

  tick();
  setInterval(tick, 1000);
})();
</script>
<?= $this->endSection() ?>
<?php endif ?>
