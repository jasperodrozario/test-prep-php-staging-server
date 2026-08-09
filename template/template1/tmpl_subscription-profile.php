<main>

  <div class="userprofile_wrapper">
    <div class="container">
      <div class="userprofile-body midle-conarea mr-t88">
        <div class="col-lg-12 col-md-12">

          <div id="response">
            <?php if (!empty($success_message)) { ?>
              <div class="alert alert-success">
                <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php } ?>

            <?php if (!empty($error_message)) { ?>
              <div class="alert alert-danger">
                <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
              </div>
            <?php } ?>
          </div>

          <div class="panel dashbord-panel with-nav-tabs panel-default">
            <div class="panel-heading">Subscription Information

            </div>
            <div class="panel-body">

              <?php
              $inactive_user = getTotalRow(
                "user",
                array(
                  "user_id" => $_SESSION['user_id'],
                  "activation_status" => "N"
                )
              );

              function to_us_long_date($value)
              {
                $value = trim((string)$value);
                if ($value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
                  return '';
                }

                $v = str_replace('T', ' ', $value);
                $v = preg_replace('/Z$/', '', $v);
                $ts = strtotime($v);

                if ($ts === false) {
                  return $value;
                }

                return date('F j, Y', $ts);
              }

              $sub_start_us  = to_us_long_date($db_value['subscription_start_date'] ?? '');
              $expire_us     = to_us_long_date($db_value['expire_date'] ?? '');
              $next_bill_us  = to_us_long_date($db_value['next_billing_date'] ?? '');
              $cancel_eff_us = to_us_long_date($db_value['cancel_effective_date'] ?? '');

              $square_status_raw = (string)($db_value['status'] ?? '');
              $square_status     = strtolower(trim($square_status_raw));

              $cancel_status_raw = (string)($db_value['cancel_status'] ?? 'NONE');
              $cancel_status     = strtolower(trim($cancel_status_raw));

              $is_pending_cancel = (
                strpos($cancel_status, 'pending') !== false ||
                strpos($cancel_status, 'cancel_pending') !== false
              );

              $is_canceled_or_inactive = (
                strpos($square_status, 'canceled') !== false ||
                strpos($square_status, 'cancelled') !== false ||
                strpos($square_status, 'inactive') !== false ||
                strpos($square_status, 'expired') !== false ||
                strpos($square_status, 'ended') !== false
              );

              $is_active_square = (strpos($square_status, 'active') !== false);

              $display_status = $square_status_raw;
              $display_status_key = $square_status;

              if ($is_pending_cancel) {
                $display_status = 'CANCEL_PENDING';
                $display_status_key = 'cancel_pending';
              }

              $color = '#000';
              if (strpos($display_status_key, 'active') !== false) {
                $color = 'green';
              } elseif (
                strpos($display_status_key, 'pause') !== false ||
                strpos($display_status_key, 'pending') !== false
              ) {
                $color = 'orange';
              } elseif (
                strpos($display_status_key, 'canceled') !== false ||
                strpos($display_status_key, 'inactive') !== false
              ) {
                $color = 'red';
              }

              $next_billing_raw  = trim((string)($db_value['next_billing_date'] ?? ''));
              $show_next_billing = (!$is_pending_cancel && !$is_canceled_or_inactive && $next_billing_raw !== '');

              $subscribe_again_url = $configs['nextjs_base_url']
                . '/subscribe-again?user_id=' . (int)($user_id ?? 0)
                . '&auth_token=' . urlencode((string)($auth_token ?? ''));

              $buy_more_sessions_url = $configs['nextjs_base_url']
                . '/buy-more-sessions?user_id=' . (int)($user_id ?? 0)
                . '&auth_token=' . urlencode((string)($auth_token ?? ''));
              ?>


              <div class="form-group row">
                <label class="col-md-3 col-form-label">Name</label>
                <div class="col-md-9">
                  <?= htmlspecialchars(trim(($db_value['first_name'] ?? '') . ' ' . ($db_value['last_name'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Email</label>
                <div class="col-md-9">
                  <?= htmlspecialchars(($db_value['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                </div>
              </div>

              <?php
              $status_check = isset($square_status) ? strtolower(trim($square_status)) : '';

              if ($status_check !== 'free'  && $status_check !== 'expired') {
              ?>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Subscription Start Date</label>
                  <div class="col-md-9">
                    <b><?= htmlspecialchars($sub_start_us, ENT_QUOTES, 'UTF-8'); ?></b>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Subscription Expire Date</label>
                  <div class="col-md-9">
                    <b><?= htmlspecialchars($expire_us, ENT_QUOTES, 'UTF-8'); ?></b>
                  </div>
                </div>
              <?php } ?>

              <?php if ($show_next_billing) { ?>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Next Billing Date</label>
                  <div class="col-md-9">
                    <b><?= htmlspecialchars($next_bill_us, ENT_QUOTES, 'UTF-8'); ?></b>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Subscription Status</label>
                <div class="col-md-9">
                  <span style="color: <?= htmlspecialchars($color, ENT_QUOTES, 'UTF-8'); ?>; font-weight:700;">
                    <?php
                    $cancelStatusUpper = strtoupper(trim($cancel_status_raw ?? ''));

                    if ($cancelStatusUpper === 'CANCEL_PENDING') {
                      $pretty = 'Cancellation Scheduled';
                      if (!empty($cancel_eff_us)) {
                        $pretty .= ' on ' . $cancel_eff_us;
                      }
                    } else {
                      $pretty = ucwords(strtolower(trim($display_status)));
                    }

                    echo htmlspecialchars($pretty, ENT_QUOTES, 'UTF-8');
                    ?>
                    <!--
                    </span>
                   <?php if ($inactive_user) { ?>
                     <span class="text-danger">You are not an active subscriber yet or your subscription has expired. Please subscribe to access all features.</span>
                   <?php } ?>
                   -->
                </div>
              </div>

              <?php if ($is_pending_cancel) { ?>

                <div class="form-group row">
                  <div class="col-md-3"></div>
                  <div class="col-md-9">
                    <p style="color:#d9534f; font-weight:500; margin-bottom:10px;">
                      Cancellation of your subscription will be effective at
                      <b><?= htmlspecialchars($cancel_eff_us, ENT_QUOTES, 'UTF-8'); ?></b>.
                      You can resume your subscription before this date.
                    </p>

                    <form method="post" id="resume_form" style="display:inline;">
                      <input type="hidden" name="resume_subscription" value="resume">

                      <button type="submit" id="resume_btn" class="btn btn-success" style="margin-top:4px;">
                        Resume Subscription
                      </button>

                      <span id="resume_wait" style="display:none; margin-left:10px; font-weight:600;">
                        Resuming... please wait
                      </span>
                    </form>
                  </div>
                </div>

              <?php } elseif ($is_canceled_or_inactive || $square_status === 'Expired') { ?>

                <div class="form-group row">
                  <div class="col-md-3"></div>
                  <div class="col-md-9">
                    <button
                      type="button"
                      class="btn btn-success"
                      style="margin-top:4px;"
                      onclick="window.location.href='<?= htmlspecialchars($subscribe_again_url, ENT_QUOTES, 'UTF-8'); ?>';">
                      Subscribe Again
                    </button>
                  </div>
                </div>

                <div class="alert alert-info" style="margin-top: 20px;">
                  <strong>No Active Subscription</strong>
                  <p style="margin: 8px 0 5px;">
                    Subscribe to access your course materials.
                  </p>
                  <p style="margin: 0;">
                    If you have a tutor from ASVAB Tutoring, all course materials are
                    <strong>free for 6 months</strong>.
                  </p>
                </div>

              <?php }  ?>

              <?php if ($is_active_square && !$is_pending_cancel && !$is_canceled_or_inactive) { ?>
                <div class="form-group row">
                  <div class="col-md-3"></div>
                  <div class="col-md-9">
                    <a
                      class="btn btn-warning"
                      href="javascript:void(0);"
                      style="width:200px; margin-top:10px;"
                      onclick="openCancelModal();">
                      Cancel Subscription
                    </a>
                  </div>
                </div>
              <?php } ?>

              <?php if (ucwords(strtolower(trim($display_status))) === "Free") {
              ?>
                <div class="form-group row">
                  <div class="col-md-3"></div>
                  <div class="col-md-9">
                    <button
                      type="button"
                      class="btn btn-success"
                      style="margin-top:4px;"
                      onclick="window.location.href='<?= htmlspecialchars($buy_more_sessions_url, ENT_QUOTES, 'UTF-8'); ?>';">
                      Buy More Sessions
                    </button>
                  </div>
                </div>
              <?php } ?>



              <?php
              /*
  echo '<pre>';
  // $user_id = $_SESSION['user_id'] ?? null;
  // var_dump($_POST);
  //var_dump($auth_token);
  //echo "Cancel status: ".$cancel_status;
  //var_dump($user_id); 
  echo '</pre>';
  */
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="cancelModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
      <h4>Cancel Subscription</h4>

      <form method="post" id="cancel_subs">
        <textarea
          name="cancel_reason"
          placeholder="Please share your reason for cancellation..."
          required></textarea>

        <input type="hidden" name="cancel_subscription" value="cancel">

        <div class="modal-buttons">
          <button type="button" class="btn btn-secondary" onclick="closeCancelModal()">Close</button>
          <button type="submit" class="btn btn-danger" id="cancel_btn">Submit</button>
          <span id="cancel_wait" style="display:none; margin-left:10px; font-weight:600;">
            Submitting... please wait
          </span>
        </div>
      </form>
    </div>
  </div>
</main>

<script>
  function openCancelModal() {
    var el = document.getElementById("cancelModal");
    if (el) el.style.display = "flex";
  }

  function closeCancelModal() {
    var el = document.getElementById("cancelModal");
    if (el) el.style.display = "none";
  }

  window.addEventListener("click", function(event) {
    var modal = document.getElementById("cancelModal");
    if (modal && event.target === modal) {
      modal.style.display = "none";
    }
  });

  (function() {
    var rf = document.getElementById("resume_form");
    if (rf) {
      rf.addEventListener("submit", function() {
        var btn = document.getElementById("resume_btn");
        var wait = document.getElementById("resume_wait");
        if (btn) btn.disabled = true;
        if (wait) wait.style.display = "inline";
      });
    }

    var cf = document.getElementById("cancel_subs");
    if (cf) {
      cf.addEventListener("submit", function() {
        var btn = document.getElementById("cancel_btn");
        var wait = document.getElementById("cancel_wait");
        if (btn) btn.disabled = true;
        if (wait) wait.style.display = "inline";
      });
    }
  })();
</script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>