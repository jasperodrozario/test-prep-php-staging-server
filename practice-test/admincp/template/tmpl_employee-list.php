<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"> -->
<style>
  :root{
    --bg:#F5F6F8; --surface:#FFFFFF; --surface-alt:#FAFBFC;
    --border:#E4E7EC; --border-strong:#CDD2DB;
    --text-primary:#15171C; --text-secondary:#5B6472; --text-muted:#98A0AC;
    --accent:#3350E0; --accent-hover:#2740C4; --accent-light:#EBF0FE;
    --danger:#D5432E; --danger-light:#FBEAE7;
    --success:#1E8A5F; --success-light:#E7F5EE;
    --warning:#B4670A; --warning-light:#FCF0DF;
    --radius:10px; --shadow-lg:0 20px 50px rgba(15,18,25,0.16);
  }
  *{box-sizing:border-box;}
  body{margin:0;font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text-primary);-webkit-font-smoothing:antialiased;}
  .mono{font-family:'JetBrains Mono',monospace;}
  .wrap{max-width:1360px;margin:0 auto;padding:32px 40px 80px;}
  .crumb{font-size:13px;color:var(--text-muted);margin-bottom:6px;}
  .crumb span{color:var(--text-secondary);}
  .header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px;}
  h1{font-size:24px;font-weight:600;margin:0 0 4px;letter-spacing:-0.01em;}
  .subtitle{font-size:14px;color:var(--text-secondary);margin:0;}
  .btn{border:1px solid var(--border-strong);background:var(--surface);color:var(--text-primary);font-size:13.5px;font-weight:500;
    padding:9px 16px;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:background .12s,border-color .12s;text-decoration:none;}
  .btn:hover{background:var(--surface-alt);border-color:#B9C0CB;}
  .btn-primary{background:var(--accent);border-color:var(--accent);color:#fff;}
  .btn-primary:hover{background:var(--accent-hover);border-color:var(--accent-hover);}
  .btn-danger{background:var(--danger);border-color:var(--danger);color:#fff;}
  .btn-sm{padding:6px 10px;font-size:12.5px;}
  .icon-btn{border:none;background:transparent;cursor:pointer;color:var(--text-muted);padding:6px;border-radius:6px;display:flex;text-decoration:none;}
  .icon-btn:hover{background:var(--surface-alt);color:var(--text-primary);}
  .icon-btn[disabled],.icon-btn.disabled{pointer-events:none;}

  .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;}
  .stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;}
  .stat .n{font-size:22px;font-weight:600;letter-spacing:-0.01em;}
  .stat .l{font-size:12.5px;color:var(--text-secondary);margin-top:2px;}
  .stat.warn .n{color:var(--warning);}

  .toolbar{display:flex;align-items:center;gap:10px;margin-bottom:18px;flex-wrap:wrap;}
  .search{position:relative;flex:1;min-width:220px;max-width:320px;}
  .search input{width:100%;padding:9px 12px 9px 34px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;background:var(--surface);color:var(--text-primary);outline:none;}
  .search input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-light);}
  .search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);}
  select{padding:8px 12px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;color:var(--text-primary);background:var(--surface);outline:none;}
  .pills{display:flex;gap:6px;background:var(--surface);border:1px solid var(--border);padding:3px;border-radius:9px;}
  .pill{padding:6px 13px;font-size:13px;font-weight:500;color:var(--text-secondary);border-radius:6px;cursor:pointer;border:none;background:transparent;text-decoration:none;display:inline-block;}
  .pill.active{background:var(--text-primary);color:#fff;}
  .spacer{flex:1;}

  table{width:100%;border-collapse:collapse;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;}
  thead th{text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);padding:12px 16px;background:var(--surface-alt);border-bottom:1px solid var(--border);}
  tbody td{padding:12px 16px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle;}
  tbody tr:last-child td{border-bottom:none;}
  tbody tr:hover{background:var(--surface-alt);cursor:pointer;}
  .emp-cell{display:flex;align-items:center;gap:10px;}
  .avatar{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:12.5px;flex-shrink:0;}
  .emp-name{font-weight:500;font-size:13.5px;}
  .emp-id{font-size:11.5px;color:var(--text-muted);}
  .badge{font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;display:inline-block;}
  .badge.active{background:var(--success-light);color:var(--success);}
  .badge.inactive{background:var(--danger-light);color:var(--danger);}
  .badge.leave{background:var(--warning-light);color:var(--warning);}
  .row-actions{display:flex;gap:4px;justify-content:flex-end;}

  .self-signup-tip{background:var(--accent-light);border:1px solid #C7D3FB;border-radius:8px;padding:10px 14px;font-size:12.5px;color:#233A9E;margin-bottom:20px;line-height:1.6;}
  .self-signup-tip a{color:#233A9E;font-weight:600;word-break:break-all;}

  .pagination{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-top:20px;flex-wrap:wrap;}
  .page-info{font-size:12.5px;color:var(--text-secondary);}
  .page-btns{display:flex;align-items:center;gap:4px;}
  .page-btn{min-width:30px;height:30px;padding:0 6px;border:1px solid var(--border);background:var(--surface);color:var(--text-secondary);
    border-radius:7px;font-size:12.5px;font-weight:500;cursor:pointer;display:flex;align-items:center;justify-content:center;text-decoration:none;}
  .page-btn.active{background:var(--text-primary);border-color:var(--text-primary);color:#fff;}
</style>
</head>
<body>
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

$badgeLabel = array('active' => 'Active', 'inactive' => 'Inactive', 'leave' => 'On leave');

$totalPages = max(1, (int) ceil($total / $perPage));
$startItem  = empty($employees) ? 0 : ($page - 1) * $perPage + 1;
$endItem    = empty($employees) ? 0 : $startItem + count($employees) - 1;
?>
<div class="wrap">

  <!-- <div class="crumb">Employee management <span>/</span> <span>Employees</span></div> -->
   
  <div class="header">
    <div>
      <h1>Employees</h1>
      <p class="subtitle">Manage your team's profiles, roles, and access.</p>
    </div>
    <a href="add-employee" class="btn btn-primary">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 5v14M5 12h14"/></svg>
      Add employee
    </a>
  </div>

  <div class="self-signup-tip">
    <b>New employees can create their own profile</b> at <a href="https://staging-test-prep.asvab-tutoring.com/practice-test/add-employee-self" target="_blank" rel="noopener">https://staging-test-prep.asvab-tutoring.com/practice-test/add-employee-self</a> &mdash; accounts are created inactive, so find them below and set their status to Active once you've verified them.
  </div>

  <div class="stats">
    <div class="stat"><div class="n"><?= (int) $stat_total ?></div><div class="l">Total employees</div></div>
    <div class="stat"><div class="n"><?= (int) $stat_active ?></div><div class="l">Active</div></div>
    <div class="stat warn"><div class="n"><?= (int) $stat_on_leave ?></div><div class="l">On leave today</div></div>
    <div class="stat"><div class="n"><?= (int) $stat_new ?></div><div class="l">New this month</div></div>
  </div>

  <form method="get" id="filterForm">
    <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
    <div class="toolbar">
      <div class="search">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or employee ID…">
      </div>
      <div class="pills">
        <a href="?<?= buildQS(array('status' => 'all')) ?>" class="pill <?= $status === 'all' ? 'active' : '' ?>">All</a>
        <a href="?<?= buildQS(array('status' => 'active')) ?>" class="pill <?= $status === 'active' ? 'active' : '' ?>">Active</a>
        <a href="?<?= buildQS(array('status' => 'inactive')) ?>" class="pill <?= $status === 'inactive' ? 'active' : '' ?>">Inactive</a>
      </div>
      <select name="dept" onchange="this.form.submit()">
        <option value="">All departments</option>
        <?php foreach ($departments as $d): ?>
          <option value="<?= htmlspecialchars($d) ?>" <?= $dept === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="spacer"></div>
      <select name="sort" onchange="this.form.submit()">
        <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Sort: Name A–Z</option>
        <option value="recent" <?= $sort === 'recent' ? 'selected' : '' ?>>Sort: Recently added</option>
        <option value="department" <?= $sort === 'department' ? 'selected' : '' ?>>Sort: Department</option>
      </select>
    </div>
  </form>

  <table>
    <thead>
      <tr>
        <th>Employee</th>
        <th>Designation</th>
        <th>Department</th>
        <th>Contact</th>
        <th>Joined</th>
        <th>Status</th>
        <th style="text-align:right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($employees)): ?>
        <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:28px 16px;">No employees found.</td></tr>
      <?php else: foreach ($employees as $e):
        $name = $e['full_name'];
        $isOnLeave = in_array((int) $e['id'], $leaveIds, true);
        $statusKey = $isOnLeave ? 'leave' : $e['status'];
        $joined = $e['date_of_joining'] ? date('M d, Y', strtotime($e['date_of_joining'])) : '—';
      ?>
      <tr onclick="location.href='employee-attendance-report?employee_id=<?= (int) $e['id'] ?>'">
        <td>
          <div class="emp-cell">
            <div class="avatar" style="background:<?= empAvatarColor($name) ?>;"><?= htmlspecialchars(empInitials($name)) ?></div>
            <div>
              <div class="emp-name"><?= htmlspecialchars($name) ?></div>
              <div class="emp-id mono"><?= htmlspecialchars($e['employee_code'] ?: '—') ?></div>
            </div>
          </div>
        </td>
        <td><?= htmlspecialchars($e['designation'] ?: '—') ?></td>
        <td><?= htmlspecialchars($e['department'] ?: '—') ?></td>
        <td>
          <div><?= htmlspecialchars($e['email']) ?></div>
          <div style="color:var(--text-muted);font-size:12px;"><?= htmlspecialchars($e['phone'] ?: '—') ?></div>
        </td>
        <td><?= $joined ?></td>
        <td><span class="badge <?= $statusKey ?>"><?= $badgeLabel[$statusKey] ?></span></td>
        <td>
          <div class="row-actions" onclick="event.stopPropagation();">
            <a class="icon-btn" title="Edit" href="add-employee?id=<?= (int) $e['id'] ?>">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </a>
            <a class="icon-btn" title="Delete" href="?<?= buildQS(array('del_id' => (int) $e['id'])) ?>" onclick="return confirm('Delete <?= htmlspecialchars(addslashes($name)) ?>? This cannot be undone.');">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
            </a>
          </div>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>

  <div class="pagination">
    <div class="page-info">Showing <?= $startItem ?>–<?= $endItem ?> of <?= $total ?></div>
    <div class="page-btns">
      <?php if ($page > 1): ?>
        <a class="page-btn" href="?<?= buildQS(array('limit' => $page - 1)) ?>">‹</a>
      <?php endif; ?>
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a class="page-btn <?= $p === $page ? 'active' : '' ?>" href="?<?= buildQS(array('limit' => $p)) ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
        <a class="page-btn" href="?<?= buildQS(array('limit' => $page + 1)) ?>">›</a>
      <?php endif; ?>
    </div>
  </div>

</div>
