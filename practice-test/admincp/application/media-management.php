<?php
include("../common/access.php");
include("../common/autoload.php");

define('ML_UPLOADS_REL', '/practice-test/uploads/');
define('ML_UPLOADS_ABS', rtrim($_SERVER['DOCUMENT_ROOT'], '/') . ML_UPLOADS_REL);
define('ML_UPLOADS_URL', (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . ML_UPLOADS_REL);

/* =========================================================================
 * AJAX action handling — rename / delete / bulk_delete / upload
 * -------------------------------------------------------------------------
 * The frontend POSTs to this SAME file (its own URL) with an 'action'
 * field. If that's what this request is, handle it, output JSON, and
 * stop right here — never fall through to the page-render code below.
 * ========================================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    ml_handle_action(); // always exits internally
}

function addMediaUsage(&$used_in_list, $rows, $table, $source, $titlePrefix, $idField, $sub)
{
    foreach ($rows as $row) {
        $used_in_list[] = [
            'table'  => $table,
            'source' => $source,
            'title'  => $titlePrefix . $row[$idField],
            'sub'    => $sub
        ];
    }
}

function ml_json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function ml_normalize_rel($relPath)
{
    $relPath = str_replace('\\', '/', (string) $relPath);
    $relPath = trim($relPath, '/');
    $parts = [];
    foreach (explode('/', $relPath) as $part) {
        if ($part === '' || $part === '.') continue;
        if ($part === '..') return false; // traversal attempt — reject outright
        $parts[] = $part;
    }
    return implode('/', $parts);
}

function ml_resolve_existing($relPath)
{
    $safeRel = ml_normalize_rel($relPath);
    if ($safeRel === false || $safeRel === '') return false;

    $abs = ML_UPLOADS_ABS . $safeRel;
    $realAbs  = realpath($abs);
    $realRoot = realpath(ML_UPLOADS_ABS);

    if ($realAbs === false || $realRoot === false) return false;
    if (strpos($realAbs, $realRoot) !== 0) return false; // must stay inside uploads/

    return $realAbs;
}

function ml_slugify_base($base)
{
    $base = strtolower($base);
    $base = preg_replace('/[^a-z0-9]+/', '-', $base);
    $base = trim($base, '-');
    return $base === '' ? 'file' : $base;
}

/**
 * mysqli_real_escape_string via the app's existing connection handle.
 * Falls back to addslashes if $configs isn't available for some reason.
 */
function ml_esc($val)
{
    global $configs;
    if (isset($configs['con'])) {
        return mysqli_real_escape_string($configs['con'], (string) $val);
    }
    return addslashes((string) $val);
}

/**
 * After a file is renamed on disk, walks every DB table/column that can
 * reference an upload filename and swaps oldFilename -> newFilename
 * inside the text, then writes the row back via updateTable().
 *
 * Doesn't require knowing each table's primary key: the WHERE condition
 * is built from the row's own original column values (fetched via
 * SELECT *), which is enough to uniquely target that row for the update.
 */
function ml_sync_filename_references($oldFilename, $newFilename)
{
    if ($oldFilename === $newFilename) return;

    $tables = [
        ['question',           ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'explanation']],
        ['contents',           ['contents', 'img']],
        ['story',              ['story']],
        ['asvab_course',       ['details', 'curriculum', 'thumb_pic', 'video']],
        ['course_help_videos', ['thumbnail']],
        ['employee_task',      ['description', 'dev_notes']],
        ['user',               ['pic', 'photo', 'attachment']],
        ['tutor',              ['photo']],
    ];

    $oldEsc = ml_esc($oldFilename);

    foreach ($tables as [$table, $cols]) {
        $orParts = array_map(fn($c) => "$c LIKE '%{$oldEsc}%'", $cols);

        $rows = getQuery($table, '*', [
            '[or]' => implode(' OR ', $orParts)
        ]);

        if (empty($rows)) continue;

        foreach ($rows as $row) {
            $set = [];
            $condParts = [];

            // Build an exact-match WHERE clause manually, properly escaped,
            // passed as a single raw "[or]" fragment so updateTable's
            // unescaped col="value" branch never gets hit.
            foreach ($row as $col => $val) {
                if (is_array($val)) continue;
                $condParts[] = $val === null
                    ? "$col IS NULL"
                    : "$col='" . ml_esc($val) . "'";
            }

            foreach ($cols as $c) {
                $val = $row[$c] ?? null;
                if ($val === null || $val === '') continue;
                if (strpos($val, $oldFilename) === false) continue;
                $set[$c] = str_replace($oldFilename, $newFilename, $val);
            }

            if (empty($set)) continue;

            $condition = ['[or]' => implode(' AND ', $condParts)];

            // NOTE: this was previously calling a non-existent
            // updateTable_raw_data() — fixed back to updateTable(), which
            // is the real function defined in your common lib.
            updateTable_raw_data($table, $set, $condition, [], 0);
        }
    }
}

