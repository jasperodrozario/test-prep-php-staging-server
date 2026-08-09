<br>
<form method="post"
    action="<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>"
    enctype="multipart/form-data">

    <input type="hidden"
        name="task_id"
        value="<?php echo $_GET['task_id'] ?? ''; ?>">

    <div class="task-card">

        <div class="task-card-header">
            <i class="fa fa-tasks"></i> Task Information
        </div>

        <div class="task-card-body">

            <div class="row">

                <div class="col-md-6 form-group">
                    <label class="form-label">Assigned By</label>
                    <input type="text"
                        class="form-control"
                        name="assigned_by"
                        value="<?php echo $db_value['assigned_by'] ?? ''; ?>">
                </div>

                <div class="col-md-6 form-group">
                    <label class="form-label">Assigned To</label>
                    <input type="text"
                        class="form-control"
                        name="assigned_to"
                        value="<?php echo $db_value['assigned_to'] ?? ''; ?>">
                </div>

                <div class="col-md-12 form-group">
                    <label class="form-label">Task Title</label>
                    <input type="text"
                        class="form-control"
                        name="title"
                        value="<?php echo $db_value['title'] ?? ''; ?>">
                </div>

                <div class="col-md-12 form-group">
                    <label class="form-label">Description</label>

                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="7"><?php echo $db_value['description'] ?? ''; ?></textarea>

                </div>

            </div>


            <div class="section-title">
                Task Options
            </div>


            <div class="row">

                <div class="col-md-4 form-group">

                    <label class="form-label">
                        Work on Staging
                    </label>

                    <label class="switch">
                        <input type="checkbox"
                            name="on_staging"
                            value="Y"
                            <?php echo ($db_value['on_staging'] ?? '') == 'Y' ? 'checked' : ''; ?>>

                        <span class="slider"></span>
                    </label>

                </div>


                <div class="col-md-4 form-group">

                    <label class="form-label">
                        Mark as Tested
                    </label>

                    <label class="switch">

                        <input type="checkbox"
                            name="tested"
                            value="Y"
                            <?php echo ($db_value['tested'] ?? '') == 'Y' ? 'checked' : ''; ?>>

                        <span class="slider"></span>

                    </label>

                </div>

            </div>


            <div class="section-title">
                Routine Task
            </div>

            <div class="radio-group">

                <label>
                    <input type="radio"
                        name="routine_task"
                        class="routine-task"
                        id="routine_no"
                        value="N"
                        <?= (($db_value['routine_task'] ?? 'N') == 'N') ? 'checked' : ''; ?>>

                    No
                </label>

                <label>

                    <input type="radio"
                        name="routine_task"
                        class="routine-task"
                        id="routine_yes"
                        value="Y"
                        <?= (($db_value['routine_task'] ?? '') == 'Y') ? 'checked' : ''; ?>>

                    Yes

                </label>

            </div>


            <div id="routine_type_row"
                style="<?= (($db_value['routine_task'] ?? '') == 'Y') ? '' : 'display:none;' ?>">

                <br>

                <label class="form-label">
                    Routine Type
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="routine_type"
                    name="routine_type"
                    placeholder="Daily / Weekly / Monthly"
                    value="<?= $db_value['routine_type'] ?? ''; ?>">

            </div>



            <div class="section-title">
                Task Status
            </div>

            <div class="radio-group">

                <label>
                    <input class="status-radio"
                        type="radio"
                        name="status"
                        id="status_pending"
                        value="Pending"
                        <?= ($db_value['status'] ?? 'Pending') == 'Pending' ? 'checked' : ''; ?>>

                    Pending
                </label>

                <label>

                    <input class="status-radio"
                        type="radio"
                        name="status"
                        id="status_progress"
                        value="In Progress"
                        <?= ($db_value['status'] ?? '') == 'In Progress' ? 'checked' : ''; ?>>

                    In Progress

                </label>

                <label>

                    <input class="status-radio"
                        type="radio"
                        name="status"
                        id="status_completed"
                        value="Completed"
                        <?= ($db_value['status'] ?? '') == 'Completed' ? 'checked' : ''; ?>>

                    Completed

                </label>

            </div>

            <br><br>

            <input type="submit" name="SBMT_REG" value="Save Task" class="btn btn-success btn-save">

        </div>
    </div>

</form>

<!-- Load CKEditor -->
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('description', {
        versionCheck: false
    });
</script>

<script>
    function toggleRoutineTask() {

        const isRoutine = document.getElementById('routine_yes').checked;

        document.getElementById('routine_type_row').style.display =
            isRoutine ? '' : 'none';

        if (isRoutine) {

            document.getElementById('status_progress').checked = true;

            document.querySelectorAll('.status-radio').forEach(function(el) {
                el.disabled = true;
            });

            document.getElementById('status_progress').disabled = false;

        } else {

            document.querySelectorAll('.status-radio').forEach(function(el) {
                el.disabled = false;
            });

        }
    }

    document.querySelectorAll('.routine-task').forEach(function(el) {
        el.addEventListener('change', toggleRoutineTask);
    });

    toggleRoutineTask();
</script>

<style>
    .task-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .task-card-header {
        background: #2c7be5;
        color: #fff;
        padding: 15px 20px;
        font-size: 20px;
        font-weight: 600;
    }

    .task-card-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #444;
    }

    .form-control {
        height: 42px;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: none;
    }

    textarea.form-control {
        height: auto;
    }

    .radio-group,
    .checkbox-group {
        display: flex;
        gap: 25px;
        align-items: center;
        flex-wrap: wrap;
    }

    .radio-group label,
    .checkbox-group label {
        font-weight: 500;
        margin: 0;
    }

    .section-title {
        font-size: 17px;
        font-weight: 600;
        color: #2c7be5;
        margin: 30px 0 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
    }

    .btn-save {
        padding: 10px 35px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 54px;
        height: 28px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #ccc;
        transition: .3s;
        border-radius: 50px;
    }

    .slider:before {
        content: "";
        position: absolute;
        width: 22px;
        height: 22px;
        left: 3px;
        top: 3px;
        background: white;
        transition: .3s;
        border-radius: 50%;
    }

    .switch input:checked+.slider {
        background: #28a745;
    }

    .switch input:checked+.slider:before {
        transform: translateX(26px);
    }

    @media(max-width:768px) {

        .radio-group,
        .checkbox-group {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>