<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row mb-4">
  <div class="col">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Bio Data</li>
      </ol>
    </nav>
    <h3 class="fw-bold"><?= $existing ? 'Update Your Profile' : 'Complete Your Profile' ?></h3>
    <p class="text-muted">Certificates must be PDF, JPG, or PNG (max 2MB each).</p>
  </div>
</div>

<?php if (isset($hasApplied) && $hasApplied): ?>
  <div class="alert alert-info py-2 mb-3">
    <i class="bi bi-info-circle me-2"></i>
    You have already submitted an application. You can still update your personal details,
    but certificate uploads are optional unless you want to replace them.
  </div>
<?php endif ?>

<?php if ($errors = session()->getFlashdata('errors')): ?>
  <div class="alert alert-danger">
    <ul class="mb-0 ps-3">
      <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
    </ul>
  </div>
<?php endif ?>
<?php if ($error = session()->getFlashdata('error')): ?>
  <div class="alert alert-danger"><?= esc($error) ?></div>
<?php endif ?>

<form action="<?= base_url('student/biodata') ?>" method="post" enctype="multipart/form-data" id="bioForm" novalidate>
  <?= csrf_field() ?>

  <!-- Personal Information -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
      <i class="bi bi-person-fill me-2"></i>Personal Information
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
          <input type="date" name="dob" class="form-control" required
                 value="<?= esc(old('dob', $existing['dob'] ?? '')) ?>"
                 max="<?= date('Y-m-d', strtotime('-16 years')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
          <select name="gender" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= old('gender', $existing['gender'] ?? '') === $val ? 'selected' : '' ?>>
                <?= $lbl ?>
              </option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Your Photo <?= $existing ? '' : '<span class="text-danger">*</span>' ?></label>
          <input type="file" name="photo" class="form-control" id="photoInput" <?= $existing ? '' : 'required' ?>
                 accept=".jpg,.jpeg,.png">
          <div class="form-text">JPG/PNG, min 100×100px, max 1MB</div>
          <?php if (! empty($existing['photo'])): ?>
            <div class="mt-1 text-success small"><i class="bi bi-check-circle me-1"></i>Photo uploaded. Upload new to replace.</div>
          <?php endif ?>
        </div>
        <div class="col-md-12">
          <label class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
          <textarea name="address" class="form-control" rows="2" required minlength="10"><?= esc(old('address', $existing['address'] ?? '')) ?></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
          <input type="text" name="city" class="form-control" required
                 value="<?= esc(old('city', $existing['city'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
          <input type="text" name="state" class="form-control" required
                 value="<?= esc(old('state', $existing['state'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Pincode <span class="text-danger">*</span></label>
          <input type="text" name="pincode" class="form-control" required
                 maxlength="6" pattern="[0-9]{6}" placeholder="6-digit pincode"
                 value="<?= esc(old('pincode', $existing['pincode'] ?? '')) ?>">
        </div>
      </div>
    </div>
  </div>

  <!-- 10th Details -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-secondary text-white fw-semibold">
      <i class="bi bi-file-earmark-text me-2"></i>10th Standard Details
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Board <span class="text-danger">*</span></label>
          <input type="text" name="tenth_board" class="form-control" required placeholder="e.g. CBSE / State Board"
                 value="<?= esc(old('tenth_board', $existing['tenth_board'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Year of Passing <span class="text-danger">*</span></label>
          <input type="number" name="tenth_year" class="form-control" required min="1990" max="<?= date('Y') ?>"
                 value="<?= esc(old('tenth_year', $existing['tenth_year'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Percentage <span class="text-danger">*</span></label>
          <div class="input-group">
            <input type="number" name="tenth_percentage" class="form-control" required min="0" max="100" step="0.01"
                   value="<?= esc(old('tenth_percentage', $existing['tenth_percentage'] ?? '')) ?>">
            <span class="input-group-text">%</span>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">10th Certificate <?= $existing ? '' : '<span class="text-danger">*</span>' ?></label>
          <input type="file" name="cert_10th" class="form-control" <?= $existing ? '' : 'required' ?>
                 accept=".pdf,.jpg,.jpeg,.png">
          <div class="form-text">PDF, JPG, PNG — max 2MB</div>
          <?php if (! empty($existing['cert_10th'])): ?>
            <div class="mt-1 text-success small"><i class="bi bi-check-circle me-1"></i>Already uploaded. Upload new to replace.</div>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>

  <!-- 12th Details -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header fw-semibold" style="background:#6f42c1;color:#fff">
      <i class="bi bi-file-earmark-text-fill me-2"></i>12th Standard Details
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-semibold">Stream <span class="text-danger">*</span></label>
          <select name="twelfth_stream" class="form-select" required>
            <option value="">-- Select --</option>
            <?php foreach (['Science', 'Commerce', 'Arts'] as $s): ?>
              <option value="<?= $s ?>" <?= old('twelfth_stream', $existing['twelfth_stream'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Board <span class="text-danger">*</span></label>
          <input type="text" name="twelfth_board" class="form-control" required placeholder="e.g. CBSE"
                 value="<?= esc(old('twelfth_board', $existing['twelfth_board'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Year of Passing <span class="text-danger">*</span></label>
          <input type="number" name="twelfth_year" class="form-control" required min="1990" max="<?= date('Y') ?>"
                 value="<?= esc(old('twelfth_year', $existing['twelfth_year'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Percentage <span class="text-danger">*</span></label>
          <div class="input-group">
            <input type="number" name="twelfth_percentage" id="pctInput" class="form-control" required min="0" max="100" step="0.01"
                   value="<?= esc(old('twelfth_percentage', $existing['twelfth_percentage'] ?? '')) ?>">
            <span class="input-group-text">%</span>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">12th Certificate <?= $existing ? '' : '<span class="text-danger">*</span>' ?></label>
          <input type="file" name="cert_12th" class="form-control" <?= $existing ? '' : 'required' ?>
                 accept=".pdf,.jpg,.jpeg,.png">
          <div class="form-text">PDF, JPG, PNG — max 2MB</div>
          <?php if (! empty($existing['cert_12th'])): ?>
            <div class="mt-1 text-success small"><i class="bi bi-check-circle me-1"></i>Already uploaded.</div>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary btn-lg px-5 fw-semibold">
      <i class="bi bi-save me-2"></i>Save & Continue
    </button>
    <a href="<?= base_url('student/dashboard') ?>" class="btn btn-outline-secondary btn-lg">Cancel</a>
  </div>

</form>

<?= $this->endSection() ?>
