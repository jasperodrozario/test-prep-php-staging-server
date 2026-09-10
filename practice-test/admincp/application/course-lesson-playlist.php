<?php

include("../common/access.php");
include("../common/autoload.php");
include("../../configs/lesson_playlist.php");

if (empty($_GET['action'])) {
    $_GET['action'] = "list";
}

$mid = isset($_GET['mid']) ? ("&mid=" . urlencode($_GET['mid'])) : "";

switch ($_GET['action']) {

    case "list":
        $data['page_title'] = "Course Lesson Playlist Videos";
        $data['breadcrumb'] = array("Course Lesson Playlist Videos" => "course-lesson-playlist?action=list");
        $data['course_rows'] = getLessonPlaylistCourseList();
        $data['total'] = count($data['course_rows']);
        $data['page_subtitle'] = "Total Courses [" . $data['total'] . "]";
        break;

    case "edit":
        $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : (isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0);
        $course = getDataByWhere("course", "*", array("course_id" => $course_id));

        if (!$course || empty($course['course_id'])) {
            header("Location: course-lesson-playlist?action=list" . $mid);
            exit();
        }

        $data['page_title'] = "Course Lesson Playlist Videos";
        $data['breadcrumb'] = array(
            "Course Lesson Playlist Videos" => "course-lesson-playlist?action=list",
            $course['course_name'] => ""
        );
        $data['btn'] = "<a href='course-lesson-playlist?action=list" . $mid . "' class='btn btn-success'>Back To Courses</a>";
        $data['course'] = $course;
        $data['sync_error'] = "";

        if (isset($_POST['sync_now'])) {
            $playlist = getLessonPlaylistByCourseId($course_id);
            if ($playlist) {
                $synced = syncLessonPlaylist((int)$playlist['id'], true);
                if ($synced) {
                    $data['msg'] = str_replace("{table}", "Playlist ", $configs['success_msg']);
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['sync_error'] = "YouTube sync did not complete. The last stored videos were left unchanged.";
                }
            } else {
                $data['msg'] = $configs['error_msg'];
                $data['sync_error'] = "No playlist is linked to this course yet.";
            }
        } elseif (isset($_POST['submit'])) {
            $playlist_url = isset($_POST['playlist_url']) ? trim($_POST['playlist_url']) : "";
            $auto_update = isset($_POST['auto_update']) ? 'Y' : 'N';
            $sync_interval_days = isset($_POST['sync_interval_days']) ? (int)$_POST['sync_interval_days'] : 30;

            if ($playlist_url === "") {
                detachLessonPlaylist($course_id);
                $data['msg'] = str_replace("{table}", "Playlist ", $configs['success_msg']);
            } else {
                $result = upsertLessonPlaylist($course_id, $playlist_url, $auto_update, $sync_interval_days);
                if (!empty($result['ok'])) {
                    $data['msg'] = str_replace("{table}", "Playlist ", $configs['success_msg']);
                    if (empty($result['synced']) && !empty($result['error'])) {
                        $data['sync_error'] = $result['error'];
                    }
                } else {
                    $data['msg'] = $configs['error_msg'];
                    $data['sync_error'] = !empty($result['error']) ? $result['error'] : "Could not save playlist.";
                    $data['posted_url'] = $playlist_url;
                    $data['posted_auto_update'] = $auto_update;
                    $data['posted_interval'] = $sync_interval_days;
                }
            }
        }

        $playlist = getLessonPlaylistByCourseId($course_id);
        $data['playlist'] = $playlist;
        $data['videos'] = $playlist ? fetchLessonPlaylistVideoRows($playlist['id']) : array();
        break;

    default:
        header("Location: course-lesson-playlist?action=list" . $mid);
        exit();
}

$data['action'] = $_GET['action'];

echo template($data);
