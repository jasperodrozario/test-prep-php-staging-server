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
  .wrap{max-width:760px;margin:0 auto;padding:32px 40px 80px;}
  .header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px;gap:16px;}
  h1{font-size:24px;font-weight:600;margin:0 0 4px;letter-spacing:-0.01em;}
  .subtitle{font-size:14px;color:var(--text-secondary);margin:0;}

  .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:24px;margin-bottom:18px;}

  .btn{border:1px solid var(--border-strong);background:var(--surface);color:var(--text-primary);font-size:13.5px;font-weight:500;
    padding:9px 16px;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none;}
  .btn:hover{background:var(--surface-alt);}
  .btn-primary{background:var(--accent);border-color:var(--accent);color:#fff;}
  .btn-primary:hover{background:var(--accent-hover);}
  .btn.btn-danger{background:var(--danger);border-color:var(--danger);color:#fff;}
  .btn.btn-danger:hover{background:#b8371f;border-color:#b8371f;}
  .btn-sm{padding:5px 10px;font-size:12.5px;}

  select{padding:8px 12px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;color:var(--text-primary);background:var(--surface);outline:none;}
  .field select{width:100%;}

  .field{margin-bottom:14px;}
  .field label{display:block;font-size:12.5px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;}
  .field input, .field textarea{width:100%;padding:9px 12px;border:1px solid var(--border-strong);border-radius:8px;font-size:13.5px;font-family:inherit;color:var(--text-primary);outline:none;}
  .field input:focus, .field textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-light);}
  .field textarea{resize:vertical;min-height:60px;}
  .field .err{font-size:12px;color:var(--danger);margin-top:4px;}
  .form-row{display:flex;gap:12px;}
  .form-row .field{flex:1;min-width:0;}
  .actions-row{display:flex;justify-content:flex-end;gap:10px;}

  .section-title{font-size:14px;font-weight:600;margin:0 0 14px;display:flex;align-items:center;justify-content:space-between;}

  .holiday-row{display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border);}
  .holiday-row:last-child{border-bottom:none;}
  .h-date{width:64px;flex-shrink:0;text-align:center;background:var(--surface-alt);border:1px solid var(--border);border-radius:8px;padding:6px 4px;}
  .h-date.past{opacity:.55;}
  .h-date .d{font-size:16px;font-weight:700;line-height:1.1;}
  .h-date .m{font-size:10.5px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.03em;}
  .h-body{flex:1;min-width:0;}
  .h-title{font-weight:600;font-size:13.5px;}
  .h-meta{font-size:11.5px;color:var(--text-muted);margin-top:2px;}
  .h-desc{font-size:12.5px;color:var(--text-secondary);margin-top:4px;}
  .h-actions{display:flex;gap:6px;flex-shrink:0;}
  .badge-upcoming{font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:20px;background:var(--success-light);color:var(--success);margin-left:8px;}
  .badge-country{font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:20px;background:var(--accent-light);color:var(--accent);margin-left:8px;}
  .filters{display:flex;gap:8px;}

  .empty{color:var(--text-muted);font-size:13px;text-align:center;padding:24px 0;}
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <div>
      <h1>Government Holidays</h1>
      <p class="subtitle">Shared holiday calendar — any employee can add or update an entry.</p>
    </div>
    <form method="get" style="margin:0;" class="filters">
      <select name="country" onchange="this.form.submit()">
        <option value="0" <?= $countryParam === 0 ? 'selected' : '' ?>>All countries</option>
        <?php foreach ($usedCountries as $cid => $cname): ?>
          <option value="<?= (int) $cid ?>" <?= $countryParam === (int) $cid ? 'selected' : '' ?>><?= htmlspecialchars($cname) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="year" onchange="this.form.submit()">
        <?php foreach ($years as $y): ?>
          <option value="<?= (int) $y ?>" <?= $y === $year ? 'selected' : '' ?>><?= (int) $y ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <div class="card">
    <div class="section-title">
      <span>Add a holiday</span>
    </div>
    <form method="post">
      <input type="hidden" name="save_holiday" value="1">
      <input type="hidden" name="year" value="<?= (int) $year ?>">
      <input type="hidden" name="filter_country" value="<?= (int) $countryParam ?>">
      <div class="form-row">
        <div class="field">
          <label>Date</label>
          <input type="date" name="holiday_date" required>
        </div>
        <div class="field">
          <label>Country</label>
          <select name="country_id" required>
            <?php $preselect = $countryParam ?: $defaultCountryId; ?>
            <?php foreach ($allCountries as $c): ?>
              <option value="<?= (int) $c['id'] ?>" <?= (int) $c['id'] === (int) $preselect ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field">
        <label>Holiday name</label>
        <input type="text" name="title" placeholder="e.g. Independence Day" required>
      </div>
      <div class="field">
        <label>Notes (optional)</label>
        <textarea name="description" placeholder="Any extra detail employees should know"></textarea>
      </div>
      <div class="actions-row">
        <button type="submit" class="btn btn-primary">Add holiday</button>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="section-title"><span><?= (int) $year ?> holidays<?= $countryParam && isset($usedCountries[$countryParam]) ? ' · ' . htmlspecialchars($usedCountries[$countryParam]) : '' ?></span></div>

    <?php if (empty($holidays)): ?>
      <div class="empty">No holidays added for <?= (int) $year ?> yet.</div>
    <?php else: foreach ($holidays as $h): ?>
      <?php $isPast = $h['holiday_date'] < $today; ?>
      <div class="holiday-row" id="row-<?= (int) $h['id'] ?>">
        <div class="h-date <?= $isPast ? 'past' : '' ?>">
          <div class="d"><?= date('j', strtotime($h['holiday_date'])) ?></div>
          <div class="m"><?= date('M', strtotime($h['holiday_date'])) ?></div>
        </div>
        <div class="h-body">
          <div class="h-title">
            <?= htmlspecialchars($h['title']) ?><span class="badge-country"><?= htmlspecialchars($h['country_code'] ?: $h['country_name']) ?></span><?= (!$isPast && $h['holiday_date'] === $today) ? '<span class="badge-upcoming">Today</span>' : '' ?>
          </div>
          <div class="h-meta">
            <?= date('l', strtotime($h['holiday_date'])) ?>
            · added by <?= htmlspecialchars($h['created_by_name']) ?>
            <?php if (!empty($h['updated_by_name'])): ?> · edited by <?= htmlspecialchars($h['updated_by_name']) ?><?php endif; ?>
          </div>
          <?php if (!empty($h['description'])): ?><div class="h-desc"><?= nl2br(htmlspecialchars($h['description'])) ?></div><?php endif; ?>
        </div>
        <div class="h-actions">
          <button type="button" class="btn btn-sm" onclick="editHoliday(<?= (int) $h['id'] ?>)">Edit</button>
          <form method="post" onsubmit="return confirm('Remove this holiday?');" style="margin:0;">
            <input type="hidden" name="delete_holiday" value="1">
            <input type="hidden" name="id" value="<?= (int) $h['id'] ?>">
            <input type="hidden" name="year" value="<?= (int) $year ?>">
            <input type="hidden" name="filter_country" value="<?= (int) $countryParam ?>">
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>

