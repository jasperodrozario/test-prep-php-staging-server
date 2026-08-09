<br>
<form method='get' action="<?= $configs['admin_vpath'] ?>square-plan-variations">
    <div class='row'>
        <div class='col-md-6'>
            <input type='text' class='form-control' name='key' placeholder='Search By Session ID / Plan ID / Variation ID' autocomplete="off">
        </div>
        <div class='col-md-2'>
            <input type='submit' style='width:100%' class='btn btn-success pull-right' value='Search'>
        </div>
        <div class='col-md-4'>
            <div class='row'>
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="radio" name="env_name" id="env_production" value="production"
                        <?= isset($_GET['env_name']) && $_GET['env_name'] == 'production' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="env_production">Production</label>
                </div>
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="radio" name="env_name" id="env_sandbox" value="sandbox"
                        <?= isset($_GET['env_name']) && $_GET['env_name'] == 'sandbox' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="env_sandbox">Sandbox</label>
                </div>
                <div class="col-md-2 form-check">
                    <input class="form-check-input" type="radio" name="env_name" id="env_all" value=""
                        <?= !isset($_GET['env_name']) || $_GET['env_name'] == '' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="env_all">All</label>
                </div>
            </div>
        </div>
    </div>
</form>

<br>

<div class="table-responsive" id="scrollable-table">
    <table class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="10%">Session ID</th>
                <th>Env Name</th>
                <th>Square Plan ID</th>
                <th>Variation ID</th>
                <th>Environment</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($db_list as $d) { ?>
                <tr>

                    <td><?php echo $d['id']; ?></td>

                    <td><?php echo $d['session_id']; ?></td>

                    <td><?php echo $d['env_name']; ?></td>

                    <td>
                        <small><?php echo $d['square_plan_id']; ?></small>
                    </td>

                    <td>
                        <small><?php echo $d['plan_variation_id']; ?></small>
                    </td>

                    <td>
                        <span class="badge badge-<?php echo ($d['environment'] == 'production') ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($d['environment']); ?>
                        </span>
                    </td>

                    <td align="center">
                        <?php echo "<a  href='transaction-edit-form?action=plan-variation-edit&id=" . $d['id'] . "'><u>Edit</u></a> | "; ?>
                        <?php
                        echo "<a href=\"javascript:if (confirm('Are you sure you want to delete this record?')){window.location.href='square-plan-variations?del_id=" . $d['id'] . "'}\"><u>Delete</u></a>";
                        ?>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
if ($total > 100) {
    echo "<ul class='pagination pull-right'>" . paging($total, $param) . "</ul>";
}
?>

<script>
    $(document).ready(function() {
        // Auto-submit when radio button changes
        $('input[name="env_name"]').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>

<style>
#scrollable-table {
    overflow-x: auto;
    white-space: nowrap;
}

.badge-success {
    background: #28a745;
    color: #fff;
    padding: 5px 8px;
    border-radius: 4px;
}

.badge-warning {
    background: #ffc107;
    color: #000;
    padding: 5px 8px;
    border-radius: 4px;
}
</style>
