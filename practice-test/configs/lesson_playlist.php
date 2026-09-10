<?php

function lessonPlaylistDb()
{
    global $configs;
    if (!empty($configs['con'])) {
        @mysqli_set_charset($configs['con'], 'utf8mb4');
    }
    return $configs['con'];
}

function lessonPlaylistTable($name)
{
    global $configs;
    return $configs['prev'] . $name;
}

function lessonPlaylistEscape($value)
{
    return mysqli_real_escape_string(lessonPlaylistDb(), (string)$value);
}

function parseYouTubePlaylistId($playlist_url)
{
    $playlist_url = trim((string)$playlist_url);
    if ($playlist_url === '') {
        return '';
    }

    if (preg_match('/^PL[a-zA-Z0-9_-]+$/', $playlist_url)) {
        return $playlist_url;
    }

    $parts = parse_url($playlist_url);
    if (!empty($parts['query'])) {
        $query = array();
        parse_str($parts['query'], $query);
        if (!empty($query['list'])) {
            return trim($query['list']);
        }
    }

    if (!empty($parts['fragment'])) {
        $query = array();
        parse_str($parts['fragment'], $query);
        if (!empty($query['list'])) {
            return trim($query['list']);
        }
    }

    return '';
}

function lessonPlaylistWatchUrl($youtube_video_id)
{
    return 'https://www.youtube.com/watch?v=' . $youtube_video_id;
}

function lessonPlaylistIsStale($row)
{
    if (empty($row) || !is_array($row)) {
        return true;
    }

    return empty($row['last_synced_at'])
        || (strtotime($row['last_synced_at']) < strtotime('-' . (int)$row['sync_interval_days'] . ' days'));
}

function getLessonPlaylistByCourseId($course_id)
{
    $course_id = (int)$course_id;
    if ($course_id <= 0) {
        return null;
    }

    $sql = "SELECT * FROM " . lessonPlaylistTable('lesson_playlists') . " WHERE course_id = " . $course_id . " LIMIT 1";
    $result = mysqli_query(lessonPlaylistDb(), $sql);
    if (!$result) {
        return null;
    }
    $row = mysqli_fetch_assoc($result);
    return $row ? $row : null;
}

function getLessonPlaylistById($playlist_row_id)
{
    $playlist_row_id = (int)$playlist_row_id;
    if ($playlist_row_id <= 0) {
        return null;
    }

    $sql = "SELECT * FROM " . lessonPlaylistTable('lesson_playlists') . " WHERE id = " . $playlist_row_id . " LIMIT 1";
    $result = mysqli_query(lessonPlaylistDb(), $sql);
    if (!$result) {
        return null;
    }
    $row = mysqli_fetch_assoc($result);
    return $row ? $row : null;
}

function fetchLessonPlaylistVideoRows($playlist_row_id)
{
    $playlist_row_id = (int)$playlist_row_id;
    $out = array();
    if ($playlist_row_id <= 0) {
        return $out;
    }

    $sql = "SELECT youtube_video_id, title, sort_order FROM " . lessonPlaylistTable('lesson_playlist_videos')
        . " WHERE playlist_row_id = " . $playlist_row_id . " ORDER BY sort_order ASC, id ASC";
    $result = mysqli_query(lessonPlaylistDb(), $sql);
    if (!$result) {
        return $out;
    }
    while ($row = mysqli_fetch_assoc($result)) {
        $out[] = $row;
    }
    return $out;
}

function formatLessonPlaylistVideos($rows)
{
    $videos = array();
    foreach ($rows as $row) {
        $videos[] = array(
            'name' => $row['title'],
            'link' => lessonPlaylistWatchUrl($row['youtube_video_id'])
        );
    }
    return $videos;
}

