<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title) ?> — Enova Admission App</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
  <style>
    .field-error { font-size:.82rem; color:#b8000e; margin-top:4px; display:none; }
    .field-error.show { display:block; }
    .input-group.is-invalid .form-control,
    .input-group.is-invalid .input-group-text { border-color:#b8000e; background-color:#fff5f5; }
    .input-group.is-valid .form-control,
    .input-group.is-valid .input-group-text { border-color:#198754; }
    .input-group-text { transition: border-color .2s, background-color .2s; }
  </style>
</head>
<body class="auth-body d-flex align-items-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-sm-8 col-11">

      <div class="text-center mb-4">
        <div class="auth-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <h2 class="fw-bold mt-2"><?= esc(config('App')->appName ?? 'Enova Admission App') ?></h2>
        <p class="text-muted">Sign in to your account</p>
      </div>

      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">

          <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger py-2">
              <ul class="mb-0 ps-3">
                <?php foreach ($errors as $e): ?><li style="font-size:.88rem"><?= esc($e) ?></li><?php endforeach ?>
              </ul>
            </div>
          <?php endif ?>
          <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2"><i class="bi bi-x-circle me-2"></i><?= esc($error) ?></div>
          <?php endif ?>
          <?php if ($success = session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2"><i class="bi bi-check-circle me-2"></i><?= esc($success) ?></div>
          <?php endif ?>

          <form action="<?= base_url('login') ?>" method="post" novalidate id="loginForm">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label fw-semibold" for="email">Email Address <span class="text-danger">*</span></label>
              <div class="input-group" id="emailGroup">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control"
                       placeholder="" value="<?= esc(old('email')) ?>"
                       autocomplete="email" autofocus>
              </div>
              <div class="field-error" id="emailError"></div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold" for="loginPassword">Password <span class="text-danger">*</span></label>
              <div class="input-group" id="passwordGroup">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="loginPassword" class="form-control"
                       placeholder="" autocomplete="current-password">
                <button class="btn btn-outline-secondary toggle-pw" type="button"
                        data-target="loginPassword" tabindex="-1">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              <div class="field-error" id="passwordError"></div>
            </div>

            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary btn-lg fw-semibold" id="loginBtn">
                <span class="spinner-border spinner-border-sm d-none me-2" id="loginSpinner"></span>
                <span id="loginBtnText">Sign In</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <p class="text-center mt-3 text-muted">
        Don't have an account?
        <a href="<?= base_url('signup') ?>" class="text-decoration-none fw-semibold">Create one</a>
      </p>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('js/app.js') ?>"></script>
<script>
$(function () {

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function showError(grp, err, msg) {
    $('#'+grp).addClass('is-invalid').removeClass('is-valid');
    $('#'+err).text(msg).addClass('show');
  }
  function showValid(grp, err) {
    $('#'+grp).addClass('is-valid').removeClass('is-invalid');
    $('#'+err).text('').removeClass('show');
  }
  function clearState(grp, err) {
    $('#'+grp).removeClass('is-invalid is-valid');
    $('#'+err).text('').removeClass('show');
  }

  // Live — email
  $('#email').on('blur input', function () {
    const v = $(this).val().trim();
    if (!v)                   showError('emailGroup','emailError','Email address is required.');
    else if (!emailRegex.test(v)) showError('emailGroup','emailError','Enter a valid email address.');
    else                      showValid('emailGroup','emailError');
  }).on('focus', function(){ clearState('emailGroup','emailError'); });

  // Live — password
  $('#loginPassword').on('blur input', function () {
    const v = $(this).val();
    if (!v)          showError('passwordGroup','passwordError','Password is required.');
    else if (v.length < 8) showError('passwordGroup','passwordError','Password must be at least 8 characters.');
    else             showValid('passwordGroup','passwordError');
  }).on('focus', function(){ clearState('passwordGroup','passwordError'); });

  // Submit guard
  $('#loginForm').on('submit', function (e) {
    let ok = true;

    const email = $('#email').val().trim();
    if (!email)                   { showError('emailGroup','emailError','Email address is required.'); ok=false; }
    else if (!emailRegex.test(email)) { showError('emailGroup','emailError','Enter a valid email address.'); ok=false; }
    else                              { showValid('emailGroup','emailError'); }

    const pw = $('#loginPassword').val();
    if (!pw)        { showError('passwordGroup','passwordError','Password is required.'); ok=false; }
    else if (pw.length<8) { showError('passwordGroup','passwordError','Password must be at least 8 characters.'); ok=false; }
    else            { showValid('passwordGroup','passwordError'); }

    if (!ok) {
      e.preventDefault();
      // Reset button
      $('#loginSpinner').addClass('d-none');
      $('#loginBtnText').text('Sign In');
      $('#loginBtn').prop('disabled', false);
      $('html,body').animate({ scrollTop: $('.is-invalid:first').offset().top - 80 }, 200);
      return;
    }

    // Only show spinner when validation fully passes
    $('#loginSpinner').removeClass('d-none');
    $('#loginBtnText').text('Signing in…');
    $('#loginBtn').prop('disabled', true);
  });

});
</script>
</body>
</html>