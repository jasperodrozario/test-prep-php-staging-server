<style>
  :root {
    --bg: #F5F6F8;
    --surface: #FFFFFF;
    --surface-alt: #FAFBFC;
    --border: #E4E7EC;
    --border-strong: #CDD2DB;
    --text-primary: #15171C;
    --text-secondary: #5B6472;
    --text-muted: #98A0AC;
    --accent: #3350E0;
    --accent-hover: #2740C4;
    --accent-light: #EBF0FE;
    --danger: #D5432E;
    --danger-light: #FBEAE7;
    --success: #1E8A5F;
    --success-light: #E7F5EE;
    --radius: 10px;
  }

  .ea-wrap * {
    box-sizing: border-box;
  }

  .ea-wrap {
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--text-primary);
    max-width: 920px;
    margin: 0 auto;
    padding: 32px 40px 0;
  }

  .ea-mono {
    font-family: 'JetBrains Mono', monospace;
  }

  .ea-crumb {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 6px;
  }

  .ea-crumb span {
    color: var(--text-secondary);
  }

  .ea-crumb a {
    color: var(--text-secondary);
    text-decoration: none;
  }

  .ea-header {
    margin-bottom: 24px;
  }

  .ea-wrap h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 4px;
    letter-spacing: -0.01em;
  }

  .ea-subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
  }

  .ea-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px;
    margin-bottom: 18px;
  }

  .ea-card h2 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 2px;
  }

  .ea-card .ea-card-sub {
    font-size: 12.5px;
    color: var(--text-muted);
    margin: 0 0 18px;
  }

  .ea-photo-row {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .ea-photo-preview {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: var(--surface-alt);
    border: 1.5px dashed var(--border-strong);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    flex-shrink: 0;
    overflow: hidden;
  }

  .ea-photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .ea-photo-hint {
    font-size: 11.5px;
    color: var(--text-muted);
    margin-top: 6px;
  }

  .ea-file-input {
    font-size: 12.5px;
  }

  .ea-grid2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .ea-field {
    margin-bottom: 16px;
  }

  .ea-field.full {
    grid-column: 1 / -1;
  }

  .ea-field label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 6px;
  }

  .ea-field .req {
    color: var(--danger);
  }

  .ea-field input,
  .ea-field select,
  .ea-field textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid var(--border-strong);
    border-radius: 8px;
    font-size: 13.5px;
    font-family: inherit;
    color: var(--text-primary);
    background: var(--surface);
    outline: none;
  }

  .ea-field input.ea-err,
  .ea-field select.ea-err {
    border-color: var(--danger);
  }

  .ea-field input:focus,
  .ea-field select:focus,
  .ea-field textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-light);
  }

  .ea-field textarea {
    resize: vertical;
    min-height: 70px;
  }

  .ea-hint {
    font-size: 11.5px;
    color: var(--text-muted);
    margin-top: 5px;
  }

  .ea-field-error {
    font-size: 11.5px;
    color: var(--danger);
    margin-top: 5px;
  }

  .ea-btn {
    border: 1px solid var(--border-strong);
    background: var(--surface);
    color: var(--text-primary);
    font-size: 13.5px;
    font-weight: 500;
    padding: 9px 16px;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
  }

  .ea-btn:hover {
    background: var(--surface-alt);
  }

  .ea-btn-primary {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
  }

  .ea-btn-primary:hover {
    background: var(--accent-hover);
  }

  .ea-actions-bar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 20px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    max-width: 850px;
    margin: 0 auto;
  }

  .ea-creds-tip {
    background: var(--accent-light);
    border: 1px solid #C7D3FB;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 12px;
    color: #233A9E;
    margin-bottom: 16px;
    line-height: 1.5;
  }

  .ea-error-summary {
    background: var(--danger-light);
    border: 1px solid #F2C3BA;
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 13px;
    color: #8C2E1B;
    margin-bottom: 18px;
  }

  .ea-success-summary {
    background: var(--success-light);
    border: 1px solid #BFE3D2;
    border-radius: 8px;
    padding: 14px 16px;
    font-size: 13px;
    color: #125C40;
    margin-bottom: 18px;
    line-height: 1.6;
  }

  .ea-success-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 18px;
  }
</style>

