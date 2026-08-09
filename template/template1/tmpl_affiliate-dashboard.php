<main>
    <div id="copyToast" class="copy-toast"></div>
    <div class="page-title-area bg-11 ">
        <div class="container">
            <div class="page-title-content">
                <h2>Affiliate Dashboard</h2>
            </div>
        </div>
    </div>

    <div class="blog-column-one-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="widget-sidebar">

                        <div class="sidebar-widget categories">
                            <h3>Dasboard</h3>
                            <ul>
                                <li>
                                    <a href="<?= $configs['vpath'] ?>affiliate-dashboard/overview" <? if (!$_GET['action'] || $_GET['action'] == "overview") {
                                                                                                        echo "active";
                                                                                                    } ?>>Overview</a>
                                </li>
                                <li>
                                    <a href="<?= $configs['vpath'] ?>affiliate-dashboard/profile" <? if ($_GET['action'] == "profile") {
                                                                                                        echo "active";
                                                                                                    } ?>>My Profile</a>
                                </li>
                                <li>
                                    <a href="<?= $configs['vpath'] ?>affiliate-dashboard/referral-student" <? if ($_GET['action'] == "referral-student") {
                                                                                                                echo "active";
                                                                                                            } ?>>Referrals</a>
                                </li>
                                <!--<li>
                            <a href="<?= $configs['vpath'] ?>affiliate-dashboard/earning" <? if ($_GET['action'] == "earning") {
                                                                                                echo "active";
                                                                                            } ?>>My Earning</a>
                            </li>-->
                                <li>
                                    <a href="<?= $configs['vpath'] ?>affiliate-dashboard/payment" <? if ($_GET['action'] == "payment") {
                                                                                                        echo "active";
                                                                                                    } ?>>My Payment</a>
                                </li>
                                <!-- <li>
                                    <a href="<?= $configs['vpath'] ?>affiliate-dashboard/faq" <? if ($_GET['action'] == "tutorial") {
                                                                                                    echo "active";
                                                                                                } ?>>Faq</a>
                                </li> -->

                                <li>
                                    <a href="<?= $configs['vpath'] ?>logout">Logout</a>
                                </li>
                            </ul>
                        </div>

                    </div>

                </div>

                <div class="col-lg-8">
                    <div class="blog-column-ome">
                        <div class="row single-news">

                            <?php
                            switch ($_GET['action']) {

                                case "profile":
                            ?>
                                    <div class="news-content">


                                        <div id='response'></div>
                                        <div class="col-lg-12 col-sm-12">
                                            <h3>Update Profile</h3>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <form method="post" id="form">

                                                <input type="hidden" name="action" value="update_profile">
                                                <input type="hidden" name="user_type" value="<?= $db_value['user_type']; ?>">

                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="<?= $db_value['email']; ?>" disabled>
                                                </div>

                                                <div class="form-group">
                                                    <label>Full Name</label>
                                                    <input type="text" name="first_name" class="form-control" required
                                                        value="<?= $db_value['first_name']; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" name="mobile" maxlength="10" class="form-control" required
                                                        value="<?= $db_value['mobile']; ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label>Payment Method</label>
                                                    <select name="payment_method" class="form-control form-control-lg" required>
                                                        <option value="">Select a method</option>

                                                        <option value="paypal"
                                                            <?= (isset($db_value['payment_method']) && $db_value['payment_method'] == 'paypal') ? 'selected' : '' ?>>
                                                            PayPal
                                                        </option>

                                                        <option value="zelle"
                                                            <?= (isset($db_value['payment_method']) && $db_value['payment_method'] == 'zelle') ? 'selected' : '' ?>>
                                                            Zelle
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="font-weight-semibold">Payment Details</label>
                                                    <input type="text"
                                                        name="payment_info"
                                                        class="form-control form-control-lg"
                                                        placeholder="Email / Phone / Account Number"
                                                        required
                                                        value="<?= isset($db_value['payment_info']) ? $db_value['payment_info'] : '' ?>">
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" class="default-btn member-type">Update</button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                <?
                                    break;
                                case "referral-student":
                                ?>
                                    <div class="news-content">
                                        <div class="col-lg-12 col-sm-12">
                                            <h3>Referral Students (<?= $total ?>)</h3>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
                                                <thead>
                                                    <tr>
                                                        <td width="6%" align="center"><b>ID</b></td>
                                                        <td align="center"><b>Name</b></td>
                                                        <!-- <td align="center"><b>Subscription Type</b></td> -->
                                                        <td align="center"><b>Reg. Date</b></td>
                                                        <td align="center"><b>Status</b></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($db_list as $d) {
                                                        // if (in_array($d['user_id'], $referral_list)) {
                                                        //     $paid = "Paid Member";
                                                        // } else {
                                                        //     $paid = "<font color=red>Free Member</font>";
                                                        // }
                                                        if ($d['activation_status'] == "Y") {
                                                            $d['activation_status'] = "Approved";
                                                        } else {
                                                            $d['activation_status'] = "<font color=red>Pending</font>";
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td align="left"><?= $d['user_id']; ?></td>
                                                            <td><?php echo ucwords($d['first_name'] . " " . $d['last_name']); ?></td>
                                                            <!-- <td align="center"><?php echo $paid; ?></td> -->
                                                            <td align="center"><?php echo showdate($d['reg_date']); ?></td>
                                                            <td align="center"><?php echo $d['activation_status']; ?></td>
                                                        </tr>
                                                    <?php
                                                    }

                                                    ?>
                                                </tbody>
                                            </table>
                                            <? if ($total > 20) {
                                                echo "<ul class='pagination'>" . paging($total) . "</ul>";
                                            } ?>
                                        </div>
                                    </div>
                                <?
                                    break;
                                case "earning":
                                ?>
                                    <div class="news-content">
                                        <div class="col-lg-12 col-sm-12">
                                            <h3>Referral Income ($<?= $total_earning ?>)</h3>
                                        </div>
                                        <div class="col-lg-12 col-sm-12">
                                            <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
                                                <thead>
                                                    <tr>
                                                        <td width="15%" align="left"><b>Sr. No</b></td>
                                                        <td align="left"><b>Month/Year</b></td>
                                                        <td align="center"><b>Total Join</b></td>
                                                        <td align="right"><b>Earning</b></td>

                                                        <td width="10%" align="center"><b>Action</b></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $j = 0;
                                                    foreach ($db_list as $key => $val) {
                                                        $j++;
                                                    ?>
                                                        <tr>
                                                            <td align="left"><?= $j; ?></td>
                                                            <td><?php echo str_replace("-", "/", $key); ?></td>
                                                            <td align="center"><?php echo number_format($val['student'], 2); ?></td>
                                                            <td align="right">$<?php echo number_format($val['earning'], 2); ?></td>
                                                            <td align="center"><a href=''>Payment</a></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <? if ($total > 20) {
                                                echo "<ul class='pagination'>" . paging($total) . "</ul>";
                                            } ?>
                                        </div>
                                    </div>
                                <?
                                    break;
                                case "faq":
                                ?>
                                    <div class="news-content">


                                        <div class='row'>
                                            <div class="col-lg-12 col-sm-12">
                                                <h3 class='news-title'>Frequently Asked Questions</h3>
                                            </div>

                                            <section class="faq-area">


                                                <div class="faq-accordion">
                                                    <ul class="accordion">
                                                        <?php
                                                        $i = 0;
                                                        foreach ($faq_list as $d) {
                                                        ?>
                                                            <li class="accordion-item">
                                                                <a class="accordion-title active" href="javascript:void(0)">
                                                                    <i class="bx bx-plus"></i>
                                                                    <?= $d['question']; ?>
                                                                </a>
                                                                <div class="accordion-content <? if (!$i) {
                                                                                                    echo "show";
                                                                                                } ?>">
                                                                    <p><?= html_entity_decode($d['answers']); ?></p>
                                                                </div>
                                                            </li>
                                                        <?
                                                            $i++;
                                                        }

                                                        ?>
                                                    </ul>

                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                <?

                                    break;
                                case "payment":
                                ?>
                                    <div class="news-content">


                                        <div class='row'>
                                            <div class="col-lg-12 col-sm-12">
                                                <h3 class='news-title'>Payment</h3>
                                            </div>
                                            <div class="col-lg-12 col-sm-12">
                                                <table width="100%" cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped" align="center">
                                                    <thead>
                                                        <tr>
                                                            <td width="2%" align="center"><b>Sr.No</b></td>
                                                            <td width="10%" align="center"><b>Date</b></td>
                                                            <td width="15%" align="center"><b>Payment Method</b></td>
                                                            <td width="20%" align="center"><b>Amount($)</b></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 0;
                                                        foreach ($db_list as $d) {
                                                            $i++;

                                                        ?>
                                                            <tr>
                                                                <td align="left"><?php echo $i; ?></td>
                                                                <td align="left"><?= showdate($d['date']); ?></td>
                                                                <td align="left"><?php echo $d["payment_method"]; ?></td>
                                                                <td align="left">$<?php echo number_format($d["amount"], 2); ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                <?

                                    break;
                                default:
                                ?>

                                    <div class="news-content">


                                        <div class='row'>
                                            <div class="col-lg-12 col-sm-12">
                                                <h3 class='news-title'>Overview</h3>
                                            </div>
                                            <div class="col-lg-12 col-sm-12">

                                                <div class="affiliate-card">

                                                    <h3 class="affiliate-title">Affiliate URL</h3>

                                                        <div class="affiliate-desc">
                                                            <strong>🔹 Start Earning</strong>
                                                            <p>
                                                                Share your affiliate link to invite users and earn rewards. Just copy the link below and share it via social media, email, or messaging apps.
                                                                <br>
                                                                <span class="text-muted">💡 The more people sign up using your link, the more you earn.</span>
                                                            </p>
                                                        </div>

                                                    <!-- Subscription URL -->
                                                    <div class="affiliate-row">
                                                        <div class="input-group affiliate-group">
                                                            <input type="text"
                                                                id="url1"
                                                                class="form-control"
                                                                value="<?= $configs['vpath'] . "subscribe/referral/subscription/" . $db_value['referral_code']; ?>"
                                                                readonly>

                                                            <button onclick="copyToClipboard('#url1', 'Subscription link copied!')"
                                                                class="btn btn-outline-success">
                                                                <i class="fas fa-copy"></i> Copy
                                                            </button>
                                                        </div>
                                                    </div>



                                                    <!-- Tutoring URL -->
                                                    <!-- <div class="affiliate-row">
                                                        <div class="affiliate-info">
                                                            <span class="affiliate-label">Tutoring</span>
                                                            <div class="affiliate-url" id="url2">
                                                                https://asvab-tutoring-zeta.vercel.app?ref_type=tutoring&ref_code=<?= $db_value['referral_code']; ?>
                                                                <?
                                                                // $configs['vpath'] . "subscribe/referral/tutoring/" . $db_value['referral_code']; 
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <button onclick="copyToClipboard('#url2', 'Tutoring link copied!')"
                                                                class="btn btn-success affiliate-btn">
                                                            Copy
                                                        </button>
                                                    </div> -->

                                                </div>

                                                <!-- Toast -->
                                                <div id="copyToast" class="copy-toast">Copied!</div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="single-categories">
                                                    <div class="categories-content-wrap">
                                                        <div class="categories-content">

                                                            <h3>Total Referral Join</h3>

                                                            <p class='amount'><?= $total_student; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-sm-6">
                                                <div class="single-categories">
                                                    <div class="categories-content-wrap">
                                                        <div class="categories-content">

                                                            <h3>Total Earnings</h3>

                                                            <p class='amount'>$<?= number_format($total_earning, 2); ?> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-sm-6">
                                                <div class="single-categories">
                                                    <div class="categories-content-wrap">
                                                        <div class="categories-content">

                                                            <h3>Total Paid</h3>

                                                            <p class='amount'>$<?= number_format($total_paid, 2); ?> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="single-categories">
                                                    <div class="categories-content-wrap">
                                                        <div class="categories-content">

                                                            <h3>Total Due</h3>

                                                            <p class='amount'>$<?= str_replace("-", "", number_format($total_due, 2)); ?> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                            <?
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="paymentInfoModal" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content payment-modal">

                <!-- Header -->
                <div class="modal-header border-0">
                    <div>
                        <h5 class="modal-title mb-0">💳 Payment Information</h5>
                        <small class="text-muted">Add your payout details to receive payments.</small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <hr class="my-0">

                <!-- Body -->
                <div class="modal-body px-4 py-4">
                    <form method="post" id="paymentForm">

                        <input type="hidden" name="action" value="update_payment_info">

                        <!-- Payment Method -->
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold">Payment Method</label>
                            <select name="payment_method" class="form-control form-control-lg" required>
                                <option value="">Select a method</option>
                                <option value="paypal">PayPal</option>
                                <option value="zelle">Zelle</option>
                            </select>
                        </div>

                        <!-- Payment Info -->
                        <div class="form-group mb-4">
                            <label class="font-weight-semibold">Payment Details</label>
                            <input type="text"
                                name="payment_info"
                                class="form-control form-control-lg"
                                placeholder="Email / Phone / Account Number"
                                required>
                            <small class="text-muted">Make sure the details are correct for successful payment.</small>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                Save
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

</main>

<script>
    var paymentInfo = <?= json_encode($payment_info); ?>;

    $(document).ready(function() {
        if (!paymentInfo) {
            $('#paymentInfoModal').modal('show');
        }
    });
</script>

<script>
    $("#paymentForm").submit(function(event) {
        event.preventDefault();

        // confirmation
        var confirmAction = confirm("Are you sure you want to save payment info?");
        if (!confirmAction) {
            return false;
        }

        $('#response').html("<img src='<?= $configs['vpath']; ?>img/loaderIcon.gif'>");

        $.ajax({
                dataType: "json",
                type: "POST",
                url: "<?= $configs['vpath'] ?>ajax-home",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false
            })
            .done(function(data) {
                $("#response").html(data.msg);

                // optional: close modal after success
                $('#paymentInfoModal').modal('hide');

                // optional: reload to reflect changes
                setTimeout(function() {
                    location.reload();
                }, 1000);
            });
    });
</script>

<script>
    function copyToClipboard(selector, message = "Copied!") {
        const element = document.querySelector(selector);

        const text = element.value || element.innerText;

        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById("copyToast");

            toast.innerText = message;
            toast.classList.add("show");

            setTimeout(() => {
                toast.classList.remove("show");
            }, 1200);
        });
    }
</script>

<style>
    .payment-modal {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-title {
        font-weight: 600;
    }

    .form-control-lg {
        border-radius: 8px;
    }

    .btn-success {
        border-radius: 8px;
    }
</style>

<style>
    .affiliate-desc {
        background: #f9fafb;
        border-left: 3px solid #28a745;
        padding: 12px 14px;
        margin-bottom: 15px;
        border-radius: 6px;
        font-size: 14px;
        line-height: 1.5;
    }

    .affiliate-group .form-control,
    .affiliate-group .btn {
        height: 42px; /* consistent height */
    }

    .input-group .btn {
        margin: 0;
        padding-top: 0;
        padding-bottom: 0;
        height: 42px; /* match input */
        display: flex;
        align-items: center;
    }

    .affiliate-group .form-control {
        border-right: 0;
    }

    .affiliate-group .btn {
        border-left: 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Fix spacing issue */
    .input-group {
        flex-wrap: nowrap;
    }

    .copy-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #111;
        color: #fff;
        padding: 10px 14px;
        font-size: 13px;
        border-radius: 6px;
        opacity: 0;
        transform: translateY(-10px);
        transition: 0.25s ease;
        z-index: 99999;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .copy-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<style>
    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
    }
</style>