function fetchYouTubePlaylistItems($playlist_id)
{
    global $configs;

    $playlist_id = trim((string)$playlist_id);
    $api_key = isset($configs['youtube_api_key']) ? trim($configs['youtube_api_key']) : '';
    if ($playlist_id === '' || $api_key === '') {
        return array('ok' => false, 'items' => array(), 'error' => 'Missing YouTube playlist id or API key');
    }

    $items = array();
    $page_token = '';
    $guard = 0;

    do {
        $guard++;
        if ($guard > 40) {
            return array('ok' => false, 'items' => array(), 'error' => 'YouTube pagination limit reached');
        }

        $params = array(
            'part' => 'snippet,status',
            'maxResults' => 50,
            'playlistId' => $playlist_id,
            'key' => $api_key
        );
        if ($page_token !== '') {
            $params['pageToken'] = $page_token;
        }

        $url = 'https://www.googleapis.com/youtube/v3/playlistItems?' . http_build_query($params);
        $raw = lessonPlaylistHttpGet($url);
        if ($raw === false) {
            return array('ok' => false, 'items' => array(), 'error' => 'YouTube request failed');
        }

        $json = json_decode($raw, true);
        if (!is_array($json) || isset($json['error']) || !isset($json['items']) || !is_array($json['items'])) {
            $message = 'YouTube API error';
            if (isset($json['error']['message'])) {
                $message = $json['error']['message'];
            }
            return array('ok' => false, 'items' => array(), 'error' => $message);
        }

        foreach ($json['items'] as $item) {
            $video_id = isset($item['snippet']['resourceId']['videoId']) ? trim($item['snippet']['resourceId']['videoId']) : '';
            $title = isset($item['snippet']['title']) ? trim($item['snippet']['title']) : '';
            $privacy = isset($item['status']['privacyStatus']) ? strtolower($item['status']['privacyStatus']) : '';
            $title_lc = strtolower($title);

            if ($video_id === '') {
                continue;
            }
            if ($privacy === 'private') {
                continue;
            }
            if ($title_lc === 'private video' || $title_lc === 'deleted video') {
                continue;
            }

            if (function_exists('mb_substr')) {
                $title = mb_substr($title, 0, 255, 'UTF-8');
            } else {
                $title = substr($title, 0, 255);
            }
            if ($title === '') {
                $title = $video_id;
            }

            $items[] = array(
                'youtube_video_id' => $video_id,
                'title' => $title
            );
        }

        $page_token = isset($json['nextPageToken']) ? $json['nextPageToken'] : '';
    } while ($page_token !== '');

    $unique = array();
    $deduped = array();
    foreach ($items as $item) {
        $id = $item['youtube_video_id'];
        if (isset($unique[$id])) {
            continue;
        }
        $unique[$id] = true;
        $deduped[] = $item;
    }

    return array('ok' => true, 'items' => $deduped, 'error' => '');
}

function lessonPlaylistHttpGet($url)
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $body = curl_exec($ch);
        $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false || $http >= 500) {
            return false;
        }
        return $body;
    }

    $context = stream_context_create(array(
        'http' => array(
            'timeout' => 45,
            'ignore_errors' => true
        )
    ));
    $body = @file_get_contents($url, false, $context);
    return $body === false ? false : $body;
}

function syncLessonPlaylist($playlist_row_id, $force = false)
{
    $playlist_row_id = (int)$playlist_row_id;
    $row = getLessonPlaylistById($playlist_row_id);
    if (!$row) {
        return false;
    }

    $con = lessonPlaylistDb();
    $lock_name = 'lesson_playlist_sync_' . $playlist_row_id;
    $lock_res = mysqli_query($con, "SELECT GET_LOCK('" . lessonPlaylistEscape($lock_name) . "', 0) AS got_lock");
    $lock_row = $lock_res ? mysqli_fetch_assoc($lock_res) : null;
    if (!$lock_row || (int)$lock_row['got_lock'] !== 1) {
        return false;
    }

    $ok = false;
    try {
        $playlist_id = $row['playlist_id'];
        if ($playlist_id === '' || $playlist_id === null) {
            $playlist_id = parseYouTubePlaylistId($row['playlist_url']);
            if ($playlist_id !== '') {
                mysqli_query($con, "UPDATE " . lessonPlaylistTable('lesson_playlists')
                    . " SET playlist_id = '" . lessonPlaylistEscape($playlist_id) . "'"
                    . " WHERE id = " . $playlist_row_id);
            }
        }

        $fetched = fetchYouTubePlaylistItems($playlist_id);
        if (!empty($fetched['ok'])) {
            mysqli_begin_transaction($con);

            $deleted = mysqli_query($con, "DELETE FROM " . lessonPlaylistTable('lesson_playlist_videos')
                . " WHERE playlist_row_id = " . $playlist_row_id);

            $inserted = $deleted ? true : false;
            $sort = 0;
            if ($inserted) {
                foreach ($fetched['items'] as $item) {
                    $sql = "INSERT INTO " . lessonPlaylistTable('lesson_playlist_videos')
                        . " (playlist_row_id, youtube_video_id, title, sort_order) VALUES ("
                        . $playlist_row_id . ", '"
                        . lessonPlaylistEscape($item['youtube_video_id']) . "', '"
                        . lessonPlaylistEscape($item['title']) . "', "
                        . (int)$sort . ")";
                    if (!mysqli_query($con, $sql)) {
                        $inserted = false;
                        break;
                    }
                    $sort++;
                }
            }

            if ($inserted) {
                mysqli_query($con, "UPDATE " . lessonPlaylistTable('lesson_playlists')
                    . " SET last_synced_at = NOW() WHERE id = " . $playlist_row_id);
                mysqli_commit($con);
                $ok = true;
            } else {
                mysqli_rollback($con);
            }
        }
    } catch (Exception $e) {
        if (function_exists('mysqli_rollback')) {
            @mysqli_rollback($con);
        }
        $ok = false;
    }

    mysqli_query($con, "SELECT RELEASE_LOCK('" . lessonPlaylistEscape($lock_name) . "')");
    return $ok;
}

function getLessonPlaylistVideosForCourse($course_id, $allow_auto_sync = true)
{
    $row = getLessonPlaylistByCourseId($course_id);
    if (!$row) {
        return array();
    }

    if ($allow_auto_sync && isset($row['auto_update']) && $row['auto_update'] === 'Y' && lessonPlaylistIsStale($row)) {
        syncLessonPlaylist((int)$row['id'], false);
        $row = getLessonPlaylistByCourseId($course_id);
    }

    return formatLessonPlaylistVideos(fetchLessonPlaylistVideoRows($row['id']));
}

