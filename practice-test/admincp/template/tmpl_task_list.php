<div class="content-wrapper">
    <section class="content-header">
    </section>

    <section class="content">
        <div class="task-list-container">
            <!-- Search & Filter Section -->
            <div class="search-section">
                <form method='get' action="<?= $configs['admin_vpath'] ?>task_list">
                    <input type="hidden" name="status" value="<?= $_GET['status'] ?? '' ?>">

                    <div class="search-grid">
                        <!-- Search Input -->
                        <div class="search-field">
                            <div class="input-icon">
                                <i class="bi bi-search"></i>
                                <input type='text' name='search' class='search-input'
                                    placeholder='Search by ID, Assigner, or Assignee...'
                                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="search-button">
                            <button type='submit' class='btn-search'>
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>

                        <!-- Status Filters -->
                        <div class="status-filters">
                            <label class="filter-radio">
                                <input type="radio" name="search_status" value="Pending"
                                    <?= ($_GET['search_status'] ?? '') === 'Pending' ? 'checked' : ''; ?>>
                                <span class="filter-badge pending">Pending</span>
                            </label>
                            <label class="filter-radio">
                                <input type="radio" name="search_status" value="In Progress"
                                    <?= ($_GET['search_status'] ?? '') === 'In Progress' ? 'checked' : ''; ?>>
                                <span class="filter-badge in-progress">In Progress</span>
                            </label>
                            <label class="filter-radio">
                                <input type="radio" name="search_status" value="Completed"
                                    <?= ($_GET['search_status'] ?? '') === 'Completed' ? 'checked' : ''; ?>>
                                <span class="filter-badge completed">Completed</span>
                            </label>
                            <label class="filter-radio">
                                <input type="radio" name="search_status" value="all"
                                    <?= ($_GET['search_status'] ?? 'all') === 'all' ? 'checked' : ''; ?>>
                                <span class="filter-badge all">All</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Task Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">
                        <i class="bi bi-list-task"></i> Task List
                        <span class="task-count">(<?= $total ?> tasks)</span>
                    </div>
                    <div class="table-actions">
                        <a href="employee_task" class="btn-add">
                            <i class="bi bi-plus-circle"></i> Add New Task
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="task-table">
                        <thead>
                            <tr>
                                <th width="10%">ID & Date</th>
                                <th width="12%">Assigned By</th>
                                <th width="12%">Assigned To</th>
                                <th width="46%">Task Details</th>
                                <th width="15%">Status & Test</th>
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total > 0): ?>
                                <?php foreach ($db_list as $task): ?>
                                    <tr class="task-row">
                                        <td>
                                            <div class="task-id">#<?= $task['id'] ?></div>
                                            <div class="task-date">
                                                <i class="bi bi-calendar3"></i>
                                                <?= date('M d, Y', strtotime($task['date_created'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="assignee-name">
                                                <i class="bi bi-person-badge"></i>
                                                <?= htmlspecialchars_decode($task['assigned_by']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="assignee-name">
                                                <i class="bi bi-person-check"></i>
                                                <?= htmlspecialchars_decode($task['assigned_to']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="task-details">
                                                <div class="task-title">
                                                    <?= htmlspecialchars_decode($task['title']) ?>
                                                    <?php if ($task['on_staging'] == 'Y'): ?>
                                                        <span class="staging-badge">
                                                            <i class="bi bi-server"></i> Staging
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (($task['routine_task'] ?? '') == 'Y'): ?>
                                                        <span class="routine-badge">
                                                            <i class="bi bi-arrow-repeat"></i> Routine Task
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (($task['routine_task'] ?? '') == 'Y' && !empty($task['routine_type'])): ?>
                                                        <div class="routine-type">
                                                            <i class="bi bi-clock-history"></i>
                                                            <?= htmlspecialchars_decode($task['routine_type']) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="task-desc" id="desc<?= $task['id'] ?>">
                                                    <?= htmlspecialchars_decode($task['description']) ?>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="read-more-btn"
                                                    id="btn<?= $task['id'] ?>"
                                                    onclick="toggleDesc(<?= $task['id'] ?>, this)"
                                                    style="display:none;">

                                                    <span>Read More</span>
                                                    <i class="fa fa-chevron-down"></i>

                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="status-container">
                                                <span class="status-badge <?= strtolower(str_replace(' ', '-', $task['status'])) ?>">
                                                    <?php if ($task['status'] == 'Pending'): ?>
                                                        <i class="bi bi-clock"></i>
                                                    <?php elseif ($task['status'] == 'In Progress'): ?>
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-check-circle"></i>
                                                    <?php endif; ?>
                                                    <?= $task['status'] ?>
                                                </span>
                                                <span class="test-badge <?= $task['tested'] == 'Y' ? 'tested' : 'not-tested' ?>">
                                                    <i class="bi <?= $task['tested'] == 'Y' ? 'bi-check2-circle' : 'bi-x-circle' ?>"></i>
                                                    Test: <?= $task['tested'] == 'Y' ? 'Completed' : 'Pending' ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="employee_task?task_id=<?= $task['id'] ?>"
                                                    class="action-btn edit-btn"
                                                    title="Edit Task">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="javascript:if(confirm('Delete this task?')) {
                                                        window.location='?del_id=<?= $task['id'] ?><?= isset($_GET['search']) ? '&search=' . $_GET['search'] : '' ?>&status=completed';
                                                    }"
                                                    class="action-btn delete-btn"
                                                    title="Delete Task">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h4>No tasks found</h4>
                                            <p><?= (!empty($_GET['search'])) ? "Filtered by: " . htmlspecialchars($_GET['search']) : "Start by creating your first task" ?></p>
                                            <a href="employee_task" class="btn-add-task">
                                                <i class="bi bi-plus-circle"></i> Create Task
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total > 50): ?>
                    <div class="pagination-container">
                        <ul class="pagination-modern">
                            <?= paging($total, "", (!empty($param) ? $param : '')) ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Auto-submit when radio button changes
        $('input[name="search_status"]').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>

<script>
    function toggleDesc(id, btn) {

        const desc = document.getElementById('desc' + id);

        desc.classList.toggle('expanded');
        btn.classList.toggle('expanded');

        btn.querySelector('span').textContent =
            desc.classList.contains('expanded') ?
            'Show Less' :
            'Read More';
    }

    document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".task-desc").forEach(function (desc) {

        const btn = document.getElementById(
            "btn" + desc.id.replace("desc", "")
        );

        if (desc.scrollHeight > desc.clientHeight) {

            desc.classList.add("has-overflow");

            if (btn) {
                btn.style.display = "inline-flex";
            }
        }

    });

});
</script>

