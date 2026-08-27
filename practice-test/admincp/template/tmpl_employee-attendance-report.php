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
    --shadow-lg: 0 20px 50px rgba(15, 18, 25, 0.16);
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
    max-width: 1360px;
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
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 24px;
  }

  h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 4px;
    letter-spacing: -0.01em;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .admin-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    background: var(--accent-light);
    color: var(--accent);
  }

  .subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
  }

  .stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
  }

  .stat {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 18px;
  }

  .stat .n {
    font-size: 22px;
    font-weight: 600;
    letter-spacing: -0.01em;
  }

  .stat .l {
    font-size: 12.5px;
    color: var(--text-secondary);
    margin-top: 2px;
  }

  .stat.warn .n {
    color: var(--warning);
  }

  .stat.danger .n {
    color: var(--danger);
  }

  .toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    flex-wrap: wrap;
  }

  .month-nav {
    display: flex;
    align-items: center;
    gap: 4px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 4px;
  }

  .month-nav button,
  .month-nav a {
    border: none;
    background: transparent;
    color: var(--text-secondary);
    width: 28px;
    height: 28px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
  }

  .month-nav button:hover,
  .month-nav a:hover {
    background: var(--surface-alt);
  }

  .month-label {
    font-size: 13.5px;
    font-weight: 600;
    padding: 0 10px;
    min-width: 120px;
    text-align: center;
  }

  .search {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 280px;
  }

  .search input {
    width: 100%;
    padding: 9px 12px 9px 34px;
    border: 1px solid var(--border-strong);
    border-radius: 8px;
    font-size: 13.5px;
    background: var(--surface);
    color: var(--text-primary);
    outline: none;
  }

  .search svg {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
  }

  select {
    padding: 8px 12px;
    border: 1px solid var(--border-strong);
    border-radius: 8px;
    font-size: 13.5px;
    color: var(--text-primary);
    background: var(--surface);
    outline: none;
  }

  .spacer {
    flex: 1;
  }

  .btn {
    border: 1px solid var(--border-strong);
    background: var(--surface);
    color: var(--text-primary);
    font-size: 13.5px;
    font-weight: 500;
    padding: 9px 16px;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
  }

  .btn:hover {
    background: var(--surface-alt);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
  }

  thead th {
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--text-muted);
    padding: 12px 16px;
    background: var(--surface-alt);
    border-bottom: 1px solid var(--border);
  }

  tbody td {
    padding: 12px 16px;
    font-size: 13.5px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }

  tbody tr:last-child td {
    border-bottom: none;
  }

  tbody tr:hover {
    background: var(--surface-alt);
    cursor: pointer;
  }

  .emp-cell {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: 12px;
    flex-shrink: 0;
  }

  .emp-name {
    font-weight: 500;
    font-size: 13.5px;
  }

  .emp-id {
    font-size: 11.5px;
    color: var(--text-muted);
  }

  .bar-cell {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .bar-track {
    width: 80px;
    height: 6px;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 4px;
    overflow: hidden;
  }

  .bar-fill {
    height: 100%;
    background: var(--success);
  }

  .pct {
    font-size: 12.5px;
    font-weight: 600;
    width: 36px;
  }

  /* drawer */
  .scrim {
    position: fixed;
    inset: 0;
    background: rgba(15, 18, 25, 0.35);
    opacity: 0;
    pointer-events: none;
    transition: opacity .18s;
    z-index: 10050;
  }

  .scrim.open {
    opacity: 1;
    pointer-events: auto;
  }

  .drawer {
    position: fixed;
    top: 0;
    right: 0;
    height: 100%;
    width: 480px;
    background: var(--surface);
    box-shadow: var(--shadow-lg);
    transform: translateX(100%);
    transition: transform .2s ease;
    z-index: 10051;
    display: flex;
    flex-direction: column;
  }

  .drawer.open {
    transform: translateX(0);
  }

  .drawer-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 1px solid var(--border);
  }

  .drawer-head-info {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .icon-btn {
    border: none;
    background: transparent;
    cursor: pointer;
    color: var(--text-muted);
    padding: 4px;
    border-radius: 6px;
    display: flex;
  }

  .icon-btn:hover {
    background: var(--surface-alt);
    color: var(--text-primary);
  }

  .drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
  }

  .drawer-summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-bottom: 20px;
  }

  .mini-stat {
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px;
    text-align: center;
  }

  .mini-stat .n {
    font-size: 16px;
    font-weight: 600;
  }

  .mini-stat .l {
    font-size: 10.5px;
    color: var(--text-muted);
    margin-top: 2px;
  }

  .day-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border);
  }

  .day-row:last-child {
    border-bottom: none;
  }

  .day-date {
    width: 64px;
    flex-shrink: 0;
  }

  .day-date .d {
    font-size: 15px;
    font-weight: 600;
  }

  .day-date .m {
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
  }

  .day-detail {
    flex: 1;
  }

  .day-times {
    font-size: 12.5px;
    color: var(--text-secondary);
    margin-bottom: 4px;
  }

  .day-report {
    font-size: 12.5px;
    color: var(--text-secondary);
    background: var(--surface-alt);
    border-radius: 6px;
    padding: 8px 10px;
    line-height: 1.5;
  }

  .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
  }

  .status-dot.present {
    background: var(--success);
  }

  .status-dot.late {
    background: var(--warning);
  }

  .status-dot.absent {
    background: var(--danger);
  }

  .status-dot.leave {
    background: #98A0AC;
  }
