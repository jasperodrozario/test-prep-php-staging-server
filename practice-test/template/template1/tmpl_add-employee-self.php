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

    * {
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        color: var(--text-primary);
    }


    .wrap {
        width: 100%;
        max-width: 420px;
        margin: 60px auto;
    }

    .header {
        margin-bottom: 20px;
        text-align: center;
    }

    h1 {
        font-size: 22px;
        font-weight: 600;
        margin: 0 0 4px;
        letter-spacing: -0.01em;
    }

    .subtitle {
        font-size: 13.5px;
        color: var(--text-secondary);
        margin: 0;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 26px;
    }

    .field {
        margin-bottom: 16px;
    }

    .field label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }

    .field input {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid var(--border-strong);
        border-radius: 8px;
        font-size: 13.5px;
        font-family: inherit;
        color: var(--text-primary);
        outline: none;
    }

    .field input.err {
        border-color: var(--danger);
    }

    .field input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .field .field-error {
        font-size: 12px;
        color: var(--danger);
        margin-top: 4px;
    }

    .password-wrap {
        position: relative;
    }

    .password-wrap input {
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 6px;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        padding: 0;
        border: none;
        background: none;
        color: var(--text-muted);
        cursor: pointer;
    }

    .toggle-password:hover {
        color: var(--text-secondary);
    }

    .btn-primary {
        width: 100%;
        border: 1px solid var(--accent);
        background: var(--accent);
        color: #fff;
        font-size: 13.5px;
        font-weight: 500;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--accent-hover);
        border-color: var(--accent-hover);
    }

    .error-summary {
        background: var(--danger-light);
        border: 1px solid #F2C3BA;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 13px;
        color: #8C2E1B;
        margin-bottom: 18px;
    }

    .success-summary {
        background: var(--success-light);
        border: 1px solid #BFE3D2;
        border-radius: 8px;
        padding: 16px;
        font-size: 13.5px;
        color: #125C40;
        line-height: 1.6;
        text-align: center;
    }
</style>

<div class="wrap">

    <div class="header">
        <h1>Create your employee profile</h1>
        <p class="subtitle">Set up your login. An administrator will activate your account before you can sign in.</p>
    </div>

    <div class="card">
        <?php if ($saved): ?>
            <div class="success-summary">
                <b>Profile created.</b><br>
                Your account is pending activation. An administrator will review and turn on access &mdash; you'll be able to log in once that's done.
            </div>
        <?php else: ?>

            <?php if (!empty($errors)): ?>
                <div class="error-summary"><?= !empty($errors['general']) ? htmlspecialchars($errors['general']) : 'Please fix the field' . (count($errors) > 1 ? 's' : '') . ' below.' ?></div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($form['full_name']) ?>" class="<?= isset($errors['full_name']) ? 'err' : '' ?>" placeholder="e.g. Sarah Johnson">
                    <?php if (!empty($errors['full_name'])): ?><div class="field-error"><?= htmlspecialchars($errors['full_name']) ?></div><?php endif; ?>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($form['email']) ?>" class="<?= isset($errors['email']) ? 'err' : '' ?>" placeholder="sarah.j@company.com">
                    <?php if (!empty($errors['email'])): ?><div class="field-error"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
                </div>
                <div class="field">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($form['username']) ?>" class="<?= isset($errors['username']) ? 'err' : '' ?>" placeholder="sarah.johnson">
                    <?php if (!empty($errors['username'])): ?><div class="field-error"><?= htmlspecialchars($errors['username']) ?></div><?php endif; ?>
                </div>
                <div class="field">
                    <label>Password</label>
                    <div class="password-wrap">
                        <input type="password" name="password" id="password" class="<?= isset($errors['password']) ? 'err' : '' ?>" placeholder="At least 6 characters">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" aria-label="Show password">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    <?php if (!empty($errors['password'])): ?><div class="field-error"><?= htmlspecialchars($errors['password']) ?></div><?php endif; ?>
                </div>
                <button type="submit" class="btn-primary">Create profile</button>
            </form>

        <?php endif; ?>
    </div>

</div>

<script>
function togglePasswordVisibility() {
    var input = document.getElementById('password');
    var icon = document.getElementById('eyeIcon');
    if (!input || !icon) return;

    var showing = input.type === 'text';
    input.type = showing ? 'password' : 'text';

    icon.innerHTML = showing
        ? '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path><circle cx="12" cy="12" r="3"></circle>'
        : '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-7-11-7a18.7 18.7 0 0 1 4.22-5.22M9.9 4.24A10.4 10.4 0 0 1 12 4c7 0 11 7 11 7a18.7 18.7 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
}
</script>