<style>
    /* ===== GOOGLE FONTS & ICONS ===== */
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');

    /* ===== CONTAINER ===== */
    .task-list-container {
        background: #f8fafc;
        padding: 1.3rem;
        border-radius: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== SEARCH SECTION ===== */
    .search-section {
        background: #ffffff;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 2rem;
        border: 1px solid #eef2f6;
    }

    .search-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .search-field {
        flex: 1;
        min-width: 200px;
    }

    .input-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon i {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 1.3rem;
    }

    .search-input {
        width: 100%;
        padding: 0.7rem 1rem 0.7rem 2.8rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1.3rem;
        transition: all 0.2s ease;
        background: #fafcff;
        outline: none;
        height: 35px;
    }

    .search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        background: #ffffff;
    }

    .search-button {
        flex: 0 0 auto;
    }

    .btn-search {
        padding: 0.7rem 1.8rem;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        height: 35px;
        cursor: pointer;
    }

    .btn-search:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .status-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-left: auto;
        margin-top: 10px;
    }

    .filter-radio {
        position: relative;
        cursor: pointer;
    }

    .filter-radio input {
        display: none;
    }

    .filter-badge {
        padding: 0.4rem 1.2rem;
        border-radius: 30px;
        font-size: 1.3rem;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-block;
        border: 2px solid transparent;
    }

    .filter-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .filter-badge.in-progress {
        background: #dbeafe;
        color: #1e40af;
    }

    .filter-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .filter-badge.all {
        background: #f1f5f9;
        color: #475569;
    }

    .filter-radio input:checked+.filter-badge {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .filter-radio input:checked+.filter-badge.pending {
        border-color: #d97706;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
    }

    .filter-radio input:checked+.filter-badge.in-progress {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .filter-radio input:checked+.filter-badge.completed {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }

    /* ===== TABLE CONTAINER ===== */
    .table-container {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef2f6;
        overflow: hidden;
    }

    .table-header {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0a1a2b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-title i {
        color: #2563eb;
        font-size: 1.6rem;
    }

    .task-count {
        font-size: 1.3rem;
        font-weight: 400;
        color: #64748b;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-add {
        padding: 0.5rem 1.2rem;
        background: #2563eb;
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        background: #1d4ed8;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* ===== TABLE ===== */
    .task-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 1.3rem;
    }

    .task-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #eef2f6;
    }

    .task-table th {
        padding: 0.8rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #475569;
        font-size: 1.3rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .task-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .task-row:hover {
        background: #fafcff;
    }

    /* ===== TASK ID & DATE ===== */
    .task-id {
        font-weight: 700;
        color: #0a1a2b;
        font-size: 1.3rem;
    }

    .task-date {
        font-size: 1rem;
        color: #94a3b8;
        margin-top: 0.2rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .task-date i {
        font-size: 0.9rem;
    }

    /* ===== ASSIGNEE ===== */
    .assignee-name {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #1e293b;
        font-weight: 500;
    }

    .assignee-name i {
        color: #94a3b8;
        font-size: 1.3rem;
    }

    /* ===== TASK DETAILS ===== */
    .task-details {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }

    .task-title {
        font-weight: 600;
        color: #0a1a2b;
        font-size: 1.3rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    .task-desc {
        max-height: 80px;
        overflow: hidden;
        transition: max-height .35s ease;
        position: relative;
    }

    .task-desc.has-overflow:not(.expanded)::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 35px;
        background: linear-gradient(to bottom,
                rgba(255, 255, 255, 0),
                #fff);
    }

    .task-desc.expanded {
        max-height: 2000px;
    }

    .read-more-btn {
        margin-top: 8px;
        padding: 0;
        border: none;
        background: transparent;
        color: #0d6efd;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .read-more-btn:hover {
        color: #084298;
        text-decoration: none;
    }

    .read-more-btn i {
        transition: transform .3s;
    }

    .read-more-btn.expanded i {
        transform: rotate(180deg);
    }

    .routine-type {
        font-size: 1rem;
        color: #2563eb;
        display: flex;
        align-items: center;
        gap: 4px;
        /* margin-top: 0.2rem; */
    }

    /* ===== BADGES ===== */
    .staging-badge {
        background: #fef3c7;
        color: #92400e;
        padding: 0.15rem 0.6rem;
        border-radius: 30px;
        font-size: .9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .routine-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.15rem 0.6rem;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* ===== STATUS ===== */
    .status-container {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 30px;
        font-size: 1.1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        width: fit-content;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.in-progress {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .test-badge {
        padding: 0.25rem 0.8rem;
        border-radius: 30px;
        font-size: 1.1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        width: fit-content;
    }

    .test-badge.tested {
        background: #d1fae5;
        color: #065f46;
    }

    .test-badge.not-tested {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== ROUTINE INDICATOR ===== */
    .routine-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .routine-indicator.yes {
        color: #059669;
    }

    .routine-indicator.no {
        color: #ef4444;
    }

    .routine-type-small {
        display: block;
        font-size: 1.1rem;
        color: #64748b;
        margin-top: 0.2rem;
        background: #f1f5f9;
        padding: 0.1rem 0.5rem;
        border-radius: 20px;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        align-items: center;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .action-btn i {
        font-size: 1rem;
    }

    .edit-btn {
        color: #2563eb;
        background: #eff6ff;
    }

    .edit-btn:hover {
        background: #2563eb;
        color: white;
        text-decoration: none;
    }

    .delete-btn {
        color: #ef4444;
        background: #fef2f2;
    }

    .delete-btn:hover {
        background: #ef4444;
        color: white;
        text-decoration: none;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
    }

    .empty-state h4 {
        color: #0a1a2b;
        margin: 1rem 0 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }

    .btn-add-task {
        display: inline-block;
        padding: 0.6rem 1.5rem;
        background: #2563eb;
        color: white;
        border-radius: 10px;
        text-decoration: none;
        margin-top: 1rem;
        font-weight: 500;
    }

    .btn-add-task:hover {
        background: #1d4ed8;
        color: white;
        text-decoration: none;
    }

    /* ===== PAGINATION ===== */
    .pagination-container {
        padding: 1.2rem 1.5rem;
        border-top: 1px solid #eef2f6;
        display: flex;
        justify-content: center;
    }

    .pagination-modern {
        display: flex;
        gap: 0.3rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pagination-modern li {
        display: inline;
    }

    .pagination-modern a,
    .pagination-modern span {
        display: inline-block;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
        color: #1e293b;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .pagination-modern a:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }

    .pagination-modern .active span {
        background: #2563eb;
        color: white;
    }

    .pagination-modern .disabled span {
        color: #94a3b8;
        cursor: not-allowed;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .search-grid {
            flex-direction: column;
            align-items: stretch;
        }

        .status-filters {
            margin-left: 0;
            justify-content: center;
        }

        .search-button {
            width: 100%;
        }

        .btn-search {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .task-list-container {
            padding: 0.8rem;
        }

        .search-section {
            padding: 1rem;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
            gap: 0.8rem;
        }

        .task-table {
            font-size: 0.8rem;
        }

        .task-table td {
            padding: 0.6rem;
        }

        .task-table th {
            padding: 0.5rem 0.6rem;
            font-size: 0.7rem;
        }

        .task-title {
            font-size: 0.9rem;
        }

        .action-btn {
            width: 28px;
            height: 28px;
        }

        .action-btn i {
            font-size: 0.85rem;
        }

        .status-filters {
            gap: 0.3rem;
        }

        .filter-badge {
            padding: 0.3rem 0.8rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .task-table thead {
            display: none;
        }

        .task-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #eef2f6;
            border-radius: 12px;
            padding: 0.8rem;
        }

        .task-table tbody td {
            display: block;
            border: none;
            padding: 0.4rem 0;
        }

        .task-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            display: block;
            font-size: 0.7rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
    }
</style>