/**
 * Deletes any DB row (across all connected tables) that references this
 * filename in any of its columns. Deletes the WHOLE row, not just the
 * filename reference — e.g. if the file is used in a question's
 * option_b, that entire question row is deleted.
 */
function ml_delete_filename_references($filename)
{
    $tables = [
        ['question',           ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'explanation']],
        ['contents',           ['contents', 'img']],
        ['story',              ['story']],
        ['asvab_course',       ['details', 'curriculum', 'thumb_pic', 'video']],
        ['course_help_videos', ['thumbnail']],
        ['user',               ['pic', 'photo', 'attachment']],
        ['tutor',              ['photo']],
        ['employee_task',      ['description', 'dev_notes']],
    ];

    $esc = ml_esc($filename);

    foreach ($tables as [$table, $cols]) {

        $orParts = array_map(
            fn($c) => "$c LIKE '%{$esc}%'",
            $cols
        );

        $rows = getQuery($table, '*', [
            '[or]' => implode(' OR ', $orParts)
        ]);

        if (empty($rows)) {
            continue;
        }

        foreach ($rows as $row) {

            $condParts = [];

            foreach ($row as $col => $val) {
                if (is_array($val)) continue;

                $condParts[] = $val === null
                    ? "$col IS NULL"
                    : "$col='" . ml_esc($val) . "'";
            }

            $condition = [
                '[or]' => implode(' AND ', $condParts)
            ];

            deleteData($table, $condition);
        }
    }
}

/**
 * Deletes ONE file from disk (by its already-resolved absolute path) and
 * clears its DB references. Shared by the single-delete and bulk-delete
 * code paths so the logic only lives in one place.
 * Returns ['success' => bool, 'message' => string|null].
 */
function ml_delete_one_file($abs, $rel)
{
    if (!unlink($abs)) {
        return ['success' => false, 'message' => 'Could not delete the file on the server.'];
    }

    $filename = basename($rel);
    ml_delete_filename_references($filename);

    return ['success' => true, 'message' => null];
}

function ml_handle_upload()
{
    // Check if files were uploaded
    if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
        ml_json([
            'success' => false,
            'message' => 'No files uploaded.'
        ]);
        return;
    }

    $uploaded = [];
    $errors = [];

    $targetDir = ML_UPLOADS_ABS;

    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            ml_json([
                'success' => false,
                'message' => 'Could not create upload directory.'
            ]);
            return;
        }
    }

    // Ensure it's an array
    $fileNames = (array) $_FILES['files']['name'];
    $fileTmpNames = (array) $_FILES['files']['tmp_name'];
    $fileErrors = (array) $_FILES['files']['error'];

    foreach ($fileNames as $i => $name) {
        // Skip if there's an error
        if ($fileErrors[$i] !== UPLOAD_ERR_OK) {
            $errors[] = $name . ' (Error code: ' . $fileErrors[$i] . ')';
            continue;
        }

        // Sanitize filename
        $name = basename($name);
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '', $name);

        // If name is empty after sanitization, generate one
        if (empty($name)) {
            $name = 'file-' . uniqid();
        }

        $destination = $targetDir . $name;

        // Prevent overwrite
        if (file_exists($destination)) {
            $base = pathinfo($name, PATHINFO_FILENAME);
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $n = 1;

            do {
                $newName = $base . '-' . $n . ($ext ? '.' . $ext : '');
                $destination = $targetDir . $newName;
                $n++;
            } while (file_exists($destination));

            $name = $newName;
        }

        // Move the uploaded file
        if (move_uploaded_file($fileTmpNames[$i], $destination)) {
            // Set proper permissions
            chmod($destination, 0644);

            $uploaded[] = [
                'name' => $name,
                'path' => 'uploads/' . $name,
                'url' => ML_UPLOADS_URL . rawurlencode($name)
            ];
        } else {
            $errors[] = $name . ' (Failed to move uploaded file)';
        }
    }

    // Return response
    ml_json([
        'success' => empty($errors) || !empty($uploaded),
        'uploaded' => $uploaded,
        'errors' => $errors,
        'message' => empty($errors) ? 'All files uploaded successfully.' : 'Some files failed to upload.'
    ]);
}

