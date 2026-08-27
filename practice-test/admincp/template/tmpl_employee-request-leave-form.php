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
    --warning: #B4670A;
    --warning-light: #FCF0DF;
    --radius: 10px;
  }

  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text-primary);
    -webkit-font-smoothing: antialiased;
  }

  .mono {
    font-family: 'JetBrains Mono', monospace;
  }

  .wrap {
    max-width: 820px;
    margin: 0 auto;
    padding: 32px 40px 80px;
  }

  .crumb {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 6px;
  }

  .crumb span {
    color: var(--text-secondary);
  }

  .header {
    margin-bottom: 24px;
  }

  h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 4px;
    letter-spacing: -0.01em;
  }

  .subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
  }

  .balance-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 22px;
  }

  .balance-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
  }

  .balance-card .type {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
  }

  .balance-card .amt {
    font-size: 19px;
    font-weight: 600;
  }

  .balance-card .amt span {
    font-size: 12.5px;
    font-weight: 500;
    color: var(--text-muted);
  }

  .balance-track {
    height: 5px;
    background: var(--surface-alt);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 8px;
  }

  .balance-fill {
    height: 100%;
    background: var(--accent);
  }

  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px;
    margin-bottom: 18px;
  }

  .card h2 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 16px;
  }

  .grid2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .field {
    margin-bottom: 16px;
  }

  .field.full {
    grid-column: 1 / -1;
  }

  .field label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 6px;
  }

  .field .req {
    color: var(--danger);
  }

  .field input,
  .field select,
  .field textarea {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid var(--border-strong);
    border-radius: 8px;
    font-size: 13.5px;
    font-family: inherit;
    color: var(--text-primary);
    background: var(--surface);
    outline: none;
  }

  .field input.err,
  .field select.err {
    border-color: var(--danger);
  }

  .field input:focus,
  .field select:focus,
  .field textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-light);
  }

  .field textarea {
    resize: vertical;
    min-height: 80px;
  }

  .field-error {
    font-size: 11.5px;
    color: var(--danger);
    margin-top: 5px;
  }

  .days-note {
    font-size: 12.5px;
    color: var(--text-secondary);
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 16px;
  }

  .days-note b {
    color: var(--text-primary);
  }

  .success-note {
    font-size: 13px;
    color: var(--success);
    background: var(--success-light);
    border: 1px solid #BFE3D2;
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 16px;
  }

  .btn {
    border: 1px solid var(--border-strong);
    background: var(--surface);
    color: var(--text-primary);
    font-size: 13.5px;
    font-weight: 500;
    padding: 9px 18px;
    border-radius: 8px;
    cursor: pointer;
  }

  .btn-primary {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
  }

  .btn-primary:hover {
    background: var(--accent-hover);
  }

  .actions-row {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead th {
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--text-muted);
    padding: 10px 12px;
    border-bottom: 1px solid var(--border);
  }

  tbody td {
    padding: 10px 12px;
    font-size: 13px;
    border-bottom: 1px solid var(--border);
    vertical-align: top;
  }

  tbody tr:last-child td {
    border-bottom: none;
  }

  .badge {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    display: inline-block;
  }

  .badge.pending {
    background: var(--warning-light);
    color: var(--warning);
  }

  .badge.approved {
    background: var(--success-light);
    color: var(--success);
  }

  .badge.rejected {
    background: var(--danger-light);
    color: var(--danger);
  }

  .reason-note {
    font-size: 11.5px;
    color: var(--text-muted);
    margin-top: 3px;
  }

  .section-title {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 14px;
  }
</style>
</head>

