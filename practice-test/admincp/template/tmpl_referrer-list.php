<br>

<form method='post' action='referrer-list'>
    <table width="100%" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
        <tr>
            <td width=60%>
                <input type='text' name='search_input' class='form-control' placeholder='Enter Search Value'>
            </td>
            <td width=20%>
                <input type='submit' class='btn btn-success' name='search' value='Search' style='width: 100%;'>
            </td>
            <td width="20%">
                <label style="font-size:13px; cursor:pointer;">
                    <input type="checkbox" name="due_only" value="1"
                    onchange="this.form.submit()"
                    <?= (isset($_POST['due_only'])) ? 'checked' : '' ?>>
                    Show Due Refferers
                </label>
            </td>
        </tr>
    </table>
</form>

<br>


<div style="width:100%; overflow-x:auto;">
    <table width="100%" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
        <thead>
            <tr>
                <td align="center"><b>Sr</b></td>
                <td align="center"><b>User Id</b></td>
                <td align="center"><b>Name</b></td>
                <td align="center"><b>Payout Info</b></td>
                <td align="center"><b>Ref. Code</b></td>
                <td align="center"><b>Total Referral</b></td>
                <td align="center"><b>Total Due($)</b></td>
                <td align="center" width=15%><b>Action</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $due_only = isset($_POST['due_only']);
            $i = 0;
            foreach ($db_list as $d) {
                $due = $val_due[$d['user_id']];

                // filter condition
                if ($due_only && $due <= 0) {
                    continue;
                }

                $i++;

                if ($d['user_type'] == "Student") {
                    $user_type = "Student";
                }
                if ($d['user_type'] == "Referrer") {
                    $user_type = "Referrer";
                }
                if ($d['user_type'] == "Tutor") {
                    $user_type = "Tutor";
                }
            ?>
                <tr>
                    <td align="left"><?php echo $i; ?></td>
                    <td align="left"><?php echo $d['user_id']; ?></td>
                    <td>

                        <div style="font-weight:600; color:#111827;">
                            <?= ucwords($d['first_name'] . " " . $d['last_name']); ?>
                        </div>

                        <div style="font-size:12px; color:#6b7280; margin-top:3px;">
                            <?= $d['email'] ?>
                        </div>

                        <div style="font-size:12px; color:#6b7280; margin-top:3px;">
                            <?= $d['mobile']; ?>
                        </div>
                    </td>

                    <td>
                        <div style="font-weight:600; color:#111827;">
                            <?= ($d['payment_info']) ? $d['payment_info'] : '-'; ?>
                        </div>
                        
                        <div style="font-size:12px; color:#6b7280; margin-top:3px;">
                            <?= ($d['payment_method']) ? ucwords($d['payment_method']) : '-'; ?>
                        </div>
                    </td>

                    <td class="copy-text" data-referral="<?php echo str_replace("/practice-test", "", $configs['vpath']) . "subscribe/referral/subscription/" . $d['referral_code']; ?>">
                        <span class="copy-link">
                            <?php echo str_replace("/practice-test", "", $configs['vpath']) . "subscribe/referral/subscription/" . $d['referral_code']; ?>
                        </span>
                        <span class="copy-status"></span>
                    </td>
                    <td><?php echo $val_total[$d['user_id']]; ?></td>
                    
                    <td style="<?= ($val_due[$d['user_id']] > 0) ? 'background:#fee2e2; color:#b91c1c; font-weight:600;' : '' ?>">
                        <?php echo number_format($val_due[$d['user_id']], 2); ?>
                    </td>

                    <td>
                        <ul style='list-style-type:none;margin-left:0;padding-left:0'>

                            <li> <i class="fa fa-pencil-square-o" aria-hidden="true"></i><a href="referrer-payment?user_id=<?= $d['user_id']; ?>" class="open-modal"> Pay Due </a></li>

                            <!-- <li> <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                <a href="<?= $configs['admin_vpath'] ?>paypal-update?user_id=<?php echo $d['user_id']; ?>" data-toggle="modal" data-target="#myModal">Add Paypal</a>
                            </li> -->

                            <li> <i class="fa fa-bar-chart" aria-hidden="true"></i> <a href="<?= $configs['admin_vpath'] ?>referrer-payout?user_id=<?php echo $d['user_id']; ?>">Payment History</a> </li>

                        </ul>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
if ($total > 100) {
    echo "<ul class='pagination pull-right'>" . paging($total, 100, $param) . "</ul>";
}
?>
<!-- Modal HTML -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog custom-modal">
        <button type="button" class="close" data-dismiss="modal" onClick='window.parent.location.reload()'><i class="fa fa-times" aria-hidden="true"></i></button>
        <div class="modal-content">
            <!-- Content will be loaded here from "remots" file -->
        </div>
    </div>
</div>

<script>
    $('#myModal').on('hidden.bs.modal', function() {
        $(this).find('.modal-content').html('');
    });

    $("#ckbCheckAll").click(function() {
        $(".checkBoxClass").prop('checked', $(this).prop('checked'));
    });

    $(document).on('click', '.open-modal', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $('#myModal .modal-content').load(url, function() {
            $('#myModal').modal('show');
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var copyTextElements = document.querySelectorAll('.copy-text');
        copyTextElements.forEach(function(element) {
            element.addEventListener('click', function() {
                var referralCode = this.getAttribute('data-referral');
                copyToClipboard(referralCode, this);
            });

            element.addEventListener('mouseenter', function() {
                this.classList.add('hovered');
            });

            element.addEventListener('mouseleave', function() {
                this.classList.remove('hovered');
            });
        });

        function copyToClipboard(text, element) {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            var copyStatus = element.querySelector('.copy-status');
            copyStatus.textContent = 'Copied!';

            setTimeout(function() {
                copyStatus.textContent = '';
            }, 2000); // Remove the status message after 2 seconds
        }
    });
</script>

<style>
    .copy-text:hover {
        cursor: pointer;
        text-decoration: underline;
    }

    .copy-text.hovered {
        text-decoration: none;
    }

    .copy-status {
        position: absolute;
        top: 50;
        right: 50;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 2px 5px;
        font-size: 12px;
    }
</style>