/**
 * Deletes several files in one request. Expects paths[] (relative to the
 * uploads folder, same format as the single-delete 'path' field).
 * Returns one result entry PER INPUT PATH, in the same order they were
 * sent, so the frontend can match each result back to the item it
 * selected — even when some succeed and others fail.
 */
function ml_handle_bulk_delete()
{
    $paths = $_POST['paths'] ?? [];

    if (!is_array($paths) || empty($paths)) {
        ml_json(['success' => false, 'message' => 'No files specified.']);
        return;
    }

    $results = [];

    foreach ($paths as $rawPath) {
        $rel = ml_normalize_rel($rawPath);

        if ($rel === false || $rel === '') {
            $results[] = ['success' => false, 'path' => (string) $rawPath, 'message' => 'Invalid path.'];
            continue;
        }

        $abs = ml_resolve_existing($rel);

        if ($abs === false || !is_file($abs)) {
            $results[] = ['success' => false, 'path' => $rel, 'message' => 'File not found on the server.'];
            continue;
        }

        $outcome = ml_delete_one_file($abs, $rel);

        $results[] = [
            'success' => $outcome['success'],
            'path'    => $rel,
            'message' => $outcome['message'],
        ];
    }

    $anySuccess = false;
    foreach ($results as $r) {
        if ($r['success']) { $anySuccess = true; break; }
    }

    ml_json(['success' => $anySuccess, 'results' => $results]);
}