<body>
  <div class="wrap">

    <!-- <div class="crumb">Employee management <span>/</span> <span>Request leave</span></div> -->
    <div class="header">
      <h1>Request leave</h1>
      <p class="subtitle">Submit a new request and track the status of past ones.</p>
    </div>

    <?php if (!empty($mustResetPassword)): ?>
      <div style="font-size:13px;color:#233A9E;background:var(--accent-light);border:1px solid #C7D3FB;border-radius:8px;padding:10px 12px;margin-bottom:18px;">
        You're still using the temporary password you were given. <a href="employee-change-password" style="color:inherit;font-weight:600;">Set your own password</a>.
      </div>
    <?php endif; ?>

    <?php if (!empty($isAdminReviewer)): ?>

      <div class="card">
        <div class="section-title">Review requests <?= $pendingCount ? '<span class="badge pending">' . (int) $pendingCount . ' pending</span>' : '' ?></div>
        <form method="get" style="margin-bottom:16px;">
          <select name="status" onchange="this.form.submit()">
            <option value="pending" <?= $reviewStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="approved" <?= $reviewStatus === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="rejected" <?= $reviewStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            <option value="all" <?= $reviewStatus === 'all' ? 'selected' : '' ?>>All</option>
          </select>
        </form>

        <?php if (empty($reviewRequests)): ?>
          <p style="color:var(--text-muted);font-size:13px;">No <?= $reviewStatus === 'all' ? '' : htmlspecialchars($reviewStatus) . ' ' ?>leave requests.</p>
          <?php else: foreach ($reviewRequests as $r):
            $start = date('M j, Y', strtotime($r['start_date']));
            $end   = date('M j, Y', strtotime($r['end_date']));
            $dateLabel = $r['start_date'] === $r['end_date'] ? $start : (date('M j', strtotime($r['start_date'])) . ' – ' . $end);
            $days = (float) $r['total_days'];
            $niceDays = $days == (int) $days ? (int) $days : $days;
          ?>
            <div style="border:1px solid var(--border);border-radius:8px;padding:14px 16px;margin-bottom:12px;">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:8px;">
                <div>
                  <div style="font-weight:600;font-size:13.5px;"><?= htmlspecialchars($r['full_name']) ?> <span style="font-weight:400;color:var(--text-muted);font-size:12px;">· <?= htmlspecialchars($r['employee_code'] ?: '') ?></span></div>
                  <div style="font-size:12.5px;color:var(--text-secondary);margin-top:2px;"><?= htmlspecialchars($r['leave_type']) ?> · <?= $dateLabel ?> · <?= $niceDays ?> day<?= $niceDays == 1 ? '' : 's' ?></div>
                </div>
                <span class="badge <?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
              </div>
              <div style="font-size:12.5px;color:var(--text-secondary);background:var(--surface-alt);border-radius:6px;padding:8px 10px;margin-bottom:10px;"><?= htmlspecialchars($r['reason']) ?></div>

              <?php if ($r['status'] === 'pending'): ?>
                <form method="post">
                  <input type="hidden" name="leave_id" value="<?= (int) $r['id'] ?>">
                  <input type="hidden" name="status_filter" value="<?= htmlspecialchars($reviewStatus) ?>">
                  <div class="field" style="margin-bottom:8px;">
                    <textarea name="review_comment" placeholder="Optional note for the employee…" style="min-height:50px;"></textarea>
                  </div>
                  <div class="actions-row" style="justify-content:flex-start;">
                    <button type="submit" name="review_action" value="approve" class="btn btn-primary">Approve</button>
                    <button type="submit" name="review_action" value="reject" class="btn" style="border-color:var(--danger);color:var(--danger);">Reject</button>
                  </div>
                </form>
              <?php elseif (!empty($r['review_comment'])): ?>
                <div class="reason-note">Your note: "<?= htmlspecialchars($r['review_comment']) ?>"</div>
              <?php endif; ?>
            </div>
        <?php endforeach;
        endif; ?>
      </div>

    <?php elseif (empty($employee_id)): ?>
      <div class="card">
        <p style="margin:0;color:var(--text-secondary);">There's nothing to show here for your account.</p>
      </div>
    <?php else: ?>

      <div class="balance-row">
        <?php foreach ($balances as $type => $b): ?>
          <div class="balance-card">
            <div class="type"><?= htmlspecialchars($type) ?></div>
            <?php if ($b['allotment'] === null): ?>
              <div class="amt">— <span>no limit</span></div>
            <?php else:
              $pct = $b['allotment'] > 0 ? round(($b['remaining'] / $b['allotment']) * 100) : 0;
              $niceRemaining = $b['remaining'] == (int) $b['remaining'] ? (int) $b['remaining'] : $b['remaining'];
            ?>
              <div class="amt"><?= $niceRemaining ?> <span>/ <?= (int) $b['allotment'] ?> days left</span></div>
              <div class="balance-track">
                <div class="balance-fill" style="width:<?= $pct ?>%;"></div>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($submitted): ?>
        <div class="success-note">Your leave request has been submitted and is pending review.</div>
      <?php endif; ?>

      <div class="card">
        <h2>New leave request</h2>
        <form method="post" id="leaveForm">
          <div class="grid2">
            <div class="field">
              <label>Leave type <span class="req">*</span></label>
              <select name="leave_type" id="leaveType" class="<?= isset($errors['leave_type']) ? 'err' : '' ?>">
                <option value="">Select type</option>
                <?php foreach ($leaveTypes as $type => $allotment): ?>
                  <option value="<?= htmlspecialchars($type) ?>" <?= $form['leave_type'] === $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['leave_type'])): ?><div class="field-error"><?= htmlspecialchars($errors['leave_type']) ?></div><?php endif; ?>
            </div>
            <div class="field"></div>
            <div class="field">
              <label>Start date <span class="req">*</span></label>
              <input type="date" name="start_date" id="startDate" value="<?= htmlspecialchars($form['start_date']) ?>" class="<?= isset($errors['start_date']) ? 'err' : '' ?>">
              <?php if (!empty($errors['start_date'])): ?><div class="field-error"><?= htmlspecialchars($errors['start_date']) ?></div><?php endif; ?>
            </div>
            <div class="field">
              <label>End date <span class="req">*</span></label>
              <input type="date" name="end_date" id="endDate" value="<?= htmlspecialchars($form['end_date']) ?>" class="<?= isset($errors['end_date']) ? 'err' : '' ?>">
              <?php if (!empty($errors['end_date'])): ?><div class="field-error"><?= htmlspecialchars($errors['end_date']) ?></div><?php endif; ?>
            </div>
          </div>
          <div class="days-note" id="daysNote">Pick a start and end date to see how many working days this covers.</div>
          <div class="field full">
            <label>Reason <span class="req">*</span></label>
            <textarea name="reason" class="<?= isset($errors['reason']) ? 'err' : '' ?>" placeholder="Briefly describe the reason for your leave…"><?= htmlspecialchars($form['reason']) ?></textarea>
            <?php if (!empty($errors['reason'])): ?><div class="field-error"><?= htmlspecialchars($errors['reason']) ?></div><?php endif; ?>
          </div>
          <div class="actions-row">
            <button type="reset" class="btn">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit request</button>
          </div>
        </form>
      </div>

      <div class="card">
        <div class="section-title">My leave requests</div>
        <table>
          <thead>
            <tr>
              <th>Type</th>
              <th>Dates</th>
              <th>Days</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($requests)): ?>
              <tr>
                <td colspan="4" style="color:var(--text-muted);">No leave requests yet.</td>
              </tr>
              <?php else: foreach ($requests as $r):
                $start = date('M j, Y', strtotime($r['start_date']));
                $end   = date('M j, Y', strtotime($r['end_date']));
                $dateLabel = $r['start_date'] === $r['end_date'] ? $start : (date('M j', strtotime($r['start_date'])) . ' – ' . $end);
                $days = (float) $r['total_days'];
                $niceDays = $days == (int) $days ? (int) $days : $days;
              ?>
                <tr>
                  <td><?= htmlspecialchars($r['leave_type']) ?></td>
                  <td><?= $dateLabel ?></td>
                  <td><?= $niceDays ?></td>
                  <td>
                    <span class="badge <?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
                    <?php if (!empty($r['review_comment'])): ?>
                      <div class="reason-note">Manager: "<?= htmlspecialchars($r['review_comment']) ?>"</div>
                    <?php endif; ?>
                  </td>
                </tr>
            <?php endforeach;
            endif; ?>
          </tbody>
        </table>
      </div>

    <?php endif; ?>

  </div>

  <script>
    (function() {
      var startEl = document.getElementById('startDate');
      var endEl = document.getElementById('endDate');
      var note = document.getElementById('daysNote');
      if (!startEl || !endEl || !note) return;

      function fmt(d) {
        return d.toLocaleDateString('en-US', {
          weekday: 'short',
          month: 'short',
          day: 'numeric',
          year: 'numeric'
        });
      }

      function update() {
        if (!startEl.value || !endEl.value) {
          note.innerHTML = 'Pick a start and end date to see how many working days this covers.';
          return;
        }
        var start = new Date(startEl.value + 'T00:00:00');
        var end = new Date(endEl.value + 'T00:00:00');
        if (end < start) {
          note.innerHTML = 'End date can\'t be before the start date.';
          return;
        }
        var days = 0;
        var d = new Date(start);
        while (d <= end) {
          var dow = d.getDay();
          if (dow !== 0 && dow !== 6) days++;
          d.setDate(d.getDate() + 1);
        }
        var range = startEl.value === endEl.value ? fmt(start) : (fmt(start) + ' – ' + fmt(end));
        note.innerHTML = 'This request covers <b>' + days + ' working day' + (days === 1 ? '' : 's') + '</b> (' + range + ').';
      }

      startEl.addEventListener('change', update);
      endEl.addEventListener('change', update);
      update();
    })();
  </script>