<div class="ea-wrap">

  <!-- <div class="ea-crumb">Employee management <span>/</span> <a href="employee-list">Employees</a> <span>/</span> <span>Add employee</span></div> -->
  <div class="ea-header">
    <h1><?= $edit_id ? 'Edit employee' : 'Add employee' ?></h1>
    <p class="ea-subtitle"><?= $edit_id ? "Update this team member's profile and details." : 'Create a profile and login for a new team member.' ?></p>
  </div>

  <?php if (!empty($saved)): ?>
    <div class="ea-success-summary">
      <b><?= $edit_id ? 'Employee updated.' : 'Employee saved.' ?></b> Employee ID: <b class="ea-mono"><?= htmlspecialchars($saved_employee_code) ?></b><br>
      <?php if (!empty($saved_password)): ?>
        Username: <b><?= htmlspecialchars($saved_username) ?></b> &middot;
        Temporary password: <b class="ea-mono"><?= htmlspecialchars($saved_password) ?></b><br>
        Copy this password now — it won't be shown again.
      <?php else: ?>
        Username: <b><?= htmlspecialchars($saved_username) ?></b>
      <?php endif; ?>
    </div>
    <div class="ea-success-actions">
      <a href="employee-list" class="ea-btn">Back to employee list</a>
      <?php if (!$edit_id): ?>
        <a href="add-employee" class="ea-btn ea-btn-primary">Add another employee</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="ea-error-summary">Please fix <?= count($errors) ?> field<?= count($errors) > 1 ? 's' : '' ?> below before saving.</div>
  <?php endif; ?>

  <?php if (empty($saved)): ?>
  <form method="post" enctype="multipart/form-data" novalidate id="employeeAddForm">
    <input type="hidden" name="temp_password_carry" value="<?= htmlspecialchars($temp_password) ?>">
    <input type="hidden" name="employee_id" value="<?= (int) $edit_id ?>">

    <div class="ea-card">
      <h2>Profile photo</h2>
      <p class="ea-card-sub">Optional — shown across the admin panel and their own dashboard.</p>
      <div class="ea-photo-row">
        <div class="ea-photo-preview" id="photoPreview">
          <?php if (!empty($existing_photo)): ?>
            <img src="<?= htmlspecialchars($vpath . $existing_photo) ?>" alt="Profile photo">
          <?php else: ?>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <circle cx="8.5" cy="8.5" r="1.5" />
            <path d="m21 15-5-5L5 21" />
          </svg>
          <?php endif; ?>
        </div>
        <div>
          <input class="ea-file-input" type="file" name="photo" id="photoInput" accept="image/jpeg,image/png">
          <div class="ea-photo-hint">JPG or PNG, under 2MB.</div>
          <?php if (!empty($errors['photo'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['photo']) ?></div><?php endif; ?>
        </div>
      </div>
    </div>

    <div class="ea-card">
      <h2>Personal information</h2>
      <p class="ea-card-sub">Basic identity and contact details.</p>
      <div class="ea-grid2">
        <div class="ea-field">
          <label>First name <span class="req">*</span></label>
          <input type="text" name="first_name" value="<?= htmlspecialchars($form['first_name']) ?>" class="<?= isset($errors['first_name']) ? 'ea-err' : '' ?>" placeholder="e.g. Sarah">
          <?php if (!empty($errors['first_name'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['first_name']) ?></div><?php endif; ?>
        </div>
        <div class="ea-field">
          <label>Last name <span class="req">*</span></label>
          <input type="text" name="last_name" value="<?= htmlspecialchars($form['last_name']) ?>" class="<?= isset($errors['last_name']) ? 'ea-err' : '' ?>" placeholder="e.g. Johnson">
          <?php if (!empty($errors['last_name'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['last_name']) ?></div><?php endif; ?>
        </div>
        <div class="ea-field">
          <label>Email address <span class="req">*</span></label>
          <input type="email" name="email" value="<?= htmlspecialchars($form['email']) ?>" class="<?= isset($errors['email']) ? 'ea-err' : '' ?>" placeholder="sarah.j@company.com">
          <?php if (!empty($errors['email'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
        </div>
        <div class="ea-field">
          <label>Phone number</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($form['phone']) ?>" placeholder="(555) 012-3344">
        </div>
        <div class="ea-field">
          <label>Date of birth</label>
          <input type="date" name="dob" value="<?= htmlspecialchars($form['dob']) ?>">
        </div>
        <div class="ea-field">
          <label>Gender</label>
          <select name="gender">
            <?php foreach ($genderOptions as $val => $label): ?>
              <option value="<?= htmlspecialchars($val) ?>" <?= $form['gender'] === $val ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="ea-field full">
          <label>Address</label>
          <textarea name="address" placeholder="Street, city, state, ZIP"><?= htmlspecialchars($form['address']) ?></textarea>
        </div>
      </div>
    </div>

    <div class="ea-card">
      <h2>Employment details</h2>
      <p class="ea-card-sub">Role, department, and reporting structure.</p>
      <div class="ea-grid2">
        <div class="ea-field">
          <label>Employee ID</label>
          <input type="text" class="ea-mono" value="<?= htmlspecialchars($employee_code) ?>" placeholder="Auto-generated after saving" disabled style="background:var(--surface-alt);color:var(--text-muted);">
        </div>
        <div class="ea-field">
          <label>Status</label>
          <select name="status">
            <?php foreach ($statusOptions as $val => $label): ?>
              <option value="<?= htmlspecialchars($val) ?>" <?= $form['status'] === $val ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="ea-field">
          <label>Designation <span class="req">*</span></label>
          <input type="text" name="designation" value="<?= htmlspecialchars($form['designation']) ?>" class="<?= isset($errors['designation']) ? 'ea-err' : '' ?>" placeholder="e.g. Senior Tutor">
          <?php if (!empty($errors['designation'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['designation']) ?></div><?php endif; ?>
        </div>
        <div class="ea-field">
          <label>Department <span class="req">*</span></label>
          <select name="department" class="<?= isset($errors['department']) ? 'ea-err' : '' ?>">
            <option value="">Select department</option>
            <?php foreach ($departments as $dept): ?>
              <option value="<?= htmlspecialchars($dept) ?>" <?= $form['department'] === $dept ? 'selected' : '' ?>><?= htmlspecialchars($dept) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (!empty($errors['department'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['department']) ?></div><?php endif; ?>
        </div>
        <div class="ea-field">
          <label>Employment type</label>
          <select name="employment_type">
            <?php foreach ($employmentTypes as $val => $label): ?>
              <option value="<?= htmlspecialchars($val) ?>" <?= $form['employment_type'] === $val ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="ea-field">
          <label>Date of joining <span class="req">*</span></label>
          <input type="date" name="date_of_joining" value="<?= htmlspecialchars($form['date_of_joining']) ?>" class="<?= isset($errors['date_of_joining']) ? 'ea-err' : '' ?>">
          <?php if (!empty($errors['date_of_joining'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['date_of_joining']) ?></div><?php endif; ?>
        </div>
      </div>
    </div>

    <div class="ea-card">
      <h2>Login credentials</h2>
      <p class="ea-card-sub">Used for the employee's own attendance and leave forms.</p>
      <?php if (!$edit_id): ?>
        <div class="ea-creds-tip"><b>Note —</b> the employee will use these credentials to mark their own daily attendance and submit leave requests. This temporary password is shown once after saving — copy it to share with them.</div>
      <?php endif; ?>
      <div class="ea-grid2">
        <div class="ea-field">
          <label>Username <span class="req">*</span></label>
          <input type="text" name="username" value="<?= htmlspecialchars($form['username']) ?>" class="<?= isset($errors['username']) ? 'ea-err' : '' ?>" placeholder="sarah.johnson">
          <?php if (!empty($errors['username'])): ?><div class="ea-field-error"><?= htmlspecialchars($errors['username']) ?></div><?php endif; ?>
        </div>
        <?php if ($edit_id): ?>
          <div class="ea-field">
            <label>Password</label>
            <label style="display:flex;align-items:center;gap:8px;font-weight:400;font-size:13.5px;color:var(--text-primary);margin-top:9px;">
              <input type="checkbox" name="reset_password" value="1" style="width:auto;">
              Generate a new temporary password
            </label>
            <div class="ea-hint">Leave unchecked to keep their current password.</div>
          </div>
        <?php else: ?>
          <div class="ea-field">
            <label>Temporary password</label>
            <input type="text" class="ea-mono" value="<?= htmlspecialchars($temp_password) ?>" readonly style="background:var(--surface-alt);">
          </div>
        <?php endif; ?>
      </div>
    </div>

  </form>
  <?php endif; ?>
</div>

<?php if (empty($saved)): ?>
<div class="ea-actions-bar">
  <a href="employee-list" class="ea-btn">Cancel</a>
  <button type="submit" form="employeeAddForm" class="ea-btn ea-btn-primary">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
      <path d="M20 6 9 17l-5-5" />
    </svg>
    <?= $edit_id ? 'Save changes' : 'Save employee' ?>
  </button>
</div>
<?php endif; ?>

<script>
(function () {
  var input = document.getElementById('photoInput');
  var preview = document.getElementById('photoPreview');
  if (!input || !preview) return;

  var placeholder = preview.innerHTML;

  input.addEventListener('change', function () {
    var file = input.files && input.files[0];
    if (!file) {
      preview.innerHTML = placeholder;
      return;
    }
    var reader = new FileReader();
    reader.onload = function (e) {
      preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile photo preview">';
    };
    reader.readAsDataURL(file);
  });
})();
</script>