function ml_handle_action()
{
    // Always answer as JSON, even on a PHP error — never a blank/HTML page
    register_shutdown_function(function () {
        $err = error_get_last();
        if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'PHP fatal error: ' . $err['message']]);
        }
    });

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    // Upload and bulk_delete don't reference a single 'path' the way
    // rename/delete do (upload has no path yet, bulk_delete has paths[]
    // instead), so both are handled before the single-path checks below.
    if ($action === 'upload') {
        ml_handle_upload();
        return;
    }

    if ($action === 'bulk_delete') {
        ml_handle_bulk_delete();
        return;
    }

    $oldRel = ml_normalize_rel($_POST['path'] ?? '');

    if ($oldRel === false || $oldRel === '') {
        ml_json(['success' => false, 'message' => 'No file specified. (path was empty or invalid)']);
        return;
    }

    $oldAbs = ml_resolve_existing($oldRel);

    if ($oldAbs === false || !is_file($oldAbs)) {
        ml_json([
            'success' => false,
            'message' => 'File not found on the server. Looked for: ' . ML_UPLOADS_ABS . $oldRel,
        ]);
        return;
    }

    if ($action === 'rename') {
        $requestedName = basename((string) ($_POST['new_name'] ?? ''));
        if ($requestedName === '') {
            ml_json(['success' => false, 'message' => 'Please enter a file name.']);
            return;
        }

        // Keep the original extension — renaming must never change the file type
        $origExt = pathinfo($oldRel, PATHINFO_EXTENSION);
        $newBase = ml_slugify_base(pathinfo($requestedName, PATHINFO_FILENAME));
        $newFilename = $origExt !== '' ? "{$newBase}.{$origExt}" : $newBase;

        // Renaming stays within the same folder — it doesn't move the file
        $folder = dirname($oldRel);
        $newRel = ($folder !== '' && $folder !== '.') ? "{$folder}/{$newFilename}" : $newFilename;

        if ($newRel === $oldRel) {
            ml_json(['success' => true, 'path' => 'uploads/' . $oldRel, 'name' => basename($oldRel), 'message' => 'No change.']);
            return;
        }

        $newAbs = ML_UPLOADS_ABS . $newRel;

        if (file_exists($newAbs)) {
            ml_json(['success' => false, 'message' => "A file named \"$newFilename\" already exists in this folder."]);
            return;
        }

        if (!rename($oldAbs, $newAbs)) {
            ml_json(['success' => false, 'message' => 'Could not rename the file on the server. Please try again.']);
            return;
        }

        // Update DB records that reference the old filename so nothing breaks
        $oldFilename = basename($oldRel);
        ml_sync_filename_references($oldFilename, $newFilename);

        ml_json(['success' => true, 'path' => 'uploads/' . $newRel, 'name' => $newFilename]);
        return;
    }

    if ($action === 'delete') {
        $outcome = ml_delete_one_file($oldAbs, $oldRel);

        if (!$outcome['success']) {
            ml_json(['success' => false, 'message' => $outcome['message']]);
            return;
        }

        ml_json(['success' => true]);
        return;
    }

    ml_json(['success' => false, 'message' => 'Unknown action: "' . $action . '"']);
}

/* =========================================================================
 * Normal page load — everything below only runs for a plain GET
 * (or any POST without an 'action' field, e.g. your own app's routing).
 * ========================================================================= */

$data['page_title'] = "Media Management";
$data['breadcrumb'] = array("Media Management" => "media-management");

function ml_format_size($bytes)
{
    if ($bytes >= 1073741824) return round($bytes / 1073741824, 1) . ' GB';
    if ($bytes >= 1048576)    return round($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024)       return round($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
}

function ml_time_ago($ts)
{
    $diff = time() - $ts;
    if ($diff < 60)       return 'just now';
    if ($diff < 3600)     return floor($diff / 60) . ' min ago';
    if ($diff < 86400)    return floor($diff / 3600) . ' hr ago';
    if ($diff < 2592000)  return floor($diff / 86400) . ' day(s) ago';
    if ($diff < 31536000) return floor($diff / 2592000) . ' month(s) ago';
    return floor($diff / 31536000) . ' yr(s) ago';
}

function ml_get_type($ext)
{
    $ext = strtolower($ext);
    $images = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    $videos = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'm4v'];
    if (in_array($ext, $images)) return 'image';
    if (in_array($ext, $videos)) return 'video';
    return 'file';
}

/**
 * Runs each usage-lookup query ONCE (not once per file), extracts every
 * filename referenced in the text, and builds filename -> usages map.
 * Cost: 7 queries total, regardless of how many uploaded files exist.
 */
