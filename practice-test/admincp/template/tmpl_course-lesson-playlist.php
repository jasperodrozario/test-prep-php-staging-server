<?php
echo "<br>";

switch ($action) {

    case "list":
?>
        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>Course ID</b></td>
                    <td><b>Course Name</b></td>
                    <td align="center"><b>Playlist</b></td>
                    <td align="center"><b>Videos</b></td>
                    <td align="center"><b>Last Synced</b></td>
                    <td align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($course_rows)) {
                    echo "<tr><td colspan='6' align='center'>No courses found.</td></tr>";
                } else {
                    foreach ($course_rows as $d) {
                        $linked = !empty($d['playlist_row_id']);
                        $video_count = isset($d['video_count']) ? (int)$d['video_count'] : 0;
                        $last_synced = !empty($d['last_synced_at']) ? $d['last_synced_at'] : "";
                        echo "
                            <tr>
                                <td align='center'>" . (int)$d['course_id'] . "</td>
                                <td><a href='course-lesson-playlist?action=edit&course_id=" . (int)$d['course_id'] . "'><u>" . htmlspecialchars($d['course_name']) . "</u></a></td>
                                <td align='center'><span style='color:" . ($linked ? "green" : "red") . "; font-weight:bold;'>" . ($linked ? "Linked" : "None") . "</span></td>
                                <td align='center'>" . $video_count . "</td>
                                <td align='center'>" . htmlspecialchars($last_synced) . "</td>
                                <td align='center'>
                                    <a href='course-lesson-playlist?action=edit&course_id=" . (int)$d['course_id'] . "' class='btn btn-info btn-sm'>Manage</a>
                                </td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
<?php
        break;

    case "edit":
        $playlist = isset($playlist) && is_array($playlist) ? $playlist : array();
        $videos = isset($videos) && is_array($videos) ? $videos : array();
        $playlist_url = isset($posted_url) ? $posted_url : (isset($playlist['playlist_url']) ? $playlist['playlist_url'] : "");
        $auto_update = isset($posted_auto_update) ? $posted_auto_update : (isset($playlist['auto_update']) ? $playlist['auto_update'] : "Y");
        $interval = isset($posted_interval) ? (int)$posted_interval : (isset($playlist['sync_interval_days']) ? (int)$playlist['sync_interval_days'] : 30);
        $last_synced = isset($playlist['last_synced_at']) ? $playlist['last_synced_at'] : "";
        $has_playlist = !empty($playlist['id']);
?>
        <h5 style="margin-top: 0; color: #333;">
            Course: <strong><?= htmlspecialchars($course['course_name']) ?></strong>
            <span style="color:#888; font-weight:normal;">(ID <?= (int)$course['course_id'] ?>)</span>
        </h5>

        <?php if (!empty($sync_error)) { ?>
            <p style="color:red; font-weight:bold;"><?= htmlspecialchars($sync_error) ?></p>
        <?php } ?>

        <form method="post" action="course-lesson-playlist?action=edit&course_id=<?= (int)$course['course_id'] ?>">
            <input type="hidden" name="course_id" value="<?= (int)$course['course_id'] ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class="table table-bordered">
                <tr>
                    <td align="left" width="240px">Playlist URL</td>
                    <td>
                        <input type="text" name="playlist_url" class="form-control" placeholder="https://www.youtube.com/playlist?list=..." value="<?= htmlspecialchars($playlist_url) ?>">
                        <small>Leave blank and save to detach this playlist from the course.</small>
                    </td>
                </tr>
                <tr>
                    <td align="left">Auto-update</td>
                    <td>
                        <label>
                            <input type="checkbox" name="auto_update" value="Y" <?= ($auto_update === 'Y') ? 'checked' : '' ?>>
                            Refresh from YouTube when the app requests this playlist and the cache is older than the interval
                        </label>
                    </td>
                </tr>
                <tr>
                    <td align="left">Sync every N days</td>
                    <td>
                        <input type="number" name="sync_interval_days" class="form-control" min="1" value="<?= (int)$interval ?>" style="max-width:120px;">
                    </td>
                </tr>
                <tr>
                    <td align="left">Last synced</td>
                    <td><?= $last_synced ? htmlspecialchars($last_synced) : "Never" ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" value="Save" class="btn btn-success">
                        <?php if ($has_playlist) { ?>
                            &nbsp;
                            <input type="submit" name="sync_now" value="Sync now" class="btn btn-info">
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </form>

        <h5 style="margin-top: 20px; color: #333;"><strong>YouTube videos</strong></h5>
        <table width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center" width="80"><b>Order</b></td>
                    <td><b>Title</b></td>
                    <td><b>Watch URL</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($videos)) {
                    echo "<tr><td colspan='3'>No videos yet. Paste a playlist URL and save to pull videos from YouTube.</td></tr>";
                } else {
                    foreach ($videos as $v) {
                        $link = lessonPlaylistWatchUrl($v['youtube_video_id']);
                        echo "<tr>
                            <td align='center'>" . ((int)$v['sort_order'] + 1) . "</td>
                            <td>" . htmlspecialchars($v['title']) . "</td>
                            <td><a href='" . htmlspecialchars($link) . "' target='_blank' rel='noopener noreferrer'>" . htmlspecialchars($link) . "</a></td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
<?php
        break;
}
