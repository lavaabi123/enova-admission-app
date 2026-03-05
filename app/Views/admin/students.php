<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold mb-0"><?= esc($title) ?></h3>
        <p class="text-muted small mb-0">All registered students on the portal</p>
      </div>
    </div>

    <!-- Search bar -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body py-2">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
          <input type="text" id="studentSearch" class="form-control border-start-0 ps-0"
                 placeholder="Search by name or email…">
        </div>
      </div>
    </div>

    <!-- Students Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="studentsTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Registered On</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($students)): ?>
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">
                    <i class="bi bi-people display-6 d-block mb-2"></i>
                    No students registered yet.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($students as $i => $student): ?>
                  <tr>
                    <td class="text-muted small"><?= $i + 1 ?></td>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                             style="width:34px;height:34px;font-size:.8rem;flex-shrink:0">
                          <?= strtoupper(substr($student['name'], 0, 1)) ?>
                        </div>
                        <span class="fw-semibold"><?= esc($student['name']) ?></span>
                      </div>
                    </td>
                    <td class="small"><?= esc($student['email']) ?></td>
                    <td class="small"><?= esc($student['phone']) ?></td>
                    <td>
                      <?php if ($student['is_active']): ?>
                        <span class="badge bg-success">Active</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Inactive</span>
                      <?php endif ?>
                    </td>
                    <td class="small text-muted"><?= date('d M Y', strtotime($student['created_at'])) ?></td>
                  </tr>
                <?php endforeach ?>
              <?php endif ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php if (isset($pager)): ?>
        <div class="card-footer bg-white border-0">
          <?= $pager->links() ?>
        </div>
      <?php endif ?>
    </div>

 
<?= $this->endSection() ?>