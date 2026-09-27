<?php

/**
 * Intro videos keyed by course_id. Both URLs stay empty until a real file
 * or YouTube link is added for that course.
 *
 * Files live in uploads/course-intro-videos/<course>/intro.mp4:
 *   30 SHSAT       uploads/course-intro-videos/shsat-prep/intro.mp4
 *   24 AFOQT       uploads/course-intro-videos/afoqt/intro.mp4
 *   26 ASTB        uploads/course-intro-videos/astb/intro.mp4
 *   21 OAR         uploads/course-intro-videos/oar/intro.mp4
 *   25 SIFT        uploads/course-intro-videos/sift/intro.mp4
 *   38 ASVAB Prep  uploads/course-intro-videos/asvab-prep/intro.mp4
 *   51 State Math  uploads/course-intro-videos/state-math-test-prep/intro.mp4
 */
function getIntroVideoForCourse($course_id, $vpath)
{
    $map = array(
        30 => array("server_url" => "uploads/course-intro-videos/shsat-prep/intro.mp4", "youtube_url" => ""),
        24 => array("server_url" => "", "youtube_url" => ""),
        26 => array("server_url" => "", "youtube_url" => ""),
        21 => array("server_url" => "", "youtube_url" => ""),
        25 => array("server_url" => "", "youtube_url" => ""),
        38 => array("server_url" => "", "youtube_url" => ""),
        51 => array("server_url" => "", "youtube_url" => ""),
    );

    // $map = array(
    //     30 => array("server_url" => "uploads/course-intro-videos/shsat-prep/intro.mp4", "youtube_url" => ""),
    //     24 => array("server_url" => "uploads/course-intro-videos/afoqt/intro.mp4", "youtube_url" => ""),
    //     26 => array("server_url" => "uploads/course-intro-videos/astb/intro.mp4", "youtube_url" => ""),
    //     21 => array("server_url" => "uploads/course-intro-videos/oar/intro.mp4", "youtube_url" => ""),
    //     25 => array("server_url" => "uploads/course-intro-videos/sift/intro.mp4", "youtube_url" => ""),
    //     38 => array("server_url" => "uploads/course-intro-videos/asvab-prep/intro.mp4", "youtube_url" => ""),
    //     51 => array("server_url" => "uploads/course-intro-videos/state-math-test-prep/intro.mp4", "youtube_url" => ""),
    // );

    $course_id = (int)$course_id;
    $entry = isset($map[$course_id]) ? $map[$course_id] : array("server_url" => "", "youtube_url" => "");

    $server_url = isset($entry["server_url"]) ? trim((string)$entry["server_url"]) : "";
    $youtube_url = isset($entry["youtube_url"]) ? trim((string)$entry["youtube_url"]) : "";

    if ($server_url !== "" && stripos($server_url, "http://") !== 0 && stripos($server_url, "https://") !== 0) {
        $server_url = rtrim((string)$vpath, "/") . "/" . ltrim($server_url, "/");
    }

    return array(
        "server_url" => $server_url,
        "youtube_url" => $youtube_url,
    );
}
