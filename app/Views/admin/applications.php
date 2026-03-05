<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="fw-bold mb-0"><?= esc($title) ?></h3>
  <!-- Filter -->
  <div class="d-flex gap-2">
	<?php foreach (['', 'pending', 'under_review', 'approved', 'rejected'] as $s): ?>
	  <a href="<?= base_url('admin/applications') . ($s ? '?status=' . $s : '') ?>"
		 class="btn btn-sm <?= $filter === $s ? 'btn-primary' : 'btn-outline-secondary' ?>">
		<?= $s ? ucwords(str_replace('_', ' ', $s)) : 'All' ?>
	  </a>
	<?php endforeach ?>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
	<div class="table-responsive">
	  <table class="table table-hover mb-0 align-middle">
		<thead class="table-light">
		  <tr>
			<th>App No.</th>
			<th>Student</th>
			<th>Course</th>
			<th>Applied On</th>
			<th>Status</th>
			<th class="text-center">Actions</th>
		  </tr>
		</thead>
		<tbody>
		  <?php if (empty($applications)): ?>
			<tr><td colspan="6" class="text-center py-4 text-muted">No applications found.</td></tr>
		  <?php else: ?>
			<?php foreach ($applications as $app): ?>
			  <tr>
				<td class="fw-semibold text-primary small"><?= esc($app['application_no']) ?></td>
				<td>
				  <strong><?= esc($app['student_name']) ?></strong><br>
				  <small class="text-muted"><?= esc($app['email']) ?></small>
				</td>
				<td><?= esc($app['course_name']) ?></td>
				<td class="small text-muted"><?= format_date($app['applied_at']) ?></td>
				<td><?= status_badge($app['status']) ?></td>
				<td class="text-center">
				  <button class="btn btn-sm btn-outline-primary btn-update"
						  data-id="<?= $app['id'] ?>"
						  data-status="<?= esc($app['status']) ?>"
						  data-remarks="<?= esc($app['remarks']) ?>"
						  data-name="<?= esc($app['student_name']) ?>">
					<i class="bi bi-pencil-square"></i>
				  </button>
				</td>
			  </tr>
			<?php endforeach ?>
		  <?php endif ?>
		</tbody>
	  </table>
	</div>
  </div>
  <div class="card-footer bg-white border-0">
	<?= $pager->links() ?>
  </div>
</div>

 

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post" id="updateForm">
      <?= csrf_field() ?>
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold">Update Application Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small">Student: <strong id="modalStudentName"></strong></p>
          <div class="mb-3">
            <label class="form-label fw-semibold">New Status</label>
            <select name="status" class="form-select" required>
              <option value="pending">Pending</option>
              <option value="under_review">Under Review</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Remarks (optional)</label>
            <textarea name="remarks" class="form-control" rows="3" placeholder="Add any notes for the student..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary fw-semibold">Update Status</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>