</div>

<div class="scrim" id="scrim" style="position:fixed;inset:0;background:rgba(15,18,25,.35);display:none;z-index:10050;"></div>
<div id="editModal" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:420px;max-width:92vw;background:var(--surface);border-radius:var(--radius);padding:22px;z-index:10051;box-shadow:0 20px 50px rgba(15,18,25,.25);">
  <div style="font-weight:600;font-size:14.5px;margin-bottom:14px;">Edit holiday</div>
  <form method="post" id="editForm">
    <input type="hidden" name="save_holiday" value="1">
    <input type="hidden" name="year" value="<?= (int) $year ?>">
    <input type="hidden" name="filter_country" value="<?= (int) $countryParam ?>">
    <input type="hidden" name="id" id="edit_id">
    <div class="form-row">
      <div class="field">
        <label>Date</label>
        <input type="date" name="holiday_date" id="edit_date" required>
      </div>
      <div class="field">
        <label>Country</label>
        <select name="country_id" id="edit_country">
          <?php foreach ($allCountries as $c): ?>
            <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="field">
      <label>Holiday name</label>
      <input type="text" name="title" id="edit_title" required>
    </div>
    <div class="field">
      <label>Notes (optional)</label>
      <textarea name="description" id="edit_description"></textarea>
    </div>
    <div class="actions-row">
      <button type="button" class="btn" onclick="closeEdit()">Cancel</button>
      <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
  </form>
</div>

<script>
const holidays = <?= json_encode(array_values($holidays), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function editHoliday(id) {
  const h = holidays.find(x => parseInt(x.id, 10) === id);
  if (!h) return;
  document.getElementById('edit_id').value = h.id;
  document.getElementById('edit_date').value = h.holiday_date;
  document.getElementById('edit_country').value = h.country_id;
  document.getElementById('edit_title').value = h.title;
  document.getElementById('edit_description').value = h.description || '';
  document.getElementById('scrim').style.display = 'block';
  document.getElementById('editModal').style.display = 'block';
}

function closeEdit() {
  document.getElementById('scrim').style.display = 'none';
  document.getElementById('editModal').style.display = 'none';
}
document.getElementById('scrim').addEventListener('click', closeEdit);
</script>
