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
  .ar-wrap *{box-sizing:border-box;}
  .ar-wrap{max-width:1360px;margin:0 auto;padding:32px 40px 80px;font-family:'Inter',system-ui,sans-serif;color:var(--text-primary);-webkit-font-smoothing:antialiased;}
  .ar-wrap .mono{font-family:'JetBrains Mono',monospace;}
  .ar-wrap .header{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:24px;flex-wrap:wrap;}
  .ar-wrap h1{font-size:24px;font-weight:600;margin:0 0 4px;letter-spacing:-0.01em;}
  .ar-wrap .subtitle{font-size:14px;color:var(--text-secondary);margin:0;}
  .ar-wrap .btn{border:1px solid var(--border-strong);background:var(--surface);color:var(--text-primary);font-size:13.5px;font-weight:500;
    padding:9px 16px;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:background .12s,border-color .12s;text-decoration:none;}
  .ar-wrap .btn:hover{background:var(--surface-alt);border-color:#B9C0CB;text-decoration:none;color:var(--text-primary);}
  .ar-wrap .btn-primary{background:var(--accent);border-color:var(--accent);color:#fff;}
  .ar-wrap .btn-primary:hover{background:var(--accent-hover);border-color:var(--accent-hover);color:#fff;}
  .ar-wrap .icon-btn{border:none;background:transparent;cursor:pointer;color:var(--text-muted);padding:6px;border-radius:6px;display:inline-flex;text-decoration:none;}
  .ar-wrap .icon-btn:hover{background:var(--surface-alt);color:var(--text-primary);}

  .ar-wrap .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;}
  .ar-wrap .stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;}
  .ar-wrap .stat .n{font-size:22px;font-weight:600;letter-spacing:-0.01em;}
  .ar-wrap .stat .l{font-size:12.5px;color:var(--text-secondary);margin-top:2px;}
  .ar-wrap .stat.good .n{color:var(--success);}

  .ar-wrap .toolbar{display:flex;align-items:center;gap:10px;margin-bottom:12px;flex-wrap:wrap;}
  .ar-wrap .search{position:relative;flex:1;min-width:220px;max-width:340px;}
  .ar-wrap .search input{width:100%;padding:9px 12px 9px 34px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;background:var(--surface);color:var(--text-primary);outline:none;height:auto;}
  .ar-wrap .search input:focus,.ar-wrap select:focus,.ar-wrap input[type=date]:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-light);}
  .ar-wrap .search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);}
  .ar-wrap select,.ar-wrap input[type=date]{padding:8px 12px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;color:var(--text-primary);background:var(--surface);outline:none;height:auto;}
  .ar-wrap .date-range{display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--text-secondary);}
  .ar-wrap .pills{display:flex;gap:6px;background:var(--surface);border:1px solid var(--border);padding:3px;border-radius:9px;}
  .ar-wrap .pill{padding:6px 13px;font-size:13px;font-weight:500;color:var(--text-secondary);border-radius:6px;text-decoration:none;display:inline-block;}
  .ar-wrap .pill:hover{text-decoration:none;color:var(--text-primary);}
  .ar-wrap .pill.active{background:var(--text-primary);color:#fff;}
  .ar-wrap .spacer{flex:1;}
  .ar-wrap .clear-link{font-size:13px;color:var(--accent);text-decoration:none;}

  .ar-wrap table.ar-table{width:100%;border-collapse:collapse;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-top:8px;}
  .ar-wrap .ar-table thead th{text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);padding:12px 16px;background:var(--surface-alt);border-bottom:1px solid var(--border);white-space:nowrap;}
  .ar-wrap .ar-table tbody td{padding:12px 16px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:top;}
  .ar-wrap .ar-table tbody tr:last-child td{border-bottom:none;}
  .ar-wrap .ar-table tbody tr.clickable:hover{background:var(--surface-alt);cursor:pointer;}
  .ar-wrap .user-name{font-weight:500;}
  .ar-wrap .muted{color:var(--text-muted);font-size:12px;}
  .ar-wrap .excerpt{color:var(--text-secondary);max-width:320px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
  .ar-wrap .score{font-weight:600;}
  .ar-wrap .delta{font-size:11.5px;font-weight:600;margin-left:4px;}
  .ar-wrap .delta.up{color:var(--success);}
  .ar-wrap .delta.down{color:var(--danger);}
  .ar-wrap .badge{font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;display:inline-block;white-space:nowrap;}
  .ar-wrap .badge.very_helpful,.ar-wrap .badge.yes{background:var(--success-light);color:var(--success);}
  .ar-wrap .badge.somewhat_helpful{background:var(--warning-light);color:var(--warning);}
  .ar-wrap .badge.not_helpful,.ar-wrap .badge.no{background:var(--danger-light);color:var(--danger);}
  .ar-wrap .badge.neutral{background:var(--accent-light);color:var(--accent);}
  .ar-wrap .chips{display:flex;flex-wrap:wrap;gap:4px;}
  .ar-wrap .chip{font-size:11px;padding:2px 8px;border-radius:6px;background:var(--surface-alt);border:1px solid var(--border);color:var(--text-secondary);white-space:nowrap;}

  .ar-wrap .pagination{display:flex;align-items:center;justify-content:space-between;gap:14px;margin:20px 0 0;flex-wrap:wrap;}
  .ar-wrap .page-info{font-size:12.5px;color:var(--text-secondary);}
  .ar-wrap .page-btns{display:flex;align-items:center;gap:4px;flex-wrap:wrap;}
  .ar-wrap .page-btn{min-width:30px;height:30px;padding:0 6px;border:1px solid var(--border);background:var(--surface);color:var(--text-secondary);
    border-radius:7px;font-size:12.5px;font-weight:500;display:flex;align-items:center;justify-content:center;text-decoration:none;}
  .ar-wrap .page-btn:hover{text-decoration:none;color:var(--text-primary);}
  .ar-wrap .page-btn.active{background:var(--text-primary);border-color:var(--text-primary);color:#fff;}
  .ar-wrap .page-gap{color:var(--text-muted);padding:0 4px;}
  .ar-wrap .page-size{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .ar-wrap .page-size label{margin:0 0 0 8px;font-weight:400;}
  .ar-wrap .page-size select{padding:5px 8px;font-size:12.5px;}

  .ar-wrap .detail{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:24px;}
  .ar-wrap .detail-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px 24px;margin-bottom:24px;}
  .ar-wrap .field .k{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);margin-bottom:4px;}
  .ar-wrap .field .v{font-size:14px;word-break:break-word;}
  .ar-wrap .experience{background:var(--surface-alt);border:1px solid var(--border);border-radius:8px;padding:16px;font-size:14px;line-height:1.65;white-space:pre-wrap;word-break:break-word;}
  .ar-wrap .notice{background:var(--danger-light);color:var(--danger);border-radius:8px;padding:12px 14px;font-size:13.5px;}
  .ar-wrap .flash{border-radius:8px;padding:12px 14px;font-size:13.5px;margin-bottom:20px;}
  .ar-wrap .flash.ok{background:var(--success-light);color:var(--success);}
  .ar-wrap .flash.err{background:var(--danger-light);color:var(--danger);}
  .ar-wrap .btn-danger{background:var(--danger);border-color:var(--danger);color:#fff;}
  .ar-wrap .btn-danger:hover{background:#B9381F;border-color:#B9381F;color:#fff;}
  .ar-wrap .btn[disabled]{opacity:.45;cursor:not-allowed;pointer-events:none;}
  .ar-wrap .icon-btn.danger:hover{background:var(--danger-light);color:var(--danger);}
  .ar-wrap .row-actions{display:flex;gap:2px;justify-content:flex-end;}
  .ar-wrap .ar-table th.sel,.ar-wrap .ar-table td.sel{width:36px;padding-right:0;}
  .ar-wrap .ar-table input[type=checkbox]{width:16px;height:16px;margin:0;cursor:pointer;accent-color:var(--accent);}
  .ar-wrap .ar-table tr.selected td{background:var(--accent-light);}

  @media (max-width:991px){
    .ar-wrap{padding:24px 16px 60px;}
    .ar-wrap .stats{grid-template-columns:repeat(2,1fr);}
    .ar-wrap .detail-grid{grid-template-columns:repeat(2,1fr);}
  }
  @media (max-width:767px){
    .ar-wrap .search{max-width:none;flex-basis:100%;}
    .ar-wrap .toolbar select,.ar-wrap .date-range{flex:1 1 calc(50% - 10px);}
    .ar-wrap .date-range input{flex:1;min-width:0;}
    .ar-wrap .spacer{display:none;}
    .ar-wrap .detail{padding:16px;}
    .ar-wrap .detail-grid{grid-template-columns:1fr;}
    /* Table rows become cards */
    .ar-wrap table.ar-table,.ar-wrap .ar-table tbody,.ar-wrap .ar-table tr,.ar-wrap .ar-table td{display:block;width:100%;}
    .ar-wrap table.ar-table{border:none;background:transparent;}
    .ar-wrap .ar-table thead{display:none;}
    .ar-wrap .ar-table tbody tr{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:12px;padding:6px 0;}
    .ar-wrap .ar-table tbody td{display:flex;justify-content:space-between;gap:12px;border-bottom:none;padding:7px 14px;text-align:right;}
    .ar-wrap .ar-table tbody td::before{content:attr(data-label);font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);text-align:left;flex-shrink:0;padding-top:2px;}
    .ar-wrap .ar-table tbody td.empty{justify-content:center;}
    .ar-wrap .ar-table tbody td.empty::before{display:none;}
    .ar-wrap .ar-table td.sel{width:100%;padding-right:14px;}
    .ar-wrap .excerpt{max-width:none;}
    .ar-wrap .chips{justify-content:flex-end;}
  }
  @media (max-width:420px){
    .ar-wrap .stats{grid-template-columns:1fr 1fr;gap:10px;}
    .ar-wrap .toolbar select,.ar-wrap .date-range{flex-basis:100%;}
  }
</style>
</head>
<body>
<?php
function arUserName($r)
{
	$name = trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
	return $name !== '' ? $name : '—';
}

function arFeatures($csv, $labels)
{
	$out = array();
	foreach (array_filter(array_map('trim', explode(',', (string) $csv))) as $f) {
		$out[] = $labels[$f] ?? $f;
	}
	return $out;
}

function arDate($dt, $withTime = true)
{
	if (empty($dt) || $dt === '0000-00-00 00:00:00') return '—';
	return date($withTime ? 'M d, Y g:i A' : 'M d, Y', strtotime($dt));
}

function arScoreDelta($r)
{
	if ($r['initial_practice_score'] === null || $r['initial_practice_score'] === '') return '';
	$d = (int) $r['final_actual_score'] - (int) $r['initial_practice_score'];
	if ($d === 0) return '';
	return '<span class="delta ' . ($d > 0 ? 'up' : 'down') . '">' . ($d > 0 ? '+' : '') . $d . '</span>';
}

$platformLabel = function ($p) use ($platforms) {
	return $p ? ($platforms[$p] ?? ucfirst($p)) : '—';
};
?>
<div class="ar-wrap">

<?php if (!empty($flash)): ?>
  <div class="flash <?= $flash[0] ?>"><?= htmlspecialchars($flash[1]) ?></div>
<?php endif; ?>

<?php if ($canDelete): ?>
  <form method="post" action="app-review-list" id="deleteForm" style="display:none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="confirmed" value="0">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="return_qs" value="<?= htmlspecialchars(buildQS(array('id' => null))) ?>">
  </form>
<?php endif; ?>

<?php if ($viewId > 0): ?>

  <?php $backQS = buildQS(array('id' => null)); ?>
  <div class="header">
    <div>
      <h1>Review #<?= (int) $viewId ?></h1>
      <p class="subtitle">Full details of the app review submission.</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="app-review-list<?= $backQS ? '?' . $backQS : '' ?>" class="btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 18-6-6 6-6"/></svg>
        Back to reviews
      </a>
      <?php if ($canDelete && !empty($review)): ?>
        <button type="button" class="btn btn-danger" onclick="arDelete([<?= (int) $viewId ?>])">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
          Delete
        </button>
      <?php endif; ?>
    </div>
  </div>

  <?php if (empty($review)): ?>
    <div class="notice">Review not found.</div>
  <?php else: $r = $review; $features = arFeatures($r['features_used'], $featureLabels); ?>
    <div class="detail">
      <div class="detail-grid">
        <div class="field"><div class="k">User</div><div class="v"><?= htmlspecialchars(arUserName($r)) ?></div></div>
        <div class="field"><div class="k">Email</div><div class="v"><?= htmlspecialchars($r['email'] ?: '—') ?></div></div>
        <div class="field"><div class="k">User ID</div><div class="v mono"><?= $r['user_id'] ? (int) $r['user_id'] : '—' ?></div></div>
        <div class="field"><div class="k">Mobile</div><div class="v"><?= htmlspecialchars($r['mobile'] ?: '—') ?></div></div>
        <div class="field"><div class="k">Course</div><div class="v"><?= htmlspecialchars($r['course_name'] ?: '—') ?></div></div>
        <div class="field"><div class="k">Platform</div><div class="v"><?= htmlspecialchars($platformLabel($r['platform'])) ?></div></div>
        <div class="field"><div class="k">Final actual score</div><div class="v"><span class="score"><?= (int) $r['final_actual_score'] ?></span><?= arScoreDelta($r) ?></div></div>
        <div class="field"><div class="k">Initial practice score</div><div class="v"><?= $r['initial_practice_score'] !== null && $r['initial_practice_score'] !== '' ? (int) $r['initial_practice_score'] : '—' ?></div></div>
        <div class="field"><div class="k">Helpfulness</div><div class="v">
          <?php if ($r['helpfulness_rating']): ?>
            <span class="badge <?= htmlspecialchars($r['helpfulness_rating']) ?>"><?= htmlspecialchars($ratingLabels[$r['helpfulness_rating']] ?? $r['helpfulness_rating']) ?></span>
          <?php else: ?>—<?php endif; ?>
        </div></div>
        <div class="field"><div class="k">Share permission</div><div class="v">
          <?= (int) $r['share_permission'] === 1 ? '<span class="badge yes">Allowed</span>' : '<span class="badge no">Not allowed</span>' ?>
        </div></div>
        <div class="field"><div class="k">Submitted</div><div class="v"><?= arDate($r['submitted_at']) ?></div></div>
        <div class="field"><div class="k">Features used</div><div class="v">
          <?php if ($features): ?>
            <div class="chips"><?php foreach ($features as $f): ?><span class="chip"><?= htmlspecialchars($f) ?></span><?php endforeach; ?></div>
          <?php else: ?>—<?php endif; ?>
        </div></div>
      </div>
      <div class="field">
        <div class="k">Experience</div>
        <div class="experience"><?= trim((string) $r['experience_text']) !== '' ? htmlspecialchars(html_entity_decode($r['experience_text'], ENT_QUOTES)) : '<span class="muted">No written feedback.</span>' ?></div>
      </div>
    </div>
  <?php endif; ?>

<?php else: ?>

  <?php
  $totalPages = max(1, (int) ceil($total / $perPage));
  $startItem  = empty($reviews) ? 0 : ($page - 1) * $perPage + 1;
  $endItem    = empty($reviews) ? 0 : $startItem + count($reviews) - 1;
  $hasFilters = ($search !== '' || $course !== '' || $rating !== '' || $platform !== '' || $share !== '' || $feature !== '' || $from !== '' || $to !== '');
  ?>

  <div class="header">
    <div>
      <h1>App Score Reviews</h1>
      <p class="subtitle">Score reports and feedback submitted from the mobile apps.</p>
    </div>
    <?php if ($canDelete && !empty($reviews)): ?>
      <button type="button" class="btn btn-danger" id="deleteSelectedBtn" disabled onclick="arDeleteSelected()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
        Delete selected <span id="selectedCount"></span>
      </button>
    <?php endif; ?>
  </div>

  <div class="stats">
    <div class="stat"><div class="n"><?= $stat_total ?></div><div class="l">Total reviews</div></div>
    <div class="stat good"><div class="n"><?= $stat_very_helpful ?></div><div class="l">Rated very helpful</div></div>
    <div class="stat"><div class="n"><?= $stat_shareable ?></div><div class="l">Allowed to share</div></div>
    <div class="stat"><div class="n"><?= $stat_this_month ?></div><div class="l">This month</div></div>
  </div>

  <form method="get" id="filterForm">
    <input type="hidden" name="platform" value="<?= htmlspecialchars($platform) ?>">
    <?php if ($perPage !== 20): ?><input type="hidden" name="per_page" value="<?= (int) $perPage ?>"><?php endif; ?>
    <div class="toolbar">
      <div class="search">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search name, email, review text or ID…">
      </div>
      <div class="pills">
        <a href="?<?= buildQS(array('platform' => '', 'limit' => '')) ?>" class="pill <?= $platform === '' ? 'active' : '' ?>">All</a>
        <?php foreach ($platforms as $k => $label): ?>
          <a href="?<?= buildQS(array('platform' => $k, 'limit' => '')) ?>" class="pill <?= $platform === $k ? 'active' : '' ?>"><?= $label ?></a>
        <?php endforeach; ?>
      </div>
      <div class="spacer"></div>
      <select name="sort" onchange="this.form.submit()">
        <option value="recent" <?= $sort === 'recent' ? 'selected' : '' ?>>Sort: Newest first</option>
        <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Sort: Oldest first</option>
        <option value="score_high" <?= $sort === 'score_high' ? 'selected' : '' ?>>Sort: Highest score</option>
        <option value="score_low" <?= $sort === 'score_low' ? 'selected' : '' ?>>Sort: Lowest score</option>
        <option value="improvement" <?= $sort === 'improvement' ? 'selected' : '' ?>>Sort: Biggest improvement</option>
      </select>
    </div>
    <div class="toolbar">
      <select name="course" onchange="this.form.submit()">
        <option value="">All courses</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?= htmlspecialchars($c) ?>" <?= $course === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="rating" onchange="this.form.submit()">
        <option value="">All ratings</option>
        <?php foreach ($ratingLabels as $k => $label): ?>
          <option value="<?= $k ?>" <?= $rating === $k ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
      </select>
      <select name="feature" onchange="this.form.submit()">
        <option value="">All features</option>
        <?php foreach ($featureLabels as $k => $label): ?>
          <option value="<?= $k ?>" <?= $feature === $k ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="share" onchange="this.form.submit()">
        <option value="">Share: any</option>
        <option value="1" <?= $share === '1' ? 'selected' : '' ?>>Share allowed</option>
        <option value="0" <?= $share === '0' ? 'selected' : '' ?>>Share not allowed</option>
      </select>
      <div class="date-range">
        <input type="date" name="from" value="<?= htmlspecialchars($from) ?>" onchange="this.form.submit()" title="Submitted from">
        <span>to</span>
        <input type="date" name="to" value="<?= htmlspecialchars($to) ?>" onchange="this.form.submit()" title="Submitted to">
      </div>
      <?php if ($hasFilters): ?>
        <a href="app-review-list<?= $perPage !== 20 ? '?per_page=' . (int) $perPage : '' ?>" class="clear-link">Clear filters</a>
      <?php endif; ?>
    </div>
  </form>

  <table class="ar-table">
    <thead>
      <tr>
        <?php if ($canDelete): ?>
          <th class="sel"><input type="checkbox" id="selectAll" title="Select all on this page" onclick="arSelectAll(this.checked)"></th>
        <?php endif; ?>
        <th>User</th>
        <th>Course</th>
        <th>Scores</th>
        <th>Helpfulness</th>
        <th>Features used</th>
        <th>Experience</th>
        <th>Share</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($reviews)): ?>
        <tr><td colspan="<?= $canDelete ? 9 : 8 ?>" class="empty" style="text-align:center;color:var(--text-muted);padding:28px 16px;">No reviews found.</td></tr>
      <?php else: foreach ($reviews as $r):
        $viewUrl = 'app-review-list?' . buildQS(array('id' => (int) $r['review_id']));
        $features = arFeatures($r['features_used'], $featureLabels);
      ?>
      <tr class="clickable" onclick="location.href='<?= htmlspecialchars($viewUrl, ENT_QUOTES) ?>'">
        <?php if ($canDelete): ?>
          <td data-label="Select" class="sel" onclick="event.stopPropagation();"><input type="checkbox" class="row-check" value="<?= (int) $r['review_id'] ?>" onchange="arUpdateSelection()"></td>
        <?php endif; ?>
        <td data-label="User">
          <div>
            <div class="user-name"><?= htmlspecialchars(arUserName($r)) ?></div>
            <div class="muted"><?= htmlspecialchars($r['email'] ?: '—') ?></div>
            <div class="muted mono">#<?= (int) $r['review_id'] ?></div>
          </div>
        </td>
        <td data-label="Course">
          <div>
            <div><?= htmlspecialchars($r['course_name'] ?: '—') ?></div>
            <span class="badge neutral" style="margin-top:4px;"><?= htmlspecialchars($platformLabel($r['platform'])) ?></span>
          </div>
        </td>
        <td data-label="Scores">
          <div>
            <span class="score"><?= (int) $r['final_actual_score'] ?></span><?= arScoreDelta($r) ?>
            <div class="muted">Initial: <?= $r['initial_practice_score'] !== null && $r['initial_practice_score'] !== '' ? (int) $r['initial_practice_score'] : '—' ?></div>
          </div>
        </td>
        <td data-label="Helpfulness">
          <?php if ($r['helpfulness_rating']): ?>
            <span class="badge <?= htmlspecialchars($r['helpfulness_rating']) ?>"><?= htmlspecialchars($ratingLabels[$r['helpfulness_rating']] ?? $r['helpfulness_rating']) ?></span>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td data-label="Features">
          <?php if ($features): ?>
            <div class="chips"><?php foreach ($features as $f): ?><span class="chip"><?= htmlspecialchars($f) ?></span><?php endforeach; ?></div>
          <?php else: ?>—<?php endif; ?>
        </td>
        <td data-label="Experience">
          <div>
            <div class="excerpt"><?= trim((string) $r['experience_text']) !== '' ? htmlspecialchars(html_entity_decode($r['experience_text'], ENT_QUOTES)) : '—' ?></div>
            <div class="muted" style="margin-top:4px;white-space:nowrap;"><?= arDate($r['submitted_at']) ?></div>
          </div>
        </td>
        <td data-label="Share"><?= (int) $r['share_permission'] === 1 ? '<span class="badge yes">Yes</span>' : '<span class="badge no">No</span>' ?></td>
        <td data-label="Actions">
          <div class="row-actions" onclick="event.stopPropagation();">
            <a class="icon-btn" title="View details" href="<?= htmlspecialchars($viewUrl) ?>">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
            </a>
            <?php if ($canDelete): ?>
              <button type="button" class="icon-btn danger" title="Delete" onclick="arDelete([<?= (int) $r['review_id'] ?>])">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
              </button>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>

  <div class="pagination">
    <div class="page-size">
      <div class="page-info">Showing <?= $startItem ?>–<?= $endItem ?> of <?= $total ?></div>
      <label class="page-info" for="perPageSelect">Per page</label>
      <select id="perPageSelect" onchange="location.href=this.value">
        <?php foreach ($perPageOptions as $n): ?>
          <option value="?<?= htmlspecialchars(buildQS(array('per_page' => $n === 20 ? '' : $n, 'limit' => ''))) ?>" <?= $perPage === $n ? 'selected' : '' ?>><?= $n ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="page-btns">
      <?php if ($page > 1): ?>
        <a class="page-btn" href="?<?= buildQS(array('limit' => $page - 1)) ?>">‹</a>
      <?php endif; ?>
      <?php
      $prev = 0;
      for ($p = 1; $p <= $totalPages; $p++):
        if ($p !== 1 && $p !== $totalPages && abs($p - $page) > 2) continue;
        if ($prev && $p - $prev > 1): ?><span class="page-gap">…</span><?php endif;
        $prev = $p;
      ?>
        <a class="page-btn <?= $p === $page ? 'active' : '' ?>" href="?<?= buildQS(array('limit' => $p)) ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
        <a class="page-btn" href="?<?= buildQS(array('limit' => $page + 1)) ?>">›</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

<?php endif; ?>

</div>

<?php if ($canDelete): ?>
<script>
  function arDelete(ids) {
    var msg = ids.length === 1
      ? 'Delete score review #' + ids[0] + '?\n\nThis permanently removes it and cannot be undone.'
      : 'Delete ' + ids.length + ' selected score reviews?\n\nThis permanently removes them and cannot be undone.';
    if (!ids.length || !confirm(msg)) return;
    var form = document.getElementById('deleteForm');
    ids.forEach(function (id) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'review_ids[]';
      input.value = id;
      form.appendChild(input);
    });
    form.elements.confirmed.value = '1';
    form.submit();
  }

  function arSelectedIds() {
    return Array.prototype.map.call(document.querySelectorAll('.row-check:checked'), function (c) { return parseInt(c.value, 10); });
  }

  function arUpdateSelection() {
    var checks = document.querySelectorAll('.row-check');
    var ids = arSelectedIds();
    Array.prototype.forEach.call(checks, function (c) { c.closest('tr').classList.toggle('selected', c.checked); });
    var btn = document.getElementById('deleteSelectedBtn');
    if (btn) btn.disabled = ids.length === 0;
    var count = document.getElementById('selectedCount');
    if (count) count.textContent = ids.length ? '(' + ids.length + ')' : '';
    var all = document.getElementById('selectAll');
    if (all) {
      all.checked = checks.length > 0 && ids.length === checks.length;
      all.indeterminate = ids.length > 0 && ids.length < checks.length;
    }
  }

  function arSelectAll(checked) {
    Array.prototype.forEach.call(document.querySelectorAll('.row-check'), function (c) { c.checked = checked; });
    arUpdateSelection();
  }

  function arDeleteSelected() {
    arDelete(arSelectedIds());
  }
</script>
<?php endif; ?>
