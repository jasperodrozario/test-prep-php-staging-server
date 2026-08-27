<style>
  :root{
    --bg:#F5F6F8; --surface:#FFFFFF; --surface-alt:#FAFBFC;
    --border:#E4E7EC; --border-strong:#CDD2DB;
    --text-primary:#15171C; --text-secondary:#5B6472; --text-muted:#98A0AC;
    --accent:#3350E0; --accent-hover:#2740C4; --accent-light:#EBF0FE;
    --danger:#D5432E; --danger-light:#FBEAE7;
    --success:#1E8A5F; --success-light:#E7F5EE;
    --warning:#B4670A; --warning-light:#FCF0DF;
    --radius:10px;
  }
  *{box-sizing:border-box;}
  body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text-primary);-webkit-font-smoothing:antialiased;}
  .wrap{max-width:520px;margin:0 auto;padding:32px 40px 80px;}
  .crumb{font-size:13px;color:var(--text-muted);margin-bottom:6px;}
  .crumb span{color:var(--text-secondary);}
  .header{margin-bottom:24px;}
  h1{font-size:24px;font-weight:600;margin:0 0 4px;letter-spacing:-0.01em;}
  .subtitle{font-size:14px;color:var(--text-secondary);margin:0;}

  .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:18px;}
  .notice{font-size:13px;color:#233A9E;background:var(--accent-light);border:1px solid #C7D3FB;border-radius:8px;padding:10px 12px;margin-bottom:18px;line-height:1.5;}
  .success-note{font-size:13px;color:var(--success);background:var(--success-light);border:1px solid #BFE3D2;border-radius:8px;padding:10px 12px;margin-bottom:16px;}

  .field{margin-bottom:16px;}
  .field label{display:block;font-size:12.5px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;}
  .field input{width:100%;padding:9px 12px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;font-family:inherit;color:var(--text-primary);outline:none;}
  .field input.err{border-color:var(--danger);}
  .field input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-light);}
  .field-error{font-size:11.5px;color:var(--danger);margin-top:5px;}
  .hint{font-size:11.5px;color:var(--text-muted);margin-top:5px;}

  .btn{border:1px solid var(--border-strong);background:var(--surface);color:var(--text-primary);font-size:13.5px;font-weight:500;
    padding:9px 18px;border-radius:8px;cursor:pointer;text-decoration:none;display:inline-block;}
  .btn-primary{background:var(--accent);border-color:var(--accent);color:#fff;}
  .btn-primary:hover{background:var(--accent-hover);}
  .actions-row{display:flex;justify-content:flex-end;gap:10px;}
</style>
</head>
<body>
<div class="wrap">

  <!-- <div class="crumb">Employee management <span>/</span> <span>Change password</span></div> -->
  <div class="header">
    <h1>Change password</h1>
    <p class="subtitle">Set your own password to replace the one you were given.</p>
  </div>

  <?php if (empty($employee_id)): ?>
    <div class="card">
      <p style="margin:0;color:var(--text-secondary);">You're not signed in as an employee, so there's nothing to show here yet.</p>
    </div>
  <?php else: ?>

    <?php if (!empty($forced) && empty($success)): ?>
      <div class="notice">You're still using the temporary password you were given. Set your own below before continuing.</div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="card">
        <div class="success-note">Your password has been updated.</div>
        <div class="actions-row">
          <a href="employee-attendance-form" class="btn btn-primary">Continue</a>
        </div>
      </div>
    <?php else: ?>
      <div class="card">
        <form method="post">
          <div class="field">
            <label>Current password <span style="color:var(--danger);">*</span></label>
            <input type="password" name="current_password" class="<?= isset($errors['current_password']) ? 'err' : '' ?>" autocomplete="current-password">
            <?php if (!empty($errors['current_password'])): ?><div class="field-error"><?= htmlspecialchars($errors['current_password']) ?></div><?php endif; ?>
          </div>
          <div class="field">
            <label>New password <span style="color:var(--danger);">*</span></label>
            <input type="password" name="new_password" class="<?= isset($errors['new_password']) ? 'err' : '' ?>" autocomplete="new-password">
            <?php if (!empty($errors['new_password'])): ?><div class="field-error"><?= htmlspecialchars($errors['new_password']) ?></div><?php else: ?><div class="hint">At least 8 characters.</div><?php endif; ?>
          </div>
          <div class="field">
            <label>Confirm new password <span style="color:var(--danger);">*</span></label>
            <input type="password" name="confirm_password" class="<?= isset($errors['confirm_password']) ? 'err' : '' ?>" autocomplete="new-password">
            <?php if (!empty($errors['confirm_password'])): ?><div class="field-error"><?= htmlspecialchars($errors['confirm_password']) ?></div><?php endif; ?>
          </div>
          <div class="actions-row">
            <button type="submit" class="btn btn-primary">Update password</button>
          </div>
        </form>
      </div>
    <?php endif; ?>

  <?php endif; ?>

</div>
