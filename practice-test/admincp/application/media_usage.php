<?php
include("../common/access.php");
include("../common/autoload.php");

define('ML_UPLOADS_REL', '/practice-test/uploads/');
define('ML_UPLOADS_ABS', rtrim($_SERVER['DOCUMENT_ROOT'], '/') . ML_UPLOADS_REL);
define('ML_UPLOADS_URL', (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . ML_UPLOADS_REL);

/* =========================================================================
 * AJAX action handling — rename / delete
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

    $action = $_POST['action'] ?? '';
    $oldRel = ml_normalize_rel($_POST['path'] ?? '');

    if ($oldRel === false || $oldRel === '') {
        ml_json(['success' => false, 'message' => 'No file specified. (path was empty or invalid)']);
    }

    $oldAbs = ml_resolve_existing($oldRel);

    if ($oldAbs === false || !is_file($oldAbs)) {
        ml_json([
            'success' => false,
            'message' => 'File not found on the server. Looked for: ' . ML_UPLOADS_ABS . $oldRel,
        ]);
    }

    if ($action === 'rename') {
        $requestedName = basename((string) ($_POST['new_name'] ?? ''));
        if ($requestedName === '') {
            ml_json(['success' => false, 'message' => 'Please enter a file name.']);
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
        }

        $newAbs = ML_UPLOADS_ABS . $newRel;

        if (file_exists($newAbs)) {
            ml_json(['success' => false, 'message' => "A file named \"$newFilename\" already exists in this folder."]);
        }

        if (!rename($oldAbs, $newAbs)) {
            ml_json(['success' => false, 'message' => 'Could not rename the file on the server. Please try again.']);
        }

        // TODO: update DB records that reference 'uploads/' . $oldRel,
        // setting them to 'uploads/' . $newRel.

        ml_json(['success' => true, 'path' => 'uploads/' . $newRel, 'name' => $newFilename]);
    }

    if ($action === 'delete') {
        if (!unlink($oldAbs)) {
            ml_json(['success' => false, 'message' => 'Could not delete the file on the server. Please try again.']);
        }

        // TODO: remove/null out DB records that reference 'uploads/' . $oldRel.

        ml_json(['success' => true]);
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
 * Reads practice-test/uploads/ and returns one entry per file.
 */
function getMediaItems()
{
    $items = [];

    if (!is_dir(ML_UPLOADS_ABS)) {
        return $items;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            ML_UPLOADS_ABS,
            FilesystemIterator::SKIP_DOTS
        )
    );

    $id = 1;

    foreach ($iterator as $file) {

        if (!$file->isFile()) {
            continue;
        }

        $fullPath = $file->getPathname();

        $relativePath = substr($fullPath, strlen(ML_UPLOADS_ABS));
        $relativePath = str_replace('\\', '/', $relativePath);

        $filename = basename($fullPath);

        $ext  = pathinfo($filename, PATHINFO_EXTENSION);
        $type = ml_get_type($ext);

        $dims = '-';

        if ($type === 'image') {
            $info = @getimagesize($fullPath);
            if ($info) {
                $dims = $info[0] . 'x' . $info[1];
            }
        }

        $tmp_file_name = str_replace("'", "", $filename);
        $used_in_list = array();

    
        // $media_connections_question = getQuery( 
        //     "question",
        //     "*",
        //     [
        //         "[or]" => "question LIKE '%" . $tmp_file_name . "%'
        //             OR option_a LIKE '%" . $tmp_file_name . "%'
        //             OR option_b LIKE '%" . $tmp_file_name . "%'
        //             OR option_c LIKE '%" . $tmp_file_name . "%'
        //             OR option_d LIKE '%" . $tmp_file_name . "%'
        //             OR option_e LIKE '%" . $tmp_file_name . "%'"
        //     ]
        // );

        // if (!empty($media_connections_question)) {
        //     addMediaUsage(
        //         $used_in_list,
        //         $media_connections_question,
        //         'question',
        //         'Question',
        //         'Question #',
        //         'qid',
        //         'Used in question/ question options'
        //     );
        // }

        // $media_connections_contents = getQuery(
        //     "contents",
        //     "*",
        //     ["[or]" => "contents LIKE '%" . $tmp_file_name . "%'
        //     OR img LIKE '%" . $tmp_file_name . "%'"
        //     ]
        // );

        // if (!empty($media_connections_contents)) {
        //     addMediaUsage(
        //         $used_in_list,
        //         $media_connections_contents,
        //         'contents',
        //         'Content',
        //         '',
        //         'title',
        //         'Used in page content'
        //     );
        // }

        // $media_connections_story = getQuery(
        //     "story",
        //     "*",
        //     [
        //         "[or]" => "story LIKE '%".$tmp_file_name."%'"
        //     ]
        // );

        // if (!empty($media_connections_story)) {
        //     addMediaUsage(
        //         $used_in_list,
        //         $media_connections_story,
        //         'story',
        //         'Story',
        //         'Story #',
        //         'story_id',
        //         'Used in story'
        //     );
        // }

        // $media_connections_course = getQuery(
        //     "asvab_course",
        //     "*",
        //     [
        //         "[or]" => "details LIKE '%".$tmp_file_name."%'
        //             OR curriculum LIKE '%".$tmp_file_name."%'
        //             OR thumb_pic LIKE '%".$tmp_file_name."%'
        //             OR video LIKE '%".$tmp_file_name."%'"
        //     ]
        // );

        // if (!empty($media_connections_course)) {
        //     addMediaUsage(
        //         $used_in_list,
        //         $media_connections_course,
        //         'asvab_course',
        //         'Course',
        //         '',
        //         'name',
        //         'Used in course'
        //     );
        // }

        // $media_connections_help_video = getQuery(
        //     "course_help_videos",
        //     "*",
        //     [
        //         "[or]" => "thumbnail LIKE '%".$tmp_file_name."%'"
        //     ]
        // );

        // if (!empty($media_connections_help_video)) {
        //     addMediaUsage(
        //         $used_in_list,
        //         $media_connections_help_video,
        //         'course_help_videos',
        //         'Course Help Video',
        //         'Course #',
        //         'course_id',
        //         'Used as video thumbnail'
        //     );
        // }

        // $media_connections_user = getQuery(
        //     "user",
        //     "*",
        //     [
        //         "[or]" => "pic LIKE '%".$tmp_file_name."%'
        //             OR photo LIKE '%".$tmp_file_name."%'
        //             OR attachment LIKE '%".$tmp_file_name."%'"
        //     ]
        // );

        // if (!empty($media_connections_user)) {
        //     addMediaUsage(
        //         $used_in_list,
        //         $media_connections_user,
        //         'user',
        //         'User',
        //         '',
        //         'email',
        //         'Used in user profile'
        //     );
        // }

        // $media_connections_tutor = getQuery(
        //     "tutor",
        //     "*",
        //     [
        //         "[or]" => "photo LIKE '%".$tmp_file_name."%'"
        //     ]
        // );

        // if (!empty($media_connections_tutor)) {
        //     addMediaUsage(
        //         $used_in_list,
        //         $media_connections_tutor,
        //         'tutor',
        //         'Tutor',
        //         '',
        //         'name',
        //         'Used in tutor profile'
        //     );
        // }

        $items[] = [
            'id'       => $id++,
            'type'     => $type,
            'name'     => $filename,
            'path'     => 'uploads/' . $relativePath,
            'url'      => ML_UPLOADS_URL . str_replace('%2F', '/', rawurlencode(ltrim($relativePath, '/'))),
            'dims'     => $dims,
            // 'size'     => ml_format_size(filesize($fullPath)),
            'size'     => 0,
            'date'     => ml_time_ago(filemtime($fullPath)),
            'duration' => null,
            'used_in'  => $used_in_list,
            '_mtime'   => filemtime($fullPath),
        ];
    }

    usort($items, function ($a, $b) {
        return $b['_mtime'] <=> $a['_mtime'];
    });

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
