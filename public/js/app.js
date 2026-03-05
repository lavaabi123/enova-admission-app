/* =============================================
   Main JS
   ============================================= */

$(function () {

  // Toggle password visibility
  $(document).on('click', '.toggle-pw', function () {
    const targetId = $(this).data('target');
    const input    = $('#' + targetId);
    const icon     = $(this).find('i');

    if (input.attr('type') === 'password') {
      input.attr('type', 'text');
      icon.removeClass('bi-eye').addClass('bi-eye-slash');
    } else {
      input.attr('type', 'password');
      icon.removeClass('bi-eye-slash').addClass('bi-eye');
    }
  });

  // Password strength meter
  $('#pw1').on('input', function () {
    const val = $(this).val();
    let score = 0;
    let label = '';
    let color = '#dc3545';

    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const pct = (score / 4) * 100;
    if (score <= 1)      { label = 'Weak';   color = '#dc3545'; }
    else if (score === 2) { label = 'Fair';   color = '#fd7e14'; }
    else if (score === 3) { label = 'Good';   color = '#ffc107'; }
    else                  { label = 'Strong'; color = '#198754'; }

    $('#pwStrengthBar').css({ width: pct + '%', backgroundColor: color });
    $('#pwStrengthLabel').text(label ? 'Strength: ' + label : '');
  });

  // Confirm password match indicator
  $('#pw2').on('input', function () {
    if ($('#pw1').val() === $(this).val()) {
      $(this).removeClass('is-invalid').addClass('is-valid');
    } else {
      $(this).removeClass('is-valid').addClass('is-invalid');
    }
  });

  // Login form — show spinner on submit
  $('#loginForm').on('submit', function () {
    $('#loginSpinner').removeClass('d-none');
  });

  // Bio form — client-side quick checks
  $('#bioForm').on('submit', function (e) {
    let ok = true;
    const fields = ['dob', 'gender', 'address', 'city', 'state', 'pincode',
                    'tenth_board', 'tenth_year', 'tenth_percentage',
                    'twelfth_board', 'twelfth_year', 'twelfth_percentage', 'twelfth_stream'];
    fields.forEach(f => {
      const el = $('[name=' + f + ']');
      if (el.val() === '' || el.val() === null) {
        el.addClass('is-invalid');
        ok = false;
      } else {
        el.removeClass('is-invalid');
      }
    });
    if (!ok) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: $('.is-invalid').first().offset().top - 100 }, 300);
    }
  });

  // File size + type validation (client-side)
  $('input[type=file]').on('change', function () {
    const inputName = $(this).attr('name') || '';
    const isPhoto   = (inputName === 'photo');
    const maxBytes  = isPhoto ? (1 * 1024 * 1024) : (2 * 1024 * 1024);
    const maxLabel  = isPhoto ? '1MB' : '2MB';

    // Allowed types
    const allowedPhoto = ['image/jpeg', 'image/jpg', 'image/png'];
    const allowedCert  = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    const allowed      = isPhoto ? allowedPhoto : allowedCert;

    // Remove any previous error
    $(this).removeClass('is-invalid');
    $(this).siblings('.file-client-error').remove();

    if (!this.files || !this.files[0]) return;
    const file = this.files[0];

    let errorMsg = '';

    if (!allowed.includes(file.type)) {
      errorMsg = isPhoto
        ? 'Only JPG or PNG images are allowed.'
        : 'Only JPG, PNG or PDF files are allowed.';
    } else if (file.size > maxBytes) {
      const sizeMB = (file.size / 1024 / 1024).toFixed(2);
      errorMsg = 'File is ' + sizeMB + 'MB — maximum allowed is ' + maxLabel + '.';
    }

    if (errorMsg) {
      $(this).addClass('is-invalid');
      $(this).after('<div class="file-client-error text-danger small mt-1"><i class="bi bi-x-circle me-1"></i>' + errorMsg + '</div>');
      // Clear the input so the invalid file is not submitted
      $(this).val('');
    }
  });

  // Photo preview (only if file passes validation)
  $('#photoInput').on('change', function () {
    // Remove old preview first
    $('#photoPreview').remove();

    const file = this.files[0];
    // Only preview if file is still set (not cleared by validator above)
    if (file && file.type.startsWith('image/') && file.size <= 1 * 1024 * 1024) {
      const self = this;
      const reader = new FileReader();
      reader.onload = function (e) {
        $(self).after(
          '<img id="photoPreview" class="mt-2 rounded border d-block" ' +
          'style="max-width:90px;max-height:90px;object-fit:cover" src="' + e.target.result + '">'
        );
      };
      reader.readAsDataURL(file);
    }
  });

  // Auto-dismiss flash alerts after 5s
  setTimeout(function () {
    $('.alert-dismissible').alert('close');
  }, 5000);

});