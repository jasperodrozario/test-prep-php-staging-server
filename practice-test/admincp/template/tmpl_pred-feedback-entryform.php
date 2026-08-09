<br>

<!-- success or error msg -->
<?php if (!empty($feedback_msg)) : ?>
    <div class="alert alert-<?=($feedback_msg == $configs['success_msg']) ? 'success' : 'danger';?>" role="alert">
        <?= $feedback_msg; ?>
    </div>
<?php endif; ?>

<!-- Modern Feedback Form -->
<div class="form-card-modern">
    <form method="post" action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>" enctype="multipart/form-data">

        <input type="hidden" name="feedback_id" value="<?php echo $_GET['feedback_id'] ?>">

        <div class="form-header">
            <h4 class="form-title-modern">
                <i class="bi bi-chat-square-text-fill"></i> Feedback Form
            </h4>
        </div>

        <!-- User Name -->
        <div class="form-group-modern">
            <label class="form-label-modern">
                <i class="bi bi-person-circle"></i> User Name <span class="text-danger">*</span>
            </label>
            <input type="text" class="input-modern" name='user_name' value="<?php echo $db_value['user_name'] ?>" required placeholder="Enter your full name">
        </div>

        <!-- Course -->
        <div class="form-group-modern">
            <label class="form-label-modern">
                <i class="bi bi-book"></i> Course <span class="text-danger">*</span>
            </label>
            <select name='course_id' class="select-modern" required>
                <option value='0'>Select Course</option>
                <?php
                $course_list = getQuery("course", "*", "", "course_name");
                foreach ($course_list as $dd) {
                    $selected = ($db_value['course_id'] == $dd['course_id']) ? 'selected' : '';
                    echo "<option value=" . $dd['course_id'] . " $selected>" . $dd['course_name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Rating -->
        <div class="form-group-modern">
            <label class="form-label-modern">
                <i class="bi bi-star-fill"></i> Rating <span class="text-danger">*</span>
            </label>
            <div class="rating-wrapper">
                <input type="number" class="input-modern" name='rating' value="<?php echo $db_value['rating'] ?>" required min="1" max="5" placeholder="Enter rating (1-5)">
                <span class="rating-hint">1-5</span>
            </div>
        </div>

        <!-- Date -->
        <div class="form-group-modern">
            <label class="form-label-modern">
                <i class="bi bi-calendar"></i> Created Date <span class="text-danger">*</span>
            </label>

            <input
                type="date"
                class="input-modern"
                name="created_at"
                value="<?= !empty($db_value['created_at']) ? date('Y-m-d', strtotime($db_value['created_at'])) : date('Y-m-d'); ?>"
                required>
        </div>

        <!-- Feedback -->
        <div class="form-group-modern">
            <label class="form-label-modern">
                <i class="bi bi-pencil-square"></i> Feedback
            </label>
            <textarea class="textarea-modern" name='feedback' rows="4" placeholder="Write your feedback here..."><?= html_entity_decode($db_value['feedback']); ?></textarea>
        </div>

        <!-- Display on Website -->
        <div class="form-group-modern">
            <div class="toggle-wrapper">
                <label class="toggle-label">
                    <input type="checkbox" name="is_online" <?php echo ($db_value['is_online'] == 'Y') ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">
                        <i class="bi bi-globe2"></i> Display on Website
                    </span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group-modern" style="margin-bottom: 0;">
            <input type="submit" name="SBMT_REG" value="Submit Feedback" class="btn btn-success">
        </div>

    </form>
</div>

<style>
    /* ===== MODERN FORM STYLES ===== */
    .form-card-modern {
        max-width: 900px;
        width: 100%;
        margin: 0 auto;
    }

    .form-header {
        margin-bottom: 2rem;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 1rem;
    }

    .form-title-modern {
        font-size: 2rem;
        font-weight: 700;
        color: #0b1e33;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.02em;
    }

    .form-title-modern i {
        color: #2563eb;
        font-size: 2rem;
    }

    /* ===== FORM GROUP ===== */
    .form-group-modern {
        margin-bottom: 1.75rem;
    }

    .form-group-modern:last-of-type {
        margin-bottom: 0;
    }

    /* Labels */
    .form-label-modern {
        font-weight: 600;
        font-size: 1.5rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.75rem;
    }

    .form-label-modern i {
        color: #64748b;
        font-size: 1.3rem;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    /* Inputs */
    .input-modern,
    .select-modern,
    .textarea-modern {
        width: 100%;
        padding: 0.9rem 1.2rem;
        font-size: 1.3rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        background: #fafcff;
        color: #0f172a;
        transition: all .2s ease;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
    }

    .input-modern,
    .select-modern {
        height: 56px;
    }

    .textarea-modern {
        min-height: 140px;
        resize: vertical;
        line-height: 1.7;
    }

    .input-modern:focus,
    .select-modern:focus,
    .textarea-modern:focus {
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .08);
    }

    .input-modern::placeholder,
    .textarea-modern::placeholder {
        color: #94a3b8;
        font-size: 1.3rem;
    }

    /* Select */
    .select-modern {
        appearance: none;
        cursor: pointer;
        padding-right: 3rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2364748b' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
    }

    /* Rating */
    .rating-wrapper {
        position: relative;
    }

    .rating-wrapper .input-modern {
        padding-right: 5rem;
    }

    .rating-hint {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: #eef2ff;
        color: #2563eb;
        font-size: 1.3rem;
        font-weight: 600;
        padding: .3rem .8rem;
        border-radius: 30px;
        pointer-events: none;
    }

    /* Toggle */
    .toggle-wrapper {
        display: flex;
        align-items: center;
    }

    .toggle-label {
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        user-select: none;
    }

    .toggle-label input {
        display: none;
    }

    .toggle-slider {
        width: 52px;
        height: 28px;
        background: #cbd5e1;
        border-radius: 50px;
        position: relative;
        transition: .3s;
    }

    .toggle-slider::after {
        content: "";
        width: 22px;
        height: 22px;
        background: #fff;
        border-radius: 50%;
        position: absolute;
        left: 3px;
        top: 3px;
        transition: .3s;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
    }

    .toggle-label input:checked+.toggle-slider {
        background: #2563eb;
    }

    .toggle-label input:checked+.toggle-slider::after {
        left: 27px;
    }

    .toggle-text {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1.3rem;
        font-weight: 600;
        color: #1e293b;
    }

    .toggle-text i {
        font-size: 1.3rem;
        color: #64748b;
    }

    /* Button */
    .btn-submit-modern {
        width: 100%;
        height: 58px;
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: .25s;
        box-shadow: 0 5px 16px rgba(37, 99, 235, .25);
    }

    .btn-submit-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37, 99, 235, .35);
    }

    .btn-submit-modern i {
        font-size: 1.3rem;
    }

    /* Remove number arrows */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-card-modern {
            padding: 1.5rem;
            max-width: 100%;
        }

        .form-title-modern {
            font-size: 1.6rem;
        }

        .form-title-modern i {
            font-size: 1.6rem;
        }

        .form-label-modern {
            font-size: 1.3rem;
        }

        .input-modern,
        .select-modern,
        .textarea-modern {
            font-size: 1.3rem;
        }

        .btn-submit-modern {
            height: 52px;
            font-size: 1.3rem;
        }

        .toggle-text {
            font-size: 1.3rem;
        }
    }
</style>