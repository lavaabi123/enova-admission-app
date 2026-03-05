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
    .input-group.is-invalid .input-group-text,
    .form-control.is-invalid-field { border-color:#b8000e !important; background-color:#fff5f5; }
    .input-group.is-valid .form-control,
    .input-group.is-valid .input-group-text { border-color:#198754; }
    .pw-rules li { font-size:.78rem; transition: color .2s; }
    .pw-rules li.pass { color:#198754; }
    .pw-rules li.fail { color:#aaa; }
  </style>
</head>
<body class="auth-body d-flex align-items-center min-vh-100 py-5">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-sm-9 col-11">

      <div class="text-center mb-4">
        <div class="auth-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <h2 class="fw-bold mt-2"><?= esc(config('App')->appName ?? 'Enova Admission App') ?></h2>
        <p class="text-muted">Create your student account</p>
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

          <form action="<?= base_url('signup') ?>" method="post" novalidate id="signupForm">
            <?= csrf_field() ?>

            <!-- Full Name -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
              <div class="input-group" id="nameGroup">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="name" id="name" class="form-control"
                       placeholder="" value="<?= esc(old('name')) ?>"
                       minlength="3" maxlength="100" autocomplete="name">
              </div>
              <div class="field-error" id="nameError"></div>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
              <div class="input-group" id="emailGroup">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="regEmail" class="form-control"
                       placeholder="you@example.com" value="<?= esc(old('email')) ?>"
                       autocomplete="email">
              </div>
              <div class="field-error" id="emailError"></div>
            </div>

            <!-- Phone -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
              <div class="input-group" id="phoneGroup">
                <span class="input-group-text">+91</span>
                <input type="tel" name="phone" id="phone" class="form-control"
                       placeholder="10-digit mobile number"
                       value="<?= esc(old('phone')) ?>"
                       maxlength="10" inputmode="numeric" autocomplete="tel">
              </div>
              <div class="field-error" id="phoneError"></div>
            </div>

            <!-- Password -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <div class="input-group" id="pwGroup">
                  <span class="input-group-text"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" id="pw1" class="form-control"
                         autocomplete="new-password">
                  <button class="btn btn-outline-secondary toggle-pw" type="button"
                          data-target="pw1" tabindex="-1"><i class="bi bi-eye"></i></button>
                </div>
                <div class="field-error" id="pwError"></div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group" id="pw2Group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="confirm_password" id="pw2" class="form-control"
                         autocomplete="new-password">
                  <button class="btn btn-outline-secondary toggle-pw" type="button"
                          data-target="pw2" tabindex="-1"><i class="bi bi-eye"></i></button>
                </div>
                <div class="field-error" id="pw2Error"></div>
              </div>
            </div>

            <!-- Password rules checklist -->
            <ul class="pw-rules list-unstyled mb-3 ps-1">
              <li id="rule-len"  class="fail"><i class="bi bi-circle me-1"></i>At least 8 characters</li>
              <li id="rule-letter" class="fail"><i class="bi bi-circle me-1"></i>Contains a letter</li>
              <li id="rule-num"  class="fail"><i class="bi bi-circle me-1"></i>Contains a number</li>
              <li id="rule-sp"  class="fail"><i class="bi bi-circle me-1"></i>Contains a Special character</li>
            </ul>

            <!-- Strength bar -->
            <div class="mb-3">
              <div class="progress" style="height:5px">
                <div id="pwStrengthBar" class="progress-bar" style="width:0%"></div>
              </div>
              <small id="pwStrengthLabel" class="text-muted"></small>
            </div>

            <div class="d-grid mt-2">
              <button type="submit" class="btn btn-primary btn-lg fw-semibold" id="signupBtn">
                <span class="spinner-border spinner-border-sm d-none me-2" id="signupSpinner"></span>
                <span id="signupBtnText">Create Account</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <p class="text-center mt-3 text-muted">
        Already registered?
        <a href="<?= base_url('login') ?>" class="text-decoration-none fw-semibold">Sign In</a>
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
  const phoneRegex = /^[6-9][0-9]{9}$/;   // Indian mobile

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

  // ── Name ──
  $('#name').on('blur', function () {
    const v = $(this).val().trim();
    if (!v)           showError('nameGroup','nameError','Full name is required.');
    else if (v.length < 3) showError('nameGroup','nameError','Name must be at least 3 characters.');
    else              showValid('nameGroup','nameError');
  }).on('focus', function(){ clearState('nameGroup','nameError'); });

  // ── Email ──
  $('#regEmail').on('blur', function () {
    const v = $(this).val().trim();
    if (!v)                    showError('emailGroup','emailError','Email is required.');
    else if (!emailRegex.test(v)) showError('emailGroup','emailError','Enter a valid email address.');
    else                       showValid('emailGroup','emailError');
  }).on('focus', function(){ clearState('emailGroup','emailError'); });

  // ── Phone ──
  $('#phone').on('input', function () {
    // Strip non-digits
    $(this).val($(this).val().replace(/\D/g,''));
  }).on('blur', function () {
    const v = $(this).val();
    if (!v)                    showError('phoneGroup','phoneError','Phone number is required.');
    else if (!phoneRegex.test(v)) showError('phoneGroup','phoneError','Enter a valid 10-digit mobile number.');
    else                       showValid('phoneGroup','phoneError');
  }).on('focus', function(){ clearState('phoneGroup','phoneError'); });

  // ── Password strength & rules ──
  function checkRule(id, pass) {
    const el = $('#' + id);
    if (pass) {
      el.removeClass('fail').addClass('pass')
        .find('i').removeClass('bi-circle').addClass('bi-check-circle-fill');
    } else {
      el.removeClass('pass').addClass('fail')
        .find('i').removeClass('bi-check-circle-fill').addClass('bi-circle');
    }
  }

  $('#pw1').on('input', function () {
    const v = $(this).val();
    const hasLen    = v.length >= 8;
    const hasLetter = /[A-Za-z]/.test(v);
    const hasNum    = /[0-9]/.test(v);
    const hasSpec   = /[^A-Za-z0-9]/.test(v);

    checkRule('rule-len',    hasLen);
    checkRule('rule-letter', hasLetter);
    checkRule('rule-num',    hasNum);
    checkRule('rule-sp',    hasSpec);

    // Strength bar
    let score = [hasLen, hasLetter, hasNum, hasSpec].filter(Boolean).length;
    const pct   = score * 25;
    const colors = ['','#dc3545','#fd7e14','#ffc107','#198754'];
    const labels = ['','Weak','Fair','Good','Strong'];
    $('#pwStrengthBar').css({ width: pct+'%', backgroundColor: colors[score] });
    $('#pwStrengthLabel').text(score ? 'Strength: '+labels[score] : '');

    // Re-check confirm if already typed
    if ($('#pw2').val()) checkConfirm();
  }).on('blur', function () {
    const v = $(this).val();
    if (!v)           showError('pwGroup','pwError','Password is required.');
    else if (v.length<8) showError('pwGroup','pwError','Minimum 8 characters required.');
    else if (!/[A-Za-z]/.test(v)||!/[0-9]/.test(v))
                      showError('pwGroup','pwError','Must contain letters and numbers.');
    else              showValid('pwGroup','pwError');
  }).on('focus', function(){ clearState('pwGroup','pwError'); });

  // ── Confirm password ──
  function checkConfirm() {
    const v  = $('#pw2').val();
    const pw = $('#pw1').val();
    if (!v)         showError('pw2Group','pw2Error','Please confirm your password.');
    else if (v!==pw) showError('pw2Group','pw2Error','Passwords do not match.');
    else             showValid('pw2Group','pw2Error');
  }
  $('#pw2').on('blur input', checkConfirm)
           .on('focus', function(){ clearState('pw2Group','pw2Error'); });

  // ── Submit guard ──
  $('#signupForm').on('submit', function (e) {
    let ok = true;

    const name = $('#name').val().trim();
    if (!name || name.length<3) { showError('nameGroup','nameError', !name?'Full name is required.':'Name must be at least 3 characters.'); ok=false; }
    else showValid('nameGroup','nameError');

    const email = $('#regEmail').val().trim();
    if (!email)                    { showError('emailGroup','emailError','Email is required.'); ok=false; }
    else if (!emailRegex.test(email)) { showError('emailGroup','emailError','Enter a valid email address.'); ok=false; }
    else showValid('emailGroup','emailError');

    const phone = $('#phone').val();
    if (!phone)                   { showError('phoneGroup','phoneError','Phone number is required.'); ok=false; }
    else if (!phoneRegex.test(phone)) { showError('phoneGroup','phoneError','Enter a valid 10-digit mobile number.'); ok=false; }
    else showValid('phoneGroup','phoneError');

    const pw = $('#pw1').val();
    if (!pw)           { showError('pwGroup','pwError','Password is required.'); ok=false; }
    else if (pw.length<8) { showError('pwGroup','pwError','Minimum 8 characters required.'); ok=false; }
    else if (!/[A-Za-z]/.test(pw)||!/[0-9]/.test(pw)) { showError('pwGroup','pwError','Must contain letters and numbers.'); ok=false; }
    else showValid('pwGroup','pwError');

    const pw2 = $('#pw2').val();
    if (!pw2)      { showError('pw2Group','pw2Error','Please confirm your password.'); ok=false; }
    else if (pw2!==pw) { showError('pw2Group','pw2Error','Passwords do not match.'); ok=false; }
    else showValid('pw2Group','pw2Error');

    if (!ok) {
      e.preventDefault();
      $('#signupSpinner').addClass('d-none');
      $('#signupBtnText').text('Create Account');
      $('#signupBtn').prop('disabled', false);
      $('html,body').animate({ scrollTop: $('.is-invalid:first').offset().top - 80 }, 200);
      return;
    }

    $('#signupSpinner').removeClass('d-none');
    $('#signupBtnText').text('Creating account…');
    $('#signupBtn').prop('disabled', true);
  });

});
</script>
</body>
</html>