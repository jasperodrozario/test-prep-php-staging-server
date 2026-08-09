<br>
<div id="response"></div>

<form method="post" action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>" class="filter-bar">

    <div class="filter-grid">

        <!-- Search -->
        <div class="filter-item">
            <input type="text" name="key" class="form-control"
                placeholder="Search by email">
        </div>

        <!-- Course Dropdown -->
        <div class="filter-item">
            <select name="course_id" class="form-control">
                <option value="">All Courses</option>
                <?php
                $courses = getQuery("course", "*");
                foreach ($courses as $c) {
                    echo "<option value='{$c['course_id']}'>{$c['course_name']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- From Date -->
        <div class="filter-item">
            <input type="date" name="from_date" class="form-control">
        </div>

        <!-- To Date -->
        <div class="filter-item">
            <input type="date" name="to_date" class="form-control">
        </div>

        <!-- Button -->
        <div class="filter-item">
            <button type="submit" class="btn-search">
                Search
            </button>
        </div>

    </div>

</form>
<br>
<form method="post" action="<?= basename($_SERVER['PHP_SELF'], ".php"); ?>">
    <div class="table-responsive">
        <table id="table-1" class="table table-bordered table-hover feedback-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Course</th>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($total) {
                    foreach ($db_list as $d) {

                        $isResolved = (int)$d['is_resolved'];
                        $isOnline = (int)$d['is_online'];

                        $statusLabel = $isResolved ? "Reviewed" : "Pending";
                        $statusClass = $isResolved ? "status-viewed" : "status-pending";

                        $isOnlineLabel = $isOnline ? "Online" : "Offline";
                        $isOnlineClass = $isOnline ? "status-viewed" : "status-pending";

                        $rowClass = !$isResolved ? "unread-feedback" : "";

                        $user = getDataByWhere("user", "*", ["user_id" => $d['user_id']]);
                        $course_name = getFldValueById("course", "course_name", ["course_id" => $d['course_id']]);
                        $predic_test = getFldValueById("predic_test", "shortcode", ["Predic_test_id" => $d['pred_test_id']]);

                        $rating = (int)$d['rating'];

                        if(!$d['user_id']){
                            $user['first_name'] = $d['user_name'];
                        }

                        echo "
                            <tr class='feedback-row {$rowClass}'>
                                <td>
                                    <div class='user-cell'>
                                        <div class='user-id'>
                                            <div class='id-badge'>#{$d['user_id']}</div>
                                        </div>

                                        <div class='user-info'>
                                            <strong>
                                                {$user['first_name']} {$user['last_name']}
                                                " . (!$isResolved ? "<span class='new-badge'>New</span>" : "") . "
                                            </strong>
                                            <small>{$user['email']}</small>
                                            <small>{$user['mobile']}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <strong>{$course_name}</strong><br>
                                    <small>{$predic_test}</small>
                                </td>


                                <td class='feedback-text'>

                                    <div class='feedback-content'>
                                        <span class='rating-inline'>⭐ {$rating}/5</span>
                                        " . nl2br(htmlentities($d['feedback'])) . "
                                    </div>

                                </td>

                                <td>
                                    <small>{$d['created_at']}</small>
                                </td>

                                <td>
                                    <div class='status-stack'>
                                        <span class='status-badge {$statusClass}'>
                                            {$statusLabel}
                                        </span>
                                        <span class='status-badge {$isOnlineClass}'>
                                            {$isOnlineLabel}
                                        </span>
                                    </div>
                                </td>

                                <td class='action-cell'>
                                    <a class='action-link'
                                    href='" . basename($_SERVER['PHP_SELF'], ".php") . "?id={$d['feedback_id']}&status=" . ($isResolved ? "N" : "Y") . "'>
                                    " . ($isResolved ? "Unseen" : "Seen") . "
                                    </a>
                                    <br>
                                    <a class='action-link'
                                    href='" . basename($_SERVER['PHP_SELF'], ".php") . "?id={$d['feedback_id']}&online_status=" . ($isOnline ? "N" : "Y") . "'>
                                    " . ($isOnline ? "Offline" : "Online") . "
                                    </a>
                                    <br>
                                    <a class='action-link'
                                    href='pred-feedback-entryform?feedback_id={$d['feedback_id']}'>
                                    Edit
                                    </a>
                                    <br>
                                    <a class='action-link danger'
                                    onclick=\"return confirm('Are you sure you want to delete?')\"
                                    href='" . basename($_SERVER['PHP_SELF'], ".php") . "?del_id={$d['feedback_id']}' >
                                    Delete
                                    </a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align:center;color:#999;'>No feedback found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</form>



<? if ($total > 100) {
    echo "<ul class='pagination'>" . paging($total, "", "id=" . $_GET['id'] . "&status=" . $_GET['status']) . "</ul>";
} ?>


<style>
    .feedback-content {
        max-width: 320px;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    @media (max-width: 576px) {
        .feedback-content {
            width: 100%;
            max-width: 100%;
            white-space: normal !important;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .feedback-table td.feedback-text {
            width: 250px;
            /* adjust as needed */
            min-width: 250px;
            max-width: 250px;
        }
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .feedback-table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: none;
    }

    .feedback-table thead th {
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.3px;
        padding: 14px;
        border: none;
    }

    .feedback-table th {
        font-weight: 500;
        font-size: 13px;
        padding: 14px;
    }

    .feedback-table td {
        vertical-align: middle !important;
        font-size: 13px;
        color: #374151;
        padding: 14px;
    }

    /* ID badge */
    .id-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #eef2ff;
        color: #4f46e5;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    /* Rating */
    .rating-badge {
        background: #fff7ed;
        color: #f59e0b;
        padding: 5px 10px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Feedback box */
    .feedback-box {
        max-width: 280px;
        background: #f9fafb;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 12px;
        line-height: 1.4;
        color: #374151;
    }

    /* Status badges */
    .status-badge {
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-viewed {
        background: #dcfce7;
        color: #166534;
    }

    /* Action links */
    .action-link {
        font-size: 12px;
        text-decoration: none;
        color: #4f46e5;
        font-weight: 500;
    }

    .action-link.danger {
        color: #dc2626;
    }

    .feedback-table th {
        text-align: center;
    }

    .user-cell {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        /* keep everything top aligned */
    }

    /* Left side */
    .user-id {
        min-width: 60px;
    }

    /* Right side */
    .user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.4;
    }

    /* .feedback-content {
        max-width: 320px;
        font-size: 12.5px;
        line-height: 1.5;
        color: #374151;
        background: #f9fafb;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid #eef2f7;
    } */

    /* Inline rating */
    .rating-inline {
        display: inline-block;
        background: #fff7ed;
        color: #f59e0b;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 6px;
    }

    /* Feedback text */
    .feedback-text-inner {
        flex: 1;
    }

    .status-stack {
        display: flex;
        flex-direction: column;
        gap: 6px;
        /* controls spacing between badges */
    }

    .filter-bar {
        margin-bottom: 16px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr 1fr 120px;
        gap: 10px;
        align-items: center;
    }

    /* Inputs */
    .filter-grid .form-control {
        height: 40px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        padding: 0 10px;
        transition: 0.2s;
    }

    .filter-grid .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        outline: none;
    }

    /* Button */
    .btn-search {
        width: 100%;
        height: 40px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-search:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }

    .unread-feedback {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
    }

    .unread-feedback td {
        font-weight: 500;
    }

    .new-badge {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 6px;
        background: #dc2626;
        color: #fff;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 600;
    }
</style>


<!-- BEGIN FOOTER -->
<? include("includes/footer.php"); ?>