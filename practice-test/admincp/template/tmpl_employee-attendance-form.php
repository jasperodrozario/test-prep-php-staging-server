<style>
  :root{
    --bg:#F5F6F8; --surface:#FFFFFF; --surface-alt:#FAFBFC;
    --border:#E4E7EC; --border-strong:#CDD2DB;
    --text-primary:#15171C; --text-secondary:#5B6472; --text-muted:#98A0AC;
    --accent:#3350E0; --accent-hover:#2740C4; --accent-light:#EBF0FE;
    --danger:#D5432E; --danger-light:#FBEAE7;
    --success:#1E8A5F; --success-light:#E7F5EE;
    --warning:#B4670A; --warning-light:#FCF0DF;
    --radius:10px;
  }
  *{box-sizing:border-box;}
  body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text-primary);-webkit-font-smoothing:antialiased;}
  .mono{font-family:'JetBrains Mono',monospace;}
  .wrap{max-width:760px;margin:0 auto;padding:32px 40px 80px;}
  .crumb{font-size:13px;color:var(--text-muted);margin-bottom:6px;}
  .crumb span{color:var(--text-secondary);}
  .header{margin-bottom:24px;}
  h1{font-size:24px;font-weight:600;margin:0 0 4px;letter-spacing:-0.01em;}
  .subtitle{font-size:14px;color:var(--text-secondary);margin:0;}

  .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:18px;}

  .clock-card{text-align:center;padding:32px 24px;}
  .today-date{font-size:13px;color:var(--text-muted);margin-bottom:4px;}
  .live-time{font-size:34px;font-weight:600;letter-spacing:-0.02em;margin-bottom:4px;font-family:'JetBrains Mono',monospace;}
  .usa-time{font-size:13px;color:var(--text-muted);margin-bottom:16px;font-family:'JetBrains Mono',monospace;}
  .clock-status{display:inline-flex;align-items:center;gap:8px;font-size:13.5px;font-weight:500;padding:8px 16px;border-radius:20px;margin-bottom:20px;}
  .clock-status.not-started{background:var(--surface-alt);color:var(--text-secondary);border:1px solid var(--border);}
  .clock-status.in-progress{background:var(--success-light);color:var(--success);}
  .clock-status.late{background:var(--warning-light);color:var(--warning);}
  .clock-status.done{background:var(--surface-alt);color:var(--text-secondary);border:1px solid var(--border);}
  .clock-status .dot{width:7px;height:7px;border-radius:50%;background:currentColor;}
  .clock-btn{width:100%;max-width:280px;padding:14px 20px;border-radius:10px;font-size:15px;font-weight:600;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;margin:0 auto;}
  .clock-btn.in{background:var(--accent);color:#fff;}
  .clock-btn.out{background:var(--danger);color:#fff;}
  .clock-times{display:flex;justify-content:center;gap:32px;margin-top:20px;font-size:13px;}
  .clock-times .t-label{color:var(--text-muted);margin-bottom:2px;}
  .clock-times .t-val{font-weight:600;font-family:'JetBrains Mono',monospace;}

  .field{margin-bottom:14px;}
  .field label{display:block;font-size:12.5px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;}
  .field textarea{width:100%;padding:10px 12px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;font-family:inherit;color:var(--text-primary);outline:none;resize:vertical;min-height:100px;}
  .field textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-light);}
  .field textarea:disabled{background:var(--surface-alt);color:var(--text-muted);}
  .btn{border:1px solid var(--border-strong);background:var(--surface);color:var(--text-primary);font-size:13.5px;font-weight:500;
    padding:9px 18px;border-radius:8px;cursor:pointer;}
  .btn-primary{background:var(--accent);border-color:var(--accent);color:#fff;}
  .btn-primary:hover{background:var(--accent-hover);}
  .btn-primary:disabled{background:var(--border-strong);border-color:var(--border-strong);cursor:not-allowed;}
  .actions-row{display:flex;justify-content:flex-end;align-items:center;gap:10px;}
  .hint{font-size:12.5px;color:var(--text-muted);}

  table{width:100%;border-collapse:collapse;}
  thead th{text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);padding:10px 12px;border-bottom:1px solid var(--border);}
  tbody td{padding:10px 12px;font-size:13px;border-bottom:1px solid var(--border);}
  tbody tr:last-child td{border-bottom:none;}
  .badge{font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;display:inline-block;}
  .badge.present{background:var(--success-light);color:var(--success);}
  .badge.late{background:var(--warning-light);color:var(--warning);}
  .badge.absent{background:var(--danger-light);color:var(--danger);}
  .badge.leave{background:var(--surface-alt);color:var(--text-secondary);}
  .section-title{font-size:14px;font-weight:600;margin:0 0 14px;}
