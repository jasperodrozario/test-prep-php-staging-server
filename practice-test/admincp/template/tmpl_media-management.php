<style>
  .ml-wrap {
    max-width: 1360px;
    margin: 0 auto;
    padding: 32px 40px 80px;
    font-family: 'Inter', system-ui, sans-serif;
    color: #15171C;
  }

  .ml-wrap * {
    box-sizing: border-box;
  }

  .ml-mono {
    font-family: 'JetBrains Mono', monospace;
  }

  .ml-crumb {
    font-size: 13px;
    color: #98A0AC;
    margin-bottom: 6px;
  }

  .ml-crumb span {
    color: #5B6472;
  }

  .ml-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 24px;
  }

  .ml-wrap h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 4px;
    letter-spacing: -0.01em;
  }

  .ml-subtitle {
    font-size: 14px;
    color: #5B6472;
    margin: 0;
  }

  .ml-btn {
    border: 1px solid #CDD2DB;
    background: #fff;
    color: #15171C;
    font-size: 13.5px;
    font-weight: 500;
    padding: 9px 16px;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background .12s, border-color .12s;
  }

  .ml-btn:hover {
    background: #FAFBFC;
    border-color: #B9C0CB;
  }

  .ml-btn-primary {
    background: #3350E0;
    border-color: #3350E0;
    color: #fff;
  }

  .ml-btn-primary:hover {
    background: #2740C4;
    border-color: #2740C4;
  }

  .ml-btn-danger {
    background: #D5432E;
    border-color: #D5432E;
    color: #fff;
  }

  .ml-btn-danger:hover {
    background: #B8371F;
    border-color: #B8371F;
  }

  .ml-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
  }

  .ml-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 28px;
  }

  .ml-stat {
    background: #fff;
    border: 1px solid #E4E7EC;
    border-radius: 10px;
    padding: 16px 18px;
  }

  .ml-stat .n {
    font-size: 22px;
    font-weight: 600;
    letter-spacing: -0.01em;
  }

  .ml-stat .l {
    font-size: 12.5px;
    color: #5B6472;
    margin-top: 2px;
  }

  .ml-stat.warn .n {
    color: #B4670A;
  }

  .ml-stat-clickable {
    cursor: pointer;
    transition: border-color .12s, box-shadow .12s;
  }

  .ml-stat-clickable:hover {
    border-color: #CDD2DB;
    box-shadow: 0 2px 10px rgba(15, 18, 25, 0.06);
  }

  .ml-stat-clickable.active {
    border-color: #3350E0;
    box-shadow: 0 0 0 3px #EBF0FE;
  }

  .ml-stat-clickable.active .n {
    color: #3350E0;
  }

  .ml-stat.warn.active .n {
    color: #B4670A;
  }

  .ml-active-filter-bar {
    display: none;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    font-size: 12.5px;
    color: #5B6472;
  }

  .ml-active-filter-bar.show {
    display: flex;
  }

  .ml-active-filter-bar .clear {
    color: #3350E0;
    cursor: pointer;
    font-weight: 500;
  }

  .ml-active-filter-bar .clear:hover {
    text-decoration: underline;
  }

  .ml-bulk-bar {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 14px;
    font-size: 12.5px;
    color: #5B6472;
    flex-wrap: wrap;
  }

  .ml-bulk-select-all {
    display: flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    user-select: none;
  }

  .ml-bulk-select-all input {
    cursor: pointer;
    width: 15px;
    height: 15px;
    accent-color: #3350E0;
  }

  .ml-bulk-actions {
    display: none;
    align-items: center;
    gap: 10px;
  }

  .ml-bulk-actions.show {
    display: flex;
  }

  .ml-bulk-count {
    font-weight: 600;
    color: #15171C;
  }

  .ml-bulk-actions .ml-btn {
    padding: 6px 12px;
    font-size: 12.5px;
  }

  .ml-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    flex-wrap: wrap;
  }

  .ml-search {
    position: relative;
    flex: 1;
    min-width: 220px;
    max-width: 340px;
  }

  .ml-search input {
    width: 100%;
    padding: 9px 12px 9px 34px;
    border: 1px solid #CDD2DB;
    border-radius: 8px;
    font-size: 13.5px;
    background: #fff;
    color: #15171C;
    outline: none;
  }

  .ml-search input:focus {
    border-color: #3350E0;
    box-shadow: 0 0 0 3px #EBF0FE;
  }

  .ml-search svg {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #98A0AC;
  }

  .ml-pills {
    display: flex;
    gap: 6px;
    background: #fff;
    border: 1px solid #E4E7EC;
    padding: 3px;
    border-radius: 9px;
  }

  .ml-pill {
    padding: 6px 13px;
    font-size: 13px;
    font-weight: 500;
    color: #5B6472;
    border-radius: 6px;
    cursor: pointer;
    border: none;
    background: transparent;
  }

  .ml-pill.active {
    background: #15171C;
    color: #fff;
  }

  .ml-spacer {
    flex: 1;
  }

  .ml-toolbar select {
    padding: 8px 12px;
    border: 1px solid #CDD2DB;
    border-radius: 8px;
    font-size: 13.5px;
    color: #15171C;
    background: #fff;
    outline: none;
  }

  .ml-view-toggle {
    display: flex;
    border: 1px solid #CDD2DB;
    border-radius: 8px;
    overflow: hidden;
  }

  .ml-view-toggle button {
    border: none;
    background: #fff;
    padding: 8px 10px;
    cursor: pointer;
    color: #98A0AC;
  }

  .ml-view-toggle button.active {
    background: #FAFBFC;
    color: #15171C;
  }

  .ml-view-toggle button:not(:last-child) {
    border-right: 1px solid #E4E7EC;
  }

  .ml-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
  }

  .ml-grid.hidden {
    display: none;
  }

  .ml-card {
    background: #fff;
    border: 1px solid #E4E7EC;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: border-color .12s, box-shadow .12s;
  }

  .ml-card:hover {
    border-color: #CDD2DB;
    box-shadow: 0 2px 10px rgba(15, 18, 25, 0.06);
  }

  .ml-card.selected {
    border-color: #3350E0;
    box-shadow: 0 0 0 2px #EBF0FE;
  }

  .ml-thumb {
    height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    color: #fff;
    overflow: hidden;
  }

  .ml-thumb .type-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.45);
    color: #fff;
    font-size: 10.5px;
    font-weight: 600;
    padding: 3px 7px;
    border-radius: 5px;
    text-transform: uppercase;
    letter-spacing: .03em;
  }

  .ml-card-checkbox {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 2;
    width: 17px;
    height: 17px;
    cursor: pointer;
    accent-color: #3350E0;
  }

  .ml-thumb .dur {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    font-size: 10.5px;
    padding: 2px 6px;
    border-radius: 4px;
  }

  .ml-card-body {
    padding: 10px 12px 12px;
  }

  .ml-card-name {
    font-size: 12.5px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .ml-card-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 6px;
  }

  .ml-card-size {
    font-size: 11.5px;
    color: #98A0AC;
  }

  .ml-usage-chip {
    font-size: 11px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 20px;
  }

  .ml-usage-chip.used {
    background: #E7F5EE;
    color: #1E8A5F;
  }

  .ml-usage-chip.unused {
    background: #FBEAE7;
    color: #D5432E;
  }

  .ml-list {
    display: none;
    background: #fff;
    border: 1px solid #E4E7EC;
    border-radius: 10px;
    overflow: hidden;
  }

  .ml-list.active {
    display: block;
  }

  .ml-list-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 16px;
    border-bottom: 1px solid #E4E7EC;
    cursor: pointer;
  }

  .ml-list-row:last-child {
    border-bottom: none;
  }

  .ml-list-row:hover {
    background: #FAFBFC;
  }

  .ml-list-row.selected {
    background: #EBF0FE;
  }

  .ml-list-checkbox {
    flex-shrink: 0;
    width: 15px;
    height: 15px;
    cursor: pointer;
    accent-color: #3350E0;
  }

  .ml-list-thumb {
    width: 42px;
    height: 42px;
    border-radius: 6px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    position: relative;
    overflow: hidden;
  }

  .ml-list-name {
    font-size: 13px;
    font-weight: 500;
    flex: 1;
    min-width: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .ml-list-col {
    font-size: 12.5px;
    color: #5B6472;
    width: 110px;
    flex-shrink: 0;
  }

  .ml-scrim {
    position: fixed;
    inset: 0;
    background: rgba(15, 18, 25, 0.35);
    opacity: 0;
    pointer-events: none;
    transition: opacity .18s;
    z-index: 40;
  }

  .ml-scrim.open {
    opacity: 1;
    pointer-events: auto;
  }

  .ml-drawer {
    position: fixed;
    top: 0;
    right: 0;
    height: 100%;
    width: 420px;
    background: #fff;
    box-shadow: 0 20px 50px rgba(15, 18, 25, 0.16);
    transform: translateX(100%);
    transition: transform .2s ease;
    z-index: 41;
    display: flex;
    flex-direction: column;
  }

  .ml-drawer.open {
    transform: translateX(0);
  }

  .ml-drawer-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 1px solid #E4E7EC;
  }

  .ml-drawer-head b {
    font-size: 14px;
  }

  .ml-icon-btn {
    border: none;
    background: transparent;
    cursor: pointer;
    color: #98A0AC;
    padding: 4px;
    border-radius: 6px;
    display: flex;
  }

  .ml-icon-btn:hover {
    background: #FAFBFC;
    color: #15171C;
  }

  .ml-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
  }

  .ml-preview {
    height: 190px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    margin-bottom: 14px;
    position: relative;
    overflow: hidden;
  }

  .ml-fname {
    font-size: 14.5px;
    font-weight: 600;
    word-break: break-word;
  }

  .ml-furl {
    font-size: 11.5px;
    color: #98A0AC;
    margin-bottom: 16px;
    word-break: break-all;
  }

  .ml-section-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #98A0AC;
    margin: 20px 0 10px;
  }

  .ml-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .ml-meta-item .k {
    font-size: 11.5px;
    color: #98A0AC;
  }

  .ml-meta-item .v {
    font-size: 13px;
    font-weight: 500;
    margin-top: 2px;
  }

  .ml-usage-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .ml-usage-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    background: #FAFBFC;
    border: 1px solid #E4E7EC;
    border-radius: 8px;
  }

  .ml-usage-icon {
    width: 26px;
    height: 26px;
    border-radius: 6px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
  }

  .ml-usage-text .title {
    font-size: 12.5px;
    font-weight: 500;
  }

  .ml-usage-text .sub {
    font-size: 11.5px;
    color: #98A0AC;
    margin-top: 1px;
  }

  .ml-empty-usage {
    text-align: center;
    padding: 18px 10px;
    color: #98A0AC;
    font-size: 12.5px;
    background: #FAFBFC;
    border: 1px dashed #CDD2DB;
    border-radius: 8px;
  }

  .ml-file-preview {
    border: 1px solid #E4E7EC;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
  }

  .ml-drawer-actions {
    display: flex;
    gap: 8px;
    padding: 16px 18px;
    border-top: 1px solid #E4E7EC;
  }

  .ml-drawer-actions .ml-btn {
    flex: 1;
    justify-content: center;
  }

  .ml-modal-scrim {
    position: fixed;
    inset: 0;
    background: rgba(15, 18, 25, 0.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 60;
  }

  .ml-modal-scrim.open {
    display: flex;
  }

  .ml-modal {
    width: 440px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 20px 50px rgba(15, 18, 25, 0.16);
    padding: 22px;
  }

  .ml-modal h3 {
    font-size: 16px;
    margin: 0 0 4px;
    font-weight: 600;
  }

  .ml-modal p.desc {
    font-size: 13px;
    color: #5B6472;
    margin: 0 0 16px;
  }

  .ml-modal label {
    font-size: 12px;
    font-weight: 600;
    color: #5B6472;
    display: block;
    margin-bottom: 6px;
  }

  .ml-modal input[type=text] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #CDD2DB;
    border-radius: 8px;
    font-size: 13.5px;
    outline: none;
    margin-bottom: 10px;
  }

  .ml-modal input[type=text]:focus {
    border-color: #3350E0;
    box-shadow: 0 0 0 3px #EBF0FE;
  }

  .ml-seo-tip {
    background: #EBF0FE;
    border: 1px solid #C7D3FB;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 12px;
    color: #233A9E;
    margin-bottom: 14px;
    line-height: 1.5;
  }

  .ml-impact-box {
    background: #FAFBFC;
    border: 1px solid #E4E7EC;
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 16px;
  }

  .ml-impact-box .h {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 6px;
  }

  .ml-impact-box ul {
    margin: 0;
    padding-left: 18px;
    font-size: 12px;
    color: #5B6472;
  }

  .ml-warn-box {
    display: flex;
    gap: 10px;
    background: #FBEAE7;
    border: 1px solid #F2C3BA;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 14px;
  }

  .ml-warn-box svg {
    color: #D5432E;
    flex-shrink: 0;
    margin-top: 1px;
  }

  .ml-warn-box .t {
    font-size: 13px;
    font-weight: 600;
    color: #8C2E1B;
  }

  .ml-warn-box .s {
    font-size: 12px;
    color: #8C2E1B;
    margin-top: 2px;
    line-height: 1.5;
  }

  .ml-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 6px;
  }

  .ml-check-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: #5B6472;
    margin-bottom: 16px;
  }

  .ml-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-top: 20px;
    flex-wrap: wrap;
  }

  .ml-page-info {
    font-size: 12.5px;
    color: #5B6472;
  }

  .ml-page-size {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: #5B6472;
  }

  .ml-page-size select {
    padding: 5px 8px;
    border: 1px solid #CDD2DB;
    border-radius: 6px;
    font-size: 12.5px;
    background: #fff;
    color: #15171C;
    outline: none;
  }

  .ml-page-btns {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .ml-page-btn {
    min-width: 30px;
    height: 30px;
    padding: 0 6px;
    border: 1px solid #E4E7EC;
    background: #fff;
    color: #5B6472;
    border-radius: 7px;
    font-size: 12.5px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .ml-page-btn:hover:not(:disabled) {
    border-color: #CDD2DB;
    background: #FAFBFC;
  }

  .ml-page-btn.active {
    background: #15171C;
    border-color: #15171C;
    color: #fff;
  }

  .ml-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
  }

  .ml-page-ellipsis {
    width: 20px;
    text-align: center;
    color: #98A0AC;
    font-size: 12.5px;
  }

  .ml-impact-list {
    max-height: 220px;
    overflow-y: auto;
    margin-top: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #fff;
  }

  .ml-impact-list ul {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .ml-impact-list li {
    padding: 10px 14px;
    border-bottom: 1px solid #f1f3f5;
  }

  .ml-impact-list li:last-child {
    border-bottom: none;
  }

  .ml-impact-list li strong {
    display: block;
    font-size: 14px;
    color: #222;
  }

  .ml-impact-list li span {
    font-size: 12px;
    color: #777;
  }
</style>

<div class="ml-wrap">

  <div class="ml-crumb">Content management <span>/</span> <span>Media library</span></div>
  <div class="ml-header">
    <div>
      <h1>Media library</h1>
      <p class="ml-subtitle">Every image, video and file used across pages, questions, stories and courses — in one place.</p>
    </div>
    <button class="ml-btn ml-btn-primary" id="mlUploadBtn">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <path d="M12 5v14M5 12h14" />
      </svg>
      Upload files
    </button>
  </div>

  <div class="ml-stats">
    <div class="ml-stat">
      <div class="n"><?= (int)$stats['total'] ?></div>
      <div class="l">Total files</div>
    </div>
    <div class="ml-stat">
      <div class="n"><?= htmlspecialchars($stats['storage']) ?></div>
      <div class="l">Storage used</div>
    </div>
    <div class="ml-stat ml-stat-clickable" id="mlConnectedStat" data-filter="connected">
      <div class="n"><?= (int)$stats['connected'] ?></div>
      <div class="l">Connected to content</div>
    </div>
    <div class="ml-stat warn ml-stat-clickable" id="mlUnusedStat" data-filter="unused">
      <div class="n"><?= (int)$stats['unused'] ?></div>
      <div class="l">Unused files</div>
    </div>
  </div>

  <div class="ml-toolbar">
    <div class="ml-search">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <circle cx="11" cy="11" r="7" />
        <path d="m21 21-4.3-4.3" />
      </svg>
      <input id="mlSearchInput" type="text" placeholder="Search by filename…">
    </div>
    <div class="ml-pills" id="mlTypePills">
      <button class="ml-pill active" data-type="all">All</button>
      <button class="ml-pill" data-type="image">Images</button>
      <button class="ml-pill" data-type="video">Videos</button>
      <button class="ml-pill" data-type="file">Files</button>
    </div>
    <div class="ml-spacer"></div>
    <select id="mlSortSelect">
      <option value="recent">Recently uploaded</option>
      <option value="name">Name A–Z</option>
      <option value="usage">Most used</option>
    </select>
    <div class="ml-view-toggle">
      <button id="mlGridBtn" class="active" aria-label="Grid view">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7" />
          <rect x="14" y="3" width="7" height="7" />
          <rect x="3" y="14" width="7" height="7" />
          <rect x="14" y="14" width="7" height="7" />
        </svg>
      </button>
      <button id="mlListBtn" aria-label="List view">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
  </div>

  <div class="ml-active-filter-bar" id="mlActiveFilterBar">
    <span id="mlActiveFilterLabel"></span>
    <span class="clear" id="mlClearFilterBtn">Clear</span>
  </div>

  <div class="ml-bulk-bar" id="mlBulkBar">
    <label class="ml-bulk-select-all">
      <input type="checkbox" id="mlSelectAllCheckbox">
      <span>Select all on this page</span>
    </label>
    <div class="ml-bulk-actions" id="mlBulkActions">
      <span class="ml-bulk-count" id="mlBulkCount"></span>
      <button class="ml-btn ml-btn-danger" id="mlBulkDeleteBtn">Delete selected</button>
      <button class="ml-btn" id="mlBulkClearBtn">Clear selection</button>
    </div>
  </div>

  <div class="ml-grid" id="mlGrid"></div>
  <div class="ml-list" id="mlList"></div>

  <div class="ml-pagination">
    <div class="ml-page-info" id="mlPageInfo"></div>
    <div class="ml-page-btns" id="mlPageBtns"></div>
    <div class="ml-page-size">
      Show
      <select id="mlPageSizeSelect">
        <option value="24">24</option>
        <option value="48">48</option>
        <option value="96">96</option>
      </select>
      per page
    </div>
  </div>

</div>

<div class="ml-scrim" id="mlScrim"></div>
<div class="ml-drawer" id="mlDrawer"></div>

<div class="ml-modal-scrim" id="mlRenameScrim">
  <div class="ml-modal" id="mlRenameModal"></div>
</div>
<div class="ml-modal-scrim" id="mlDeleteScrim">
  <div class="ml-modal" id="mlDeleteModal"></div>
</div>

<script>
  /* Data comes from the PHP backend. Swap the source, not this rendering
   code, once real DB-linked usage is wired in. */
  const mlItems = <?php echo json_encode($mediaItems, JSON_UNESCAPED_SLASHES); ?>;

  const ML_TYPE_META = {
    image: {
      color: '#fff',
      label: 'Image'
    },
    video: {
      color: '#3350E0',
      label: 'Video'
    },
    file: {
      color: '#6D5BD0',
      label: 'File'
    },
  };

  let mlCurrentType = 'all';
  let mlUsageFilter = 'all'; // 'all' | 'connected' | 'unused'
  let mlSelectedIds = new Set(); // multi-select state, persists across pages/filters

  function mlThumbStyle(type) {
    if (type === 'image') {
      return 'background:#fff;';
    }

    const c = ML_TYPE_META[type].color;
    const n = parseInt(c.slice(1), 16);
    const r = Math.max((n >> 16) - 30, 0);
    const g = Math.max(((n >> 8) & 0xff) - 30, 0);
    const b = Math.max((n & 0xff) - 30, 0);

    return `background:linear-gradient(135deg, ${c}, rgb(${r},${g},${b}));`;
  }

  function mlTypeSvg(type, size) {
    size = size || 26;
    if (type === 'video') return `<svg width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>`;
    if (type === 'file') return `<svg width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>`;
    return `<svg width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>`;
  }

  // Renders a real thumbnail for images (always) and video (only once this page
  // has captured a poster frame for it — see mlGenerateMissingThumbnails). Until
  // then, video cards just show the static icon, exactly like before — never a
  // live/playing video.
  // interactive=true (drawer only) renders a real playable <video controls>.
  function mlMediaPreview(it, size, interactive) {
    if (it.type === 'image') {
      return `
      <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding:4px;box-sizing:border-box;">
        <img src="${it.url}" alt="${it.name.replace(/"/g,'&quot;')}" loading="lazy"
             style="max-width:100%;max-height:100%;width:auto;height:auto;object-fit:contain;border:2px solid #CDD2DB;border-radius:4px;box-sizing:border-box;"
             onerror="this.style.display='none';this.parentElement.style.display='none';this.nextElementSibling.style.display='flex';">
      </div>
      <div style="display:none;position:absolute;inset:0;align-items:center;justify-content:center;">${mlTypeSvg('image', size)}</div>`;
    }

    if (it.type === 'video') {
      if (interactive) {
        return `
        <video src="${it.url}" controls preload="metadata" playsinline
               style="width:100%;height:100%;object-fit:contain;background:#000;"
               onclick="event.stopPropagation();"></video>`;
      }
      if (mlThumbCache.has(it.id)) {
        return `<img src="${mlThumbCache.get(it.id)}" alt="${it.name.replace(/"/g,'&quot;')}" style="width:100%;height:100%;object-fit:contain;">`;
      }
      // No captured frame yet — show the plain icon (same as before), never the live video.
      return `<div class="ml-thumb-pending" data-thumb-for="${it.id}" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">${mlTypeSvg('video', size)}</div>`;
    }

    return mlTypeSvg('file', size);
  }

  function mlRenderGrid(list) {
    document.getElementById('mlGrid').innerHTML = list.map(it => `
    <div class="ml-card ${mlSelectedIds.has(it.id) ? 'selected' : ''}" onclick="mlOpenDrawer(${it.id})">
      <div class="ml-thumb" style="${mlThumbStyle(it.type)}">
        <span class="type-badge">${ML_TYPE_META[it.type].label}</span>
        <input type="checkbox" class="ml-card-checkbox" data-id="${it.id}" ${mlSelectedIds.has(it.id) ? 'checked' : ''}
               onclick="event.stopPropagation(); mlToggleSelect(${it.id}, this.checked)"
               aria-label="Select ${it.name.replace(/"/g,'&quot;')}">
        ${mlMediaPreview(it, 26)}
        ${it.duration ? `<span class="dur">${it.duration}</span>` : ''}
      </div>
      <div class="ml-card-body">
        <div class="ml-card-name ml-mono">${it.name}</div>
        <div class="ml-card-meta">
          <span class="ml-card-size">${it.size}</span>
          <span class="ml-usage-chip ${it.used_in.length? 'used':'unused'}">${it.used_in.length ? it.used_in.length + ' place'+(it.used_in.length>1?'s':'') : 'Unused'}</span>
        </div>
      </div>
    </div>
  `).join('');
  }

  function mlRenderList(list) {
    document.getElementById('mlList').innerHTML = list.map(it => `
    <div class="ml-list-row ${mlSelectedIds.has(it.id) ? 'selected' : ''}" onclick="mlOpenDrawer(${it.id})">
      <input type="checkbox" class="ml-list-checkbox" data-id="${it.id}" ${mlSelectedIds.has(it.id) ? 'checked' : ''}
             onclick="event.stopPropagation(); mlToggleSelect(${it.id}, this.checked)"
             aria-label="Select ${it.name.replace(/"/g,'&quot;')}">
      <div class="ml-list-thumb" style="${mlThumbStyle(it.type)}">${mlMediaPreview(it, 18)}</div>
      <div class="ml-list-name ml-mono">${it.name}</div>
      <div class="ml-list-col">${ML_TYPE_META[it.type].label}</div>
      <div class="ml-list-col">${it.size}</div>
      <div class="ml-list-col">${it.date}</div>
      <div class="ml-list-col"><span class="ml-usage-chip ${it.used_in.length? 'used':'unused'}">${it.used_in.length ? it.used_in.length + ' place'+(it.used_in.length>1?'s':'') : 'Unused'}</span></div>
    </div>
  `).join('');
  }

  /* ---------------------------------------------------------------------
     Multi-select — checkboxes on each card/row + a page-wise "select all".
     Selection (mlSelectedIds) persists across page changes and filters,
     so switching pages doesn't lose what you picked on an earlier page.
     The "select all" checkbox only reflects/affects the CURRENT page.
     ------------------------------------------------------------------- */
  function mlCurrentPageItems() {
    const start = (mlCurrentPage - 1) * mlPageSize;
    return mlFilteredList.slice(start, start + mlPageSize);
  }

  function mlToggleSelect(id, checked) {
    if (checked) mlSelectedIds.add(id);
    else mlSelectedIds.delete(id);

    // Keep the card/row's own highlight in sync without a full re-render
    document.querySelectorAll(`.ml-card-checkbox[data-id="${id}"]`).forEach(cb => {
      cb.closest('.ml-card').classList.toggle('selected', checked);
    });
    document.querySelectorAll(`.ml-list-checkbox[data-id="${id}"]`).forEach(cb => {
      cb.closest('.ml-list-row').classList.toggle('selected', checked);
    });

    mlUpdateBulkBar();
  }

  function mlUpdateBulkBar() {
    const pageItems = mlCurrentPageItems();
    const pageIds = pageItems.map(it => it.id);
    const selectedOnPage = pageIds.filter(id => mlSelectedIds.has(id));

    const selectAllCb = document.getElementById('mlSelectAllCheckbox');
    if (pageIds.length === 0 || selectedOnPage.length === 0) {
      selectAllCb.checked = false;
      selectAllCb.indeterminate = false;
    } else if (selectedOnPage.length === pageIds.length) {
      selectAllCb.checked = true;
      selectAllCb.indeterminate = false;
    } else {
      selectAllCb.checked = false;
      selectAllCb.indeterminate = true;
    }

    const actions = document.getElementById('mlBulkActions');
    const countEl = document.getElementById('mlBulkCount');
    if (mlSelectedIds.size > 0) {
      actions.classList.add('show');
      countEl.textContent = `${mlSelectedIds.size} selected`;
    } else {
      actions.classList.remove('show');
    }
  }

  document.getElementById('mlSelectAllCheckbox').addEventListener('change', function() {
    const pageItems = mlCurrentPageItems();
    if (this.checked) {
      pageItems.forEach(it => mlSelectedIds.add(it.id));
    } else {
      pageItems.forEach(it => mlSelectedIds.delete(it.id));
    }
    mlRenderGrid(pageItems);
    mlRenderList(pageItems);
    mlUpdateBulkBar();
  });

  document.getElementById('mlBulkClearBtn').addEventListener('click', () => {
    mlSelectedIds.clear();
    mlRenderPage();
  });

  document.getElementById('mlBulkDeleteBtn').addEventListener('click', mlOpenBulkDelete);

  /* ---------------------------------------------------------------------
     Video thumbnail capture — runs invisibly in the background, entirely
     client-side (no server round trip, no new PHP files needed).
     The capture <video> is pushed off-screen (position:fixed, 1x1px,
     opacity:0) so the visible card ALWAYS just shows the icon placeholder
     until the captured frame is ready to swap in. Nothing plays on screen,
     ever, no matter how long capture takes.
     Captured frames live in mlThumbCache for this page load only — they're
     re-captured on the next page load rather than persisted to disk.
     ------------------------------------------------------------------- */
  const mlThumbCache = new Map(); // item id -> data URL
  const mlThumbInFlight = new Set();

  function mlCaptureVideoFrame(videoUrl) {
    return new Promise((resolve, reject) => {
      const video = document.createElement('video');
      video.muted = true;
      video.preload = 'auto';
      video.playsInline = true;
      video.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:1px;height:1px;opacity:0;pointer-events:none;';
      video.src = videoUrl;

      const cleanupAndReject = (err) => {
        video.remove();
        reject(err);
      };

      video.addEventListener('loadeddata', () => {
        try {
          video.currentTime = Math.min(1, (video.duration || 2) / 4);
        } catch (e) {
          cleanupAndReject(e);
        }
      });
      video.addEventListener('seeked', () => {
        try {
          const canvas = document.createElement('canvas');
          canvas.width = video.videoWidth || 320;
          canvas.height = video.videoHeight || 180;
          canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
          const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
          video.remove();
          resolve(dataUrl);
        } catch (e) {
          cleanupAndReject(e);
        }
      });
      video.addEventListener('error', () => cleanupAndReject(new Error('video load error')));

      document.body.appendChild(video);
    });
  }

  async function mlGenerateMissingThumbnails(items) {
    for (const it of items) {
      if (it.type !== 'video' || mlThumbCache.has(it.id) || mlThumbInFlight.has(it.id)) continue;
      mlThumbInFlight.add(it.id);

      try {
        const dataUrl = await mlCaptureVideoFrame(it.url);
        mlThumbCache.set(it.id, dataUrl);
        document.querySelectorAll(`[data-thumb-for="${it.id}"]`).forEach(el => {
          el.outerHTML = `<img src="${dataUrl}" alt="" style="width:100%;height:100%;object-fit:contain;">`;
        });
      } catch (e) {
        // silent — card just keeps showing the icon placeholder, no user-facing error needed
      } finally {
        mlThumbInFlight.delete(it.id);
      }
    }
  }

  let mlFilteredList = mlItems.slice();
  let mlCurrentPage = 1;
  let mlPageSize = 24;

  /* ---------------------------------------------------------------------
     Drawer file preview — PDF, text/CSV, Word (.docx), Excel (.xlsx/.xls).
     mammoth.js / SheetJS load from CDN only the first time they're
     actually needed, not on every page load.
     ------------------------------------------------------------------- */
  const mlScriptCache = {};

  function mlLoadScript(src) {
    if (mlScriptCache[src]) return mlScriptCache[src];
    mlScriptCache[src] = new Promise((resolve, reject) => {
      const s = document.createElement('script');
      s.src = src;
      s.onload = resolve;
      s.onerror = () => reject(new Error('Failed to load ' + src));
      document.head.appendChild(s);
    });
    return mlScriptCache[src];
  }

  function mlFileExt(name) {
    const m = name.match(/\.([a-z0-9]+)$/i);
    return m ? m[1].toLowerCase() : '';
  }

  function mlEscapeHtml(str) {
    return str.replace(/[&<>"']/g, c => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    } [c]));
  }

  function mlCsvToTable(text) {
    // Simple comma split — good enough for a quick admin preview, not full RFC-4180 (won't handle quoted commas)
    const lines = text.split(/\r?\n/).filter(l => l.length > 0).slice(0, 200);
    const rows = lines.map(line => line.split(','));
    let html = '<div style="max-height:320px;overflow:auto;padding:4px;"><table style="border-collapse:collapse;width:100%;font-size:12px;">';
    rows.forEach((cols, i) => {
      const tag = i === 0 ? 'th' : 'td';
      const cellStyle = `border:1px solid #E4E7EC;padding:4px 8px;text-align:left;${i===0?'background:#FAFBFC;font-weight:600;':''}`;
      html += '<tr>' + cols.map(c => `<${tag} style="${cellStyle}">${mlEscapeHtml(c)}</${tag}>`).join('') + '</tr>';
    });
    html += '</table></div>';
    return html;
  }

  function mlOpenFileLink(it) {
    return `<a href="${it.url}" target="_blank" rel="noopener" style="color:#3350E0;">Open the file directly</a>`;
  }

  async function mlRenderFilePreview(it) {
    const box = document.getElementById('mlFilePreviewBox');
    if (!box) return; // drawer may have been closed already
    const ext = mlFileExt(it.name);

    try {
      if (ext === 'pdf') {
        box.innerHTML = `<iframe src="${it.url}" style="width:100%;height:400px;border:none;display:block;"></iframe>`;
        return;
      }

      if (['txt', 'json', 'log', 'md'].includes(ext)) {
        const res = await fetch(it.url);
        const text = await res.text();
        box.innerHTML = `<pre style="margin:0;padding:14px;font-size:12px;line-height:1.5;white-space:pre-wrap;word-break:break-word;max-height:320px;overflow:auto;">${mlEscapeHtml(text.slice(0, 20000))}</pre>`;
        return;
      }

      if (ext === 'csv') {
        const res = await fetch(it.url);
        const text = await res.text();
        box.innerHTML = mlCsvToTable(text);
        return;
      }

      if (ext === 'docx') {
        await mlLoadScript('https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js');
        const res = await fetch(it.url);
        const buf = await res.arrayBuffer();
        const result = await mammoth.convertToHtml({
          arrayBuffer: buf
        });
        box.innerHTML = `<div style="padding:14px;font-size:13px;line-height:1.6;max-height:320px;overflow:auto;">${result.value}</div>`;
        return;
      }

      if (ext === 'doc') {
        box.innerHTML = `<div style="padding:14px;font-size:12.5px;color:#98A0AC;">Preview isn't available for legacy .doc files. ${mlOpenFileLink(it)}.</div>`;
        return;
      }

      if (['xlsx', 'xls'].includes(ext)) {
        await mlLoadScript('https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js');
        const res = await fetch(it.url);
        const buf = await res.arrayBuffer();
        const wb = XLSX.read(buf, {
          type: 'array'
        });
        const sheetHtml = XLSX.utils.sheet_to_html(wb.Sheets[wb.SheetNames[0]], {
          id: 'mlXlsxTable'
        });
        box.innerHTML = `<div style="padding:10px;max-height:320px;overflow:auto;font-size:12px;">${sheetHtml}</div>
        <style>#mlXlsxTable{border-collapse:collapse;width:100%;} #mlXlsxTable td,#mlXlsxTable th{border:1px solid #E4E7EC;padding:4px 8px;white-space:nowrap;}</style>`;
        return;
      }

      box.innerHTML = `<div style="padding:14px;font-size:12.5px;color:#98A0AC;">No preview available for this file type. ${mlOpenFileLink(it)}.</div>`;
    } catch (e) {
      box.innerHTML = `<div style="padding:14px;font-size:12.5px;color:#D5432E;">Couldn't load a preview. ${mlOpenFileLink(it)}.</div>`;
    }
  }

  function mlApplyFilters() {
    const q = document.getElementById('mlSearchInput').value.toLowerCase();
    const sort = document.getElementById('mlSortSelect').value;
    let list = mlItems.filter(it =>
      (mlCurrentType === 'all' || it.type === mlCurrentType) &&
      it.name.toLowerCase().includes(q) &&
      (mlUsageFilter === 'all' || (mlUsageFilter === 'connected' ? it.used_in.length > 0 : it.used_in.length === 0))
    );
    if (sort === 'name') list = list.slice().sort((a, b) => a.name.localeCompare(b.name));
    if (sort === 'usage') list = list.slice().sort((a, b) => b.used_in.length - a.used_in.length);
    mlFilteredList = list;
    mlCurrentPage = 1;
    mlRenderPage();
  }

  function mlSetUsageFilter(f) {
    mlUsageFilter = (mlUsageFilter === f) ? 'all' : f; // clicking the active one toggles it off
    mlUpdateUsageFilterUI();
    mlApplyFilters();
  }

  function mlUpdateUsageFilterUI() {
    document.getElementById('mlConnectedStat').classList.toggle('active', mlUsageFilter === 'connected');
    document.getElementById('mlUnusedStat').classList.toggle('active', mlUsageFilter === 'unused');

    const bar = document.getElementById('mlActiveFilterBar');
    const label = document.getElementById('mlActiveFilterLabel');
    if (mlUsageFilter === 'all') {
      bar.classList.remove('show');
    } else {
      label.textContent = mlUsageFilter === 'connected' ?
        'Showing files connected to content' :
        'Showing unused files';
      bar.classList.add('show');
    }
  }

  document.getElementById('mlConnectedStat').addEventListener('click', () => mlSetUsageFilter('connected'));
  document.getElementById('mlUnusedStat').addEventListener('click', () => mlSetUsageFilter('unused'));
  document.getElementById('mlClearFilterBtn').addEventListener('click', () => mlSetUsageFilter(mlUsageFilter));

  function mlRenderPage() {
    const total = mlFilteredList.length;
    const totalPages = Math.max(1, Math.ceil(total / mlPageSize));
    if (mlCurrentPage > totalPages) mlCurrentPage = totalPages;
    if (mlCurrentPage < 1) mlCurrentPage = 1;

    const start = (mlCurrentPage - 1) * mlPageSize;
    const pageItems = mlFilteredList.slice(start, start + mlPageSize);

    mlRenderGrid(pageItems);
    mlRenderList(pageItems);
    mlRenderPagination(total, totalPages, start, pageItems.length);
    mlGenerateMissingThumbnails(pageItems); // only the current page, not the whole library
    mlUpdateBulkBar();
  }

  function mlRenderPagination(total, totalPages, start, pageCount) {
    const infoEl = document.getElementById('mlPageInfo');
    const btnsEl = document.getElementById('mlPageBtns');

    infoEl.textContent = total === 0 ?
      'No files found' :
      `Showing ${start + 1}–${start + pageCount} of ${total}`;

    if (totalPages <= 1) {
      btnsEl.innerHTML = '';
      return;
    }

    const pages = [];
    const windowSize = 1;
    for (let p = 1; p <= totalPages; p++) {
      if (p === 1 || p === totalPages || Math.abs(p - mlCurrentPage) <= windowSize) {
        pages.push(p);
      } else if (pages[pages.length - 1] !== '…') {
        pages.push('…');
      }
    }

    let html = `<button class="ml-page-btn" id="mlPrevBtn" ${mlCurrentPage===1?'disabled':''} aria-label="Previous page">‹</button>`;
    pages.forEach(p => {
      if (p === '…') {
        html += `<span class="ml-page-ellipsis">…</span>`;
        return;
      }
      html += `<button class="ml-page-btn ${p===mlCurrentPage?'active':''}" data-page="${p}">${p}</button>`;
    });
    html += `<button class="ml-page-btn" id="mlNextBtn" ${mlCurrentPage===totalPages?'disabled':''} aria-label="Next page">›</button>`;

    btnsEl.innerHTML = html;
    document.getElementById('mlPrevBtn').addEventListener('click', () => {
      mlCurrentPage--;
      mlRenderPage();
    });
    document.getElementById('mlNextBtn').addEventListener('click', () => {
      mlCurrentPage++;
      mlRenderPage();
    });
    btnsEl.querySelectorAll('[data-page]').forEach(btn => {
      btn.addEventListener('click', () => {
        mlCurrentPage = parseInt(btn.dataset.page, 10);
        mlRenderPage();
      });
    });
  }

  document.getElementById('mlPageSizeSelect').addEventListener('change', function() {
    mlPageSize = parseInt(this.value, 10);
    mlCurrentPage = 1;
    mlRenderPage();
  });

  document.getElementById('mlSearchInput').addEventListener('input', mlApplyFilters);
  document.getElementById('mlSortSelect').addEventListener('change', mlApplyFilters);
  document.getElementById('mlTypePills').addEventListener('click', (e) => {
    const btn = e.target.closest('.ml-pill');
    if (!btn) return;
    mlCurrentType = btn.dataset.type;
    document.querySelectorAll('#mlTypePills .ml-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    mlApplyFilters();
  });
  document.getElementById('mlGridBtn').addEventListener('click', () => mlSetView('grid'));
  document.getElementById('mlListBtn').addEventListener('click', () => mlSetView('list'));

  function mlSetView(v) {
    document.getElementById('mlGrid').classList.toggle('hidden', v !== 'grid');
    document.getElementById('mlList').classList.toggle('active', v === 'list');
    document.getElementById('mlGridBtn').classList.toggle('active', v === 'grid');
    document.getElementById('mlListBtn').classList.toggle('active', v === 'list');
  }

  // Upload functionality
  document.getElementById('mlUploadBtn').addEventListener('click', function() {
    // Create a file input element
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.multiple = true;
    fileInput.accept = 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.json,.log,.md';

    fileInput.addEventListener('change', async function() {
      if (this.files.length === 0) return;

      const formData = new FormData();
      formData.append('action', 'upload');

      for (let i = 0; i < this.files.length; i++) {
        formData.append('files[]', this.files[i]);
      }

      // Show loading state
      const uploadBtn = document.getElementById('mlUploadBtn');
      const originalText = uploadBtn.innerHTML;
      uploadBtn.innerHTML = 'Uploading...';
      uploadBtn.disabled = true;

      try {
        const res = await fetch(window.location.href, {
          method: 'POST',
          body: formData
        });

        const data = await res.json();

        if (data.success) {
          // Refresh the page to show new files
          if (data.uploaded && data.uploaded.length > 0) {
            alert(`Successfully uploaded ${data.uploaded.length} file(s).`);
            // Reload the page to show new files
            location.reload();
          } else if (data.errors && data.errors.length > 0) {
            alert(`Uploaded with errors: ${data.errors.join(', ')}`);
          }
        } else {
          alert('Upload failed: ' + (data.message || 'Unknown error'));
        }
      } catch (e) {
        alert('Network error during upload. Please try again.');
      } finally {
        uploadBtn.innerHTML = originalText;
        uploadBtn.disabled = false;
      }
    });

    fileInput.click();
  });

  function mlOpenDrawer(id) {
    const it = mlItems.find(i => i.id === id);
    const drawer = document.getElementById('mlDrawer');
    drawer.innerHTML = `
    <div class="ml-drawer-head">
      <b>File details</b>
      <button class="ml-icon-btn" onclick="mlCloseDrawer()" aria-label="Close">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="ml-drawer-body">
      <div class="ml-preview" style="${mlThumbStyle(it.type)}">${mlMediaPreview(it, 34, true)}</div>
      <div class="ml-fname ml-mono">${it.name}</div>
      <div class="ml-furl">${it.path}</div>

      <div class="ml-section-label">File info</div>
      <div class="ml-meta-grid">
        <div class="ml-meta-item"><div class="k">Type</div><div class="v">${ML_TYPE_META[it.type].label}</div></div>
        <div class="ml-meta-item"><div class="k">Size</div><div class="v">${it.size}</div></div>
        <div class="ml-meta-item"><div class="k">Dimensions</div><div class="v">${it.dims}</div></div>
        <div class="ml-meta-item"><div class="k">Uploaded</div><div class="v">${it.date}</div></div>
      </div>

      ${it.type === 'file' ? `
      <div class="ml-section-label">Preview</div>
      <div class="ml-file-preview" id="mlFilePreviewBox">
        <div style="padding:14px;font-size:12.5px;color:#98A0AC;">Loading preview…</div>
      </div>
      ` : ''}

      <div class="ml-section-label">Used in (${it.used_in.length})</div>
      ${it.used_in.length ? `<div class="ml-usage-list">${it.used_in.map(u=>`
        <div class="ml-usage-item">
          <div class="ml-usage-icon" style="background:#3350E01A;color:#3350E0;">${u.table.replace('edu_','').slice(0,2).toUpperCase()}</div>
          <div class="ml-usage-text">
            <div class="title">${u.title}</div>
            <div class="sub">${u.source} · ${u.sub}</div>
          </div>
        </div>
      `).join('')}</div>` : `<div class="ml-empty-usage">Not connected to any content yet.</div>`}
    </div>
    <div class="ml-drawer-actions">
      <button class="ml-btn" onclick="mlOpenRename(${it.id})">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        Rename
      </button>
      <button class="ml-btn ml-btn-danger" onclick="mlOpenDelete(${it.id})">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
        Delete
      </button>
    </div>
  `;
    document.getElementById('mlScrim').classList.add('open');
    drawer.classList.add('open');
    if (it.type === 'file') {
      mlRenderFilePreview(it);
    }
  }

  function mlCloseDrawer() {
    document.getElementById('mlScrim').classList.remove('open');
    document.getElementById('mlDrawer').classList.remove('open');
  }
  document.getElementById('mlScrim').addEventListener('click', mlCloseDrawer);

  function mlOpenRename(id) {
    const it = mlItems.find(i => i.id === id);
    const ext = (it.name.match(/\.[^.]+$/) || [''])[0];
    document.getElementById('mlRenameModal').innerHTML = `
    <h3>Rename file</h3>
    <p class="desc">Give this file a clear, descriptive name. Search engines read image file names, so a specific name helps it surface in Google Images.</p>
    <label>File name</label>
    <input type="text" id="mlRenameInput" value="${it.name.replace(/\.[^.]+$/,'')}">
    <div class="ml-furl" id="mlRenamePreview" style="margin-bottom:14px;">${it.path}</div>
    <div class="ml-seo-tip"><b>SEO tip —</b> use lowercase words separated by hyphens that describe what's in the file, e.g. <span class="ml-mono">asvab-arithmetic-practice-question.jpg</span> instead of <span class="ml-mono">IMG_2847.jpg</span>.</div>
    ${it.used_in.length ? `
    <div class="ml-impact-box">
      <div class="h">
        This will update ${it.used_in.length} connected record${it.used_in.length > 1 ? 's' : ''}
      </div>

      <div class="ml-impact-list">
        <ul>
          ${it.used_in.map(u => `
            <li>
              <strong>${u.title}</strong><br>
              <span>${u.sub}</span>
            </li>
          `).join('')}
        </ul>
      </div>
    </div>` : ''}
    <div id="mlRenameError" style="display:none;color:#D5432E;font-size:12.5px;margin-bottom:10px;"></div>
    <div class="ml-modal-actions">
      <button class="ml-btn" id="mlRenameCancelBtn">Cancel</button>
      <button class="ml-btn ml-btn-primary" id="mlRenameSaveBtn">Save name</button>
    </div>
  `;
    document.getElementById('mlRenameInput').addEventListener('input', function() {
      document.getElementById('mlRenamePreview').textContent = 'uploads/' + this.value.toLowerCase().replace(/\s+/g, '-') + '${ext}';
    });
    document.getElementById('mlRenameCancelBtn').addEventListener('click', mlCloseRename);
    document.getElementById('mlRenameSaveBtn').addEventListener('click', () => mlSubmitRename(id));
    document.getElementById('mlRenameScrim').classList.add('open');
  }

  function mlCloseRename() {
    document.getElementById('mlRenameScrim').classList.remove('open');
  }

  async function mlSubmitRename(id) {
    const it = mlItems.find(i => i.id === id);
    const newBase = document.getElementById('mlRenameInput').value.trim();
    const saveBtn = document.getElementById('mlRenameSaveBtn');
    const errBox = document.getElementById('mlRenameError');
    errBox.style.display = 'none';

    if (!newBase) {
      errBox.textContent = 'Please enter a file name.';
      errBox.style.display = 'block';
      return;
    }

    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving…';

    try {
      const res = await fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          action: 'rename',
          path: it.path.replace(/^uploads\//, ''),
          new_name: newBase
        })
      });
      const data = await res.json();

      if (!data.success) {
        errBox.textContent = data.message || 'Something went wrong.';
        errBox.style.display = 'block';
        saveBtn.disabled = false;
        saveBtn.textContent = 'Save name';
        return;
      }

      it.name = data.name;
      it.path = data.path;
      it.url = it.url.slice(0, it.url.lastIndexOf('/') + 1) + encodeURIComponent(data.name);
      mlThumbCache.delete(id); // captured frame was keyed to the old file; let it re-capture under the new name
      mlApplyFilters();
      mlCloseRename();
      mlOpenDrawer(id);
    } catch (e) {
      errBox.textContent = 'Network error — please try again.';
      errBox.style.display = 'block';
      saveBtn.disabled = false;
      saveBtn.textContent = 'Save name';
    }
  }

  function mlOpenDelete(id) {
    const it = mlItems.find(i => i.id === id);
    const hasUsage = it.used_in.length > 0;
    document.getElementById('mlDeleteModal').innerHTML = `
    <h3>Delete "${it.name}"?</h3>
    ${hasUsage ? `
    <div class="ml-warn-box">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
      <div>
        <div class="t">This file is used in ${it.used_in.length} place${it.used_in.length>1?'s':''}</div>
        <div class="s">Deleting it will remove the image from: ${it.used_in.map(u=>u.title).join(', ')}.</div>
      </div>
    </div>
    <div class="ml-check-row">
      <input type="checkbox" id="mlConfirmCheck">
      <label for="mlConfirmCheck" style="margin:0;font-weight:400;">I understand this can't be undone</label>
    </div>
    ` : `<p class="desc">This file isn't connected to any content. It can be safely removed.</p>`}
    <div id="mlDeleteError" style="display:none;color:#D5432E;font-size:12.5px;margin-bottom:10px;"></div>
    <div class="ml-modal-actions">
      <button class="ml-btn" id="mlDeleteCancelBtn">Cancel</button>
      <button class="ml-btn ml-btn-danger" id="mlDeleteConfirmBtn">Delete file</button>
    </div>
  `;
    document.getElementById('mlDeleteCancelBtn').addEventListener('click', mlCloseDelete);
    document.getElementById('mlDeleteConfirmBtn').addEventListener('click', () => mlSubmitDelete(id));
    document.getElementById('mlDeleteScrim').classList.add('open');
  }

  function mlCloseDelete() {
    document.getElementById('mlDeleteScrim').classList.remove('open');
  }

  async function mlSubmitDelete(id) {
    const it = mlItems.find(i => i.id === id);
    const confirmCheck = document.getElementById('mlConfirmCheck');
    const errBox = document.getElementById('mlDeleteError');
    const delBtn = document.getElementById('mlDeleteConfirmBtn');
    errBox.style.display = 'none';

    if (confirmCheck && !confirmCheck.checked) {
      errBox.textContent = 'Please confirm you understand this can\'t be undone.';
      errBox.style.display = 'block';
      return;
    }

    delBtn.disabled = true;
    delBtn.textContent = 'Deleting…';

    try {
      const res = await fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          action: 'delete',
          path: it.path.replace(/^uploads\//, '')
        })
      });
      const data = await res.json();

      if (!data.success) {
        errBox.textContent = data.message || 'Something went wrong.';
        errBox.style.display = 'block';
        delBtn.disabled = false;
        delBtn.textContent = 'Delete file';
        return;
      }

      const idx = mlItems.findIndex(i => i.id === id);
      if (idx > -1) mlItems.splice(idx, 1);
      mlSelectedIds.delete(id);
      mlApplyFilters();
      mlCloseDelete();
      mlCloseDrawer();
    } catch (e) {
      errBox.textContent = 'Network error — please try again.';
      errBox.style.display = 'block';
      delBtn.disabled = false;
      delBtn.textContent = 'Delete file';
    }
  }

  /* ---------------------------------------------------------------------
     Bulk delete — reuses the same delete modal/scrim as the single-file
     delete, just built from the current selection instead of one item.
     ------------------------------------------------------------------- */
  function mlOpenBulkDelete() {
    const selectedItems = mlItems.filter(it => mlSelectedIds.has(it.id));
    if (selectedItems.length === 0) return;

    const totalUsage = selectedItems.reduce((sum, it) => sum + it.used_in.length, 0);
    const hasUsage = totalUsage > 0;
    const n = selectedItems.length;

    document.getElementById('mlDeleteModal').innerHTML = `
    <h3>Delete ${n} file${n > 1 ? 's' : ''}?</h3>
    ${hasUsage ? `
    <div class="ml-warn-box">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
      <div>
        <div class="t">${totalUsage} connected record${totalUsage > 1 ? 's' : ''} across these files</div>
        <div class="s">Deleting will remove those records too.</div>
      </div>
    </div>
    <div class="ml-check-row">
      <input type="checkbox" id="mlBulkConfirmCheck">
      <label for="mlBulkConfirmCheck" style="margin:0;font-weight:400;">I understand this can't be undone</label>
    </div>
    ` : `<p class="desc">None of the selected files are connected to any content. They can be safely removed.</p>`}
    <div class="ml-impact-list">
      <ul>
        ${selectedItems.map(it => `
          <li>
            <strong>${it.name}</strong><br>
            <span>${it.used_in.length ? it.used_in.length + ' connected record' + (it.used_in.length > 1 ? 's' : '') : 'Unused'}</span>
          </li>
        `).join('')}
      </ul>
    </div>
    <div id="mlBulkDeleteError" style="display:none;color:#D5432E;font-size:12.5px;margin-top:10px;"></div>
    <div class="ml-modal-actions">
      <button class="ml-btn" id="mlBulkDeleteCancelBtn">Cancel</button>
      <button class="ml-btn ml-btn-danger" id="mlBulkDeleteConfirmBtn">Delete ${n} file${n > 1 ? 's' : ''}</button>
    </div>
  `;
    document.getElementById('mlBulkDeleteCancelBtn').addEventListener('click', mlCloseDelete);
    document.getElementById('mlBulkDeleteConfirmBtn').addEventListener('click', mlSubmitBulkDelete);
    document.getElementById('mlDeleteScrim').classList.add('open');
  }

  async function mlSubmitBulkDelete() {
    const selectedItems = mlItems.filter(it => mlSelectedIds.has(it.id));
    const confirmCheck = document.getElementById('mlBulkConfirmCheck');
    const errBox = document.getElementById('mlBulkDeleteError');
    const delBtn = document.getElementById('mlBulkDeleteConfirmBtn');
    errBox.style.display = 'none';

    if (confirmCheck && !confirmCheck.checked) {
      errBox.textContent = 'Please confirm you understand this can\'t be undone.';
      errBox.style.display = 'block';
      return;
    }

    const n = selectedItems.length;
    delBtn.disabled = true;
    delBtn.textContent = 'Deleting…';

    try {
      const body = new URLSearchParams();
      body.append('action', 'bulk_delete');
      selectedItems.forEach(it => body.append('paths[]', it.path.replace(/^uploads\//, '')));

      const res = await fetch(window.location.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body
      });
      const data = await res.json();
      const results = data.results || [];
      const failures = [];

      // results[i] lines up with the paths sent, in the same order.
      results.forEach((r, i) => {
        const it = selectedItems[i];
        if (!it) return;

        if (r.success) {
          const idx = mlItems.findIndex(x => x.id === it.id);
          if (idx > -1) mlItems.splice(idx, 1);
          mlSelectedIds.delete(it.id);
        } else {
          failures.push(`${it.name}: ${r.message || 'Failed'}`);
        }
      });

      mlApplyFilters(); // re-render with whatever succeeded removed

      if (failures.length) {
        errBox.textContent = failures.join(' | ');
        errBox.style.display = 'block';
        delBtn.disabled = false;
        delBtn.textContent = `Delete ${n} files`;
        return;
      }

      mlCloseDelete();
    } catch (e) {
      errBox.textContent = 'Network error — please try again.';
      errBox.style.display = 'block';
      delBtn.disabled = false;
      delBtn.textContent = `Delete ${n} files`;
    }
  }

  mlRenderPage();
  mlUpdateUsageFilterUI();
</script>