</style>
</head>

<?php
function empInitials($name)
{
	$parts = array_slice(preg_split('/\s+/', trim($name)), 0, 2);
	$out = '';
	foreach ($parts as $p) {
		if ($p !== '') $out .= strtoupper(substr($p, 0, 1));
	}
	return $out !== '' ? $out : '?';
}

function empAvatarColor($name)
{
	static $colors = array('#3350E0', '#1E8A5F', '#B4670A', '#6D5BD0', '#D5432E');
	$h = 0;
	foreach (str_split($name) as $c) {
		$h += ord($c);
	}
	return $colors[$h % count($colors)];
}
?>

<body>
  <div class="wrap">

    <!-- <div class="crumb">Employee management <span>/</span> <span>Attendance & work reports</span></div> -->
    <div class="header">
      <div>
        <h1>Attendance & work reports <span class="admin-badge">Super Admin</span></h1>
        <p class="subtitle">Monthly view of daily attendance and submitted work reports across the team.</p>
      </div>
      <div style="display:flex;gap:10px;">
        <a class="btn" href="govt-holidays">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" />
          </svg>
          Govt. Holidays
        </a>
        <a class="btn" href="?<?= buildQS(array('export' => 'csv')) ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M12 15V3m0 12-4-4m4 4 4-4M2 17l.6 2.4A2 2 0 0 0 4.5 21h15a2 2 0 0 0 1.9-1.6l.6-2.4" />
          </svg>
          Export CSV
        </a>
      </div>
    </div>

    <div class="stats">
      <div class="stat">
        <div class="n"><?= $stat_rate ?>%</div>
        <div class="l">Team attendance rate</div>
      </div>
      <div class="stat">
        <div class="n"><?= (int) $stat_avg_present ?></div>
        <div class="l">Avg. present per day</div>
      </div>
      <div class="stat warn">
        <div class="n"><?= (int) $stat_late ?></div>
        <div class="l">Late arrivals this month</div>
      </div>
      <div class="stat danger">
        <div class="n"><?= (int) $stat_missing_reports ?></div>
        <div class="l">Missing work reports</div>
      </div>
    </div>

    <form method="get" id="filterForm">
      <input type="hidden" name="month" value="<?= htmlspecialchars($month) ?>">
      <div class="toolbar">
        <div class="month-nav">
          <a href="?<?= buildQS(array('month' => $prevMonth)) ?>" title="Previous month">‹</a>
          <div class="month-label"><?= htmlspecialchars($monthLabel) ?></div>
          <a href="?<?= buildQS(array('month' => $nextMonth)) ?>" title="Next month">›</a>
        </div>
        <div class="search">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <circle cx="11" cy="11" r="7" />
            <path d="m21 21-4.3-4.3" />
          </svg>
          <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search employee…">
        </div>
        <select name="dept" onchange="this.form.submit()">
          <option value="">All departments</option>
          <?php foreach ($departments as $d): ?>
            <option value="<?= htmlspecialchars($d) ?>" <?= $dept === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
          <?php endforeach; ?>
        </select>
        <div class="spacer"></div>
        <span style="font-size:12.5px;color:var(--text-muted);">Click a row to view daily breakdown</span>
      </div>
    </form>

    <table>
      <thead>
        <tr>
          <th>Employee</th>
          <th>Present</th>
          <th>Absent</th>
          <th>Late</th>
          <th>On leave</th>
          <th>Attendance rate</th>
          <th>Reports filed</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($employees)): ?>
          <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:28px 16px;">No employees found.</td></tr>
        <?php else: foreach ($employees as $e): ?>
        <tr onclick="openDrawer('<?= htmlspecialchars($e['code'], ENT_QUOTES) ?>')">
          <td>
            <div class="emp-cell">
              <div class="avatar" style="background:<?= empAvatarColor($e['name']) ?>;"><?= htmlspecialchars(empInitials($e['name'])) ?></div>
              <div>
                <div class="emp-name"><?= htmlspecialchars($e['name']) ?></div>
                <div class="emp-id mono"><?= htmlspecialchars($e['code']) ?> · <?= htmlspecialchars($e['dept'] ?: '—') ?></div>
              </div>
            </div>
          </td>
          <td><?= (int) $e['present'] ?></td>
          <td><?= (int) $e['absent'] ?></td>
          <td><?= (int) $e['late'] ?></td>
          <td><?= (int) $e['leave'] ?></td>
          <td>
            <div class="bar-cell">
              <div class="bar-track"><div class="bar-fill" style="width:<?= (int) $e['rate'] ?>%;"></div></div>
              <div class="pct"><?= (int) $e['rate'] ?>%</div>
            </div>
          </td>
          <td><?= (int) $e['reports'] ?>/<?= (int) $e['expected'] ?></td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>

  </div>

  <div class="scrim" id="scrim"></div>
  <div class="drawer" id="drawer"></div>

  <script>
    const colors = ['#3350E0', '#1E8A5F', '#B4670A', '#6D5BD0', '#D5432E'];

    function initials(name) {
      return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
    }

    function avatarColor(name) {
      let h = 0;
      for (const c of name) h += c.charCodeAt(0);
      return colors[h % colors.length];
    }

    const employees = <?= json_encode(array_values($employees), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    const monthLabel = <?= json_encode($monthLabel) ?>;

    const statusLabel = {
      present: 'Present',
      late: 'Late',
      absent: 'Absent',
      leave: 'On leave'
    };

    function openDrawer(code) {
      const e = employees.find(x => x.code === code);
      if (!e) return;
      const drawer = document.getElementById('drawer');
      drawer.innerHTML = `
    <div class="drawer-head">
      <div class="drawer-head-info">
        <div class="avatar" style="background:${avatarColor(e.name)};width:38px;height:38px;">${initials(e.name)}</div>
        <div>
          <div style="font-weight:600;font-size:14px;">${e.name}</div>
          <div style="font-size:11.5px;color:var(--text-muted);">${e.code} · ${e.dept || '—'} · ${monthLabel}</div>
        </div>
      </div>
      <button class="icon-btn" onclick="closeDrawer()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="drawer-body">
      <div class="drawer-summary">
        <div class="mini-stat"><div class="n" style="color:var(--success);">${e.present}</div><div class="l">Present</div></div>
        <div class="mini-stat"><div class="n" style="color:var(--danger);">${e.absent}</div><div class="l">Absent</div></div>
        <div class="mini-stat"><div class="n" style="color:var(--warning);">${e.late}</div><div class="l">Late</div></div>
        <div class="mini-stat"><div class="n" style="color:var(--text-muted);">${e.leave}</div><div class="l">Leave</div></div>
      </div>
      <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);margin-bottom:8px;">Daily breakdown</div>
      ${e.days.length ? e.days.map(d => `
        <div class="day-row">
          <div class="day-date"><div class="d">${d.d.split(' ')[1]}</div><div class="m">${d.dow}</div></div>
          <div class="day-detail">
            <div class="day-times"><span class="status-dot ${d.status}"></span><b>${statusLabel[d.status]}</b> ${d.in !== '—' ? `· ${d.in} – ${d.out}` : ''}</div>
            ${d.report ? `<div class="day-report">${d.report}</div>` : (d.status==='absent' ? `<div class="day-report" style="color:var(--danger);background:var(--danger-light);">No work report submitted.</div>` : '')}
          </div>
        </div>
      `).join('') : `<div style="color:var(--text-muted);font-size:13px;">No attendance records for this month.</div>`}
    </div>
  `;
      document.getElementById('scrim').classList.add('open');
      drawer.classList.add('open');
    }

    function closeDrawer() {
      document.getElementById('scrim').classList.remove('open');
      document.getElementById('drawer').classList.remove('open');
    }
    document.getElementById('scrim').addEventListener('click', closeDrawer);

    <?php if (!empty($autoOpenCode)): ?>
      openDrawer(<?= json_encode($autoOpenCode) ?>);
    <?php endif; ?>
  </script>