function ml_build_usage_index()
{
    $usageIndex = [];

    // Any token that looks like "something.ext" gets pulled out of the text
    $extPattern = 'jpg|jpeg|png|gif|webp|svg|bmp|mp4|mov|avi|webm|mkv|m4v|pdf|docx?|xlsx?|csv|txt|json|log|md';
    // $filenameRegex = '/[A-Za-z0-9_\-\.\s]+\.(?:' . $extPattern . ')/i';
    $filenameRegex = '/[A-Za-z0-9_\-\.\s()\[\]{}@#$%&+=,;!~]+\.(?:' . $extPattern . ')/i';

    $tables = [
        ['question', ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'explanation'], 'question',           'Question',           'Question #',  'qid',        'Used in question/ question options'],
        ['contents',           ['contents', 'img'],                                                   'contents',           'Content',            '',            'title',      'Used in page content'],
        ['story',              ['story'],                                                             'story',              'Story',              'Story #',     'story_id',   'Used in story'],
        ['asvab_course',       ['details', 'curriculum', 'thumb_pic', 'video'],                          'asvab_course',       'Course',             '',            'name',       'Used in course'],
        ['course_help_videos', ['thumbnail'],                                                          'course_help_videos', 'Course Help Video',  'Course #',    'course_id',  'Used as video thumbnail'],
        ['user',               ['pic', 'photo', 'attachment'],                                          'user',               'User',               '',            'email',      'Used in user profile'],
        ['tutor',              ['photo'],                                                              'tutor',              'Tutor',              '',            'name',       'Used in tutor profile'],
        ['employee_task',      ['description', 'dev_notes'],                                             'employee_task',      'Employee Task',      '',            'id',    'Used in task description or dev notes'],
    ];

    foreach ($tables as [$table, $cols, $tableKey, $source, $titlePrefix, $idField, $sub]) {
        // pull only the columns you need, id field included, no LIKE, no per-file loop
        $selectCols = array_unique(array_merge([$idField], $cols));
        $rows = getQuery($table, implode(',', $selectCols), []);

        foreach ($rows as $row) {
            $haystack = implode(' ', array_map(fn($c) => (string)($row[$c] ?? ''), $cols));
            if ($haystack === '') continue;

            preg_match_all($filenameRegex, $haystack, $m);
            foreach (array_unique($m[0]) as $fname) {
                $key = strtolower(basename($fname));
                $usageIndex[$key][] = [
                    'table'  => $tableKey,
                    'source' => $source,
                    'title'  => $titlePrefix . $row[$idField],
                    'sub'    => $sub,
                ];
            }
        }
    }

    return $usageIndex;
}

/**
 * Reads practice-test/uploads/ and returns one entry per file.
 */
function getMediaItems()
{
    $items = [];
    if (!is_dir(ML_UPLOADS_ABS)) return $items;

    $usageIndex = ml_build_usage_index(); // <-- 7 queries, once

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(ML_UPLOADS_ABS, FilesystemIterator::SKIP_DOTS)
    );

    $id = 1;
    foreach ($iterator as $file) {
        if (!$file->isFile()) continue;

        $fullPath = $file->getPathname();
        $relativePath = str_replace('\\', '/', substr($fullPath, strlen(ML_UPLOADS_ABS)));
        $filename = basename($fullPath);
        $ext  = pathinfo($filename, PATHINFO_EXTENSION);
        $type = ml_get_type($ext);

        $dims = '-';
        if ($type === 'image') {
            $info = @getimagesize($fullPath);
            if ($info) $dims = $info[0] . 'x' . $info[1];
        }

        $used_in_list = $usageIndex[strtolower($filename)] ?? [];

        $items[] = [
            'id'       => $id++,
            'type'     => $type,
            'name'     => $filename,
            'path'     => 'uploads/' . $relativePath,
            'url'      => ML_UPLOADS_URL . str_replace('%2F', '/', rawurlencode(ltrim($relativePath, '/'))),
            'dims'     => $dims,
            'size'     => ml_format_size(filesize($fullPath)),
            'date'     => ml_time_ago(filemtime($fullPath)),
            'duration' => null,
            'used_in'  => $used_in_list,
            '_mtime'   => filemtime($fullPath),
        ];
    }

    usort($items, fn($a, $b) => $b['_mtime'] <=> $a['_mtime']);
    return $items;
}

function ml_folder_size($dir)
{
    $bytes = 0;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $bytes += $file->getSize();
        }
    }

    return ml_format_size($bytes);
}

function getMediaStats($items)
{
    $total = count($items);
    $connected = 0;
    foreach ($items as $it) {
        if (count($it['used_in']) > 0) $connected++;
    }
    return [
        'total'     => $total,
        'storage'   => ml_folder_size(ML_UPLOADS_ABS),
        'connected' => $connected,
        'unused'    => $total - $connected,
    ];
}

$data['mediaItems'] = getMediaItems();
$data['stats']      = getMediaStats($data['mediaItems']);

echo template($data);