function upsertLessonPlaylist($course_id, $playlist_url, $auto_update = 'Y', $sync_interval_days = 30, $course_code = null)
{
    $course_id = (int)$course_id;
    $playlist_url = trim((string)$playlist_url);
    $playlist_id = parseYouTubePlaylistId($playlist_url);
    $auto_update = ($auto_update === 'Y' || $auto_update === '1' || $auto_update === 1 || $auto_update === true) ? 'Y' : 'N';
    $sync_interval_days = (int)$sync_interval_days;
    if ($sync_interval_days < 1) {
        $sync_interval_days = 30;
    }

    if ($course_id <= 0 || $playlist_url === '' || $playlist_id === '') {
        return array('ok' => false, 'id' => 0, 'error' => 'A valid YouTube playlist URL is required.');
    }

    $con = lessonPlaylistDb();
    $existing = getLessonPlaylistByCourseId($course_id);
    $code_sql = '';
    if ($course_code !== null && $course_code !== '') {
        $code_sql = ", course_code = '" . lessonPlaylistEscape($course_code) . "'";
    }

    if ($existing) {
        $sql = "UPDATE " . lessonPlaylistTable('lesson_playlists') . " SET "
            . "playlist_id = '" . lessonPlaylistEscape($playlist_id) . "', "
            . "playlist_url = '" . lessonPlaylistEscape($playlist_url) . "', "
            . "auto_update = '" . lessonPlaylistEscape($auto_update) . "', "
            . "sync_interval_days = " . $sync_interval_days
            . $code_sql
            . " WHERE id = " . (int)$existing['id'];
        if (!mysqli_query($con, $sql)) {
            return array('ok' => false, 'id' => 0, 'error' => 'Could not update playlist.');
        }
        $id = (int)$existing['id'];
    } else {
        $sql = "INSERT INTO " . lessonPlaylistTable('lesson_playlists')
            . " (course_id, course_code, playlist_id, playlist_url, auto_update, sync_interval_days) VALUES ("
            . $course_id . ", "
            . ($course_code ? "'" . lessonPlaylistEscape($course_code) . "'" : "NULL") . ", '"
            . lessonPlaylistEscape($playlist_id) . "', '"
            . lessonPlaylistEscape($playlist_url) . "', '"
            . lessonPlaylistEscape($auto_update) . "', "
            . $sync_interval_days . ")";
        if (!mysqli_query($con, $sql)) {
            return array('ok' => false, 'id' => 0, 'error' => 'Could not save playlist.');
        }
        $id = (int)mysqli_insert_id($con);
    }

    $synced = syncLessonPlaylist($id, true);
    return array('ok' => true, 'id' => $id, 'synced' => $synced, 'error' => $synced ? '' : 'Playlist saved, but YouTube sync did not complete. Stored videos were left unchanged.');
}

function detachLessonPlaylist($course_id)
{
    $course_id = (int)$course_id;
    if ($course_id <= 0) {
        return false;
    }
    return (bool)mysqli_query(
        lessonPlaylistDb(),
        "DELETE FROM " . lessonPlaylistTable('lesson_playlists') . " WHERE course_id = " . $course_id
    );
}

function getLessonPlaylistCourseList()
{
    $sql = "SELECT c.course_id, c.course_name, c.is_online, c.ord, c.group_id, "
        . "p.id AS playlist_row_id, p.playlist_url, p.playlist_id, p.course_code, p.auto_update, "
        . "p.sync_interval_days, p.last_synced_at, "
        . "(SELECT COUNT(*) FROM " . lessonPlaylistTable('lesson_playlist_videos') . " v WHERE v.playlist_row_id = p.id) AS video_count "
        . "FROM " . lessonPlaylistTable('course') . " c "
        . "LEFT JOIN " . lessonPlaylistTable('lesson_playlists') . " p ON p.course_id = c.course_id "
        . "ORDER BY c.course_name";

    $out = array();
    $result = mysqli_query(lessonPlaylistDb(), $sql);
    if (!$result) {
        return $out;
    }
    while ($row = mysqli_fetch_assoc($result)) {
        $out[] = $row;
    }
    return $out;
}

function handleGetLessonPlaylistRequest($authorizationToken)
{
    $videos = array();
    $exist = getTotalRow("login", array("auth_token" => $authorizationToken));

    if (!$exist) {
        return array(
            'error' => 'Token does not exist!',
            'videos' => $videos
        );
    }

    $user = getDataByJoin(
        array("user as u", "login as l"),
        "u.*,l.*",
        array("l.auth_token" => $authorizationToken),
        array("u.user_id" => "l.user_id")
    );
    $user_id = isset($user['user_id']) ? $user['user_id'] : 0;
    $course_id = getFldValueById("subscription", "course_id", array("user_id" => $user_id));
    $videos = getLessonPlaylistVideosForCourse($course_id, true);

    return array('videos' => $videos);
}
