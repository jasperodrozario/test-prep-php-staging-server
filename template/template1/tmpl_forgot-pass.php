<main class="forgot-page">
    <div class="forgot-card">

        <div class="text-center mb-4">
            <div class="icon-circle">
                <i class="fas fa-lock"></i>
            </div>

            <h2>Forgot Password?</h2>

            <?php $hasSuccess = isset($_SESSION['success']); ?>

            <?php if (!$hasSuccess) { ?>
                <p>
                    Enter your registered email address and we'll send you a link
                    to reset your password.
                </p>
            <? } ?>
        </div>

        <?php if ($hasSuccess) { ?>
            <div id="response-success" style="display:block;">
                <?= $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php } ?>

        <?php if (isset($_SESSION['error'])) { ?>
            <div id="response-error" style="display:block;">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php } ?>

        <?php if (!$hasSuccess) { ?>

            <form id="form_forgot_pass" method="post" action="<?= $configs['vpath'] . 'forgot-pass'; ?>">

                <div class="modern-input">
                    <i class="fas fa-envelope"></i>
                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required>
                </div>

                <button class="forgot-btn" type="submit" name="forgot_password_btn">
                    Send Reset Link
                </button>

            </form>

            <div class="register-link">
                Don't have an account?
                <a href="<?= $configs['vpath'] ?>subscribe">
                    Register
                </a>
            </div>

        <?php } ?>

    </div>
</main>

<style>
    body {
        background: #f5f7fb;
    }

    .forgot-page {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }

    .forgot-card {
        width: 100%;
        max-width: 460px;
        background: #fff;
        border-radius: 20px;
        padding: 45px;
        box-shadow: 0 15px 45px rgba(0, 0, 0, .08);
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        margin: auto;
        border-radius: 50%;
        background: #fff6e7;
        color: #fdb44b;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 28px;
        margin-bottom: 20px;
    }

    .forgot-card h2 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .forgot-card p {
        color: #666;
        font-size: 15px;
        line-height: 1.6;
    }

    .modern-input {
        position: relative;
    }

    .modern-input i {
        position: absolute;
        left: 18px;
        top: 0;
        line-height: 56px;
        /* Match the input height */
        font-size: 16px;
        color: #999;
    }

    .modern-input input {
        width: 100%;
        height: 56px;
        padding: 0 16px 0 50px;
        font-size: 15px;
        line-height: 56px;
        /* Keeps placeholder centered */
        border: 1px solid #ddd;
        border-radius: 14px;
        box-sizing: border-box;
    }

    .modern-input input:focus {
        border-color: #fdb44b;
        outline: none;
        box-shadow: 0 0 0 4px rgba(253, 180, 75, .18);
    }

    .forgot-btn {
        width: 100%;
        margin-top: 25px;
        height: 56px;
        border: none;
        border-radius: 14px;
        background: #fdb44b;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        transition: .3s;
    }

    .forgot-btn:hover {
        background: #e59b28;
    }

    .register-link {
        text-align: center;
        margin-top: 25px;
        color: #666;
    }

    .register-link a {
        color: #fdb44b;
        font-weight: 600;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    #response-success {
        display: none;
        padding: 16px;
        margin-bottom: 20px;
        border-radius: 12px;
        background: #ecfdf3;
        color: #027a48;
        border: 1px solid #abefc6;
    }

    #response-error {
        display: none;
        padding: 16px;
        margin-bottom: 20px;
        border-radius: 12px;
        background: #fef3f2;
        color: #b42318;
        border: 1px solid #fecdca;
    }
</style>