</style>
</head>
<body>
<div class="wrap">

  <!-- <div class="crumb">Employee management <span>/</span> <span>My attendance</span></div> -->
  <div class="header">
    <h1>My Attendance</h1>
    <p class="subtitle">Mark your attendance and share what you worked on today.</p>
  </div>

  <?php if (!empty($mustResetPassword)): ?>
    <div style="font-size:13px;color:#233A9E;background:var(--accent-light);border:1px solid #C7D3FB;border-radius:8px;padding:10px 12px;margin-bottom:18px;">
      You're still using the temporary password you were given. <a href="employee-change-password" style="color:inherit;font-weight:600;">Set your own password</a>.
    </div>
  <?php endif; ?>

  <?php if (empty($employee_id)): ?>
    <div class="card">
      <p style="margin:0;color:var(--text-secondary);">You're not signed in as an employee, so there's nothing to show here yet.</p>
    </div>
  <?php else:
    $isLeave    = $todayRow && $todayRow['status'] === 'leave';
    $checkedIn  = $todayRow && $todayRow['check_in'];
    $checkedOut = $todayRow && $todayRow['check_out'];
  ?>

  <div class="card clock-card">
    <div class="today-date"><?= date('l, F j, Y', strtotime($today)) ?></div>
    <div class="live-time" id="liveTime"></div>
    <div class="usa-time" id="usaTime"></div>

    <?php if ($isLeave): ?>
      <div class="clock-status done"><span class="dot"></span> On leave today</div>
    <?php elseif ($checkedOut): ?>
      <div class="clock-status done"><span class="dot"></span> Day complete</div>
    <?php elseif ($checkedIn): ?>
      <div class="clock-status <?= $todayRow['status'] === 'late' ? 'late' : 'in-progress' ?>">
        <span class="dot"></span> Checked in at <?= date('g:i A', strtotime($todayRow['check_in'])) ?><?= $todayRow['status'] === 'late' ? ' (late)' : '' ?>
      </div>
    <?php else: ?>
      <div class="clock-status not-started"><span class="dot"></span> Not checked in yet</div>
    <?php endif; ?>

    <?php if (!$isLeave && !$checkedIn): ?>
      <form method="post">
        <input type="hidden" name="clock_action" value="check_in">
        <button type="submit" class="clock-btn in">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          Check in
        </button>
      </form>
    <?php elseif (!$isLeave && $checkedIn && !$checkedOut): ?>
      <form method="post" onsubmit="return confirm('Are you sure you want to check out?');">
        <input type="hidden" name="clock_action" value="check_out">
        <button type="submit" class="clock-btn out">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          Check out
        </button>
      </form>
    <?php endif; ?>

    <?php if (!$isLeave): ?>
      <div class="clock-times">
        <div><div class="t-label">Checked in</div><div class="t-val"><?= $checkedIn ? date('g:i A', strtotime($todayRow['check_in'])) : '—' ?></div></div>
        <div><div class="t-label">Checked out</div><div class="t-val"><?= $checkedOut ? date('g:i A', strtotime($todayRow['check_out'])) : '—' ?></div></div>
      </div>
    <?php endif; ?>
  </div>

  <div class="card">
    <div class="section-title">Today's work report</div>
    <form method="post">
      <input type="hidden" name="save_report" value="1">
      <div class="field">
        <label>What did you work on today?</label>
        <textarea name="work_report" <?= ($todayRow && !$isLeave) ? '' : 'disabled' ?> placeholder="e.g. Reviewed flagged practice questions, held 3 tutoring sessions, drafted new content for the geometry module…"><?= $todayRow ? htmlspecialchars($todayRow['work_report']) : '' ?></textarea>
      </div>
      <div class="actions-row">
        <?php if (!$todayRow): ?><span class="hint">Check in first to log today's report.</span><?php endif; ?>
        <?php if ($isLeave): ?><span class="hint">You're marked on leave today.</span><?php endif; ?>
        <button type="submit" class="btn btn-primary" <?= ($todayRow && !$isLeave) ? '' : 'disabled' ?>>Save report</button>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="section-title">My recent attendance</div>
    <table>
      <thead><tr><th>Date</th><th>Check-in</th><th>Check-out</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (empty($recent)): ?>
          <tr><td colspan="4" style="color:var(--text-muted);">No attendance records yet.</td></tr>
        <?php else: foreach ($recent as $r): ?>
          <tr>
            <td><?= date('M j, Y', strtotime($r['attendance_date'])) ?></td>
            <td><?= $r['check_in'] ? date('g:i A', strtotime($r['check_in'])) : '—' ?></td>
            <td><?= $r['check_out'] ? date('g:i A', strtotime($r['check_out'])) : '—' ?></td>
            <td><span class="badge <?= $r['status'] ?>"><?= ucfirst($r['status'] === 'leave' ? 'On leave' : $r['status']) ?></span></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <?php endif; ?>

</div>

<script>
function tick(){
  const now = new Date();
  const el = document.getElementById('liveTime');
  if (el) el.textContent = now.toLocaleTimeString('en-US', {hour:'numeric', minute:'2-digit', second:'2-digit'});

  const usEl = document.getElementById('usaTime');
  if (usEl) {
    const usTime = now.toLocaleTimeString('en-US', {hour:'numeric', minute:'2-digit', second:'2-digit', timeZone:'America/New_York', timeZoneName:'short'});
    usEl.textContent = 'USA: ' + usTime;
  }
}
tick();
setInterval(tick, 1000);
</script>
