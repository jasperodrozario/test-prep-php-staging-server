CREATE TABLE IF NOT EXISTS edu_lesson_playlists (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id INT NOT NULL,
  course_code VARCHAR(64) NULL,
  playlist_id VARCHAR(64) NOT NULL,
  playlist_url VARCHAR(255) NOT NULL,
  auto_update CHAR(1) NOT NULL DEFAULT 'Y',
  sync_interval_days INT UNSIGNED NOT NULL DEFAULT 30,
  last_synced_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_lesson_playlists_course_id (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS edu_lesson_playlist_videos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  playlist_row_id INT UNSIGNED NOT NULL,
  youtube_video_id VARCHAR(32) NOT NULL,
  title VARCHAR(255) NOT NULL,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_playlist_video (playlist_row_id, youtube_video_id),
  CONSTRAINT fk_lesson_playlist_videos
    FOREIGN KEY (playlist_row_id) REFERENCES edu_lesson_playlists(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO edu_lesson_playlists (course_id, course_code, playlist_id, playlist_url, auto_update, sync_interval_days)
SELECT 24, 'AFOQT', 'PL7Z1WD6vWCfiyGWgDnizbzyhCbMbXEVr9', 'https://www.youtube.com/playlist?list=PL7Z1WD6vWCfiyGWgDnizbzyhCbMbXEVr9', 'Y', 30
FROM DUAL
WHERE EXISTS (SELECT 1 FROM edu_course WHERE course_id = 24)
  AND NOT EXISTS (SELECT 1 FROM edu_lesson_playlists WHERE course_id = 24);

INSERT INTO edu_lesson_playlists (course_id, course_code, playlist_id, playlist_url, auto_update, sync_interval_days)
SELECT 26, 'ASTB', 'PL7Z1WD6vWCfjW1nm93NMn5flt7itpaDlG', 'https://www.youtube.com/playlist?list=PL7Z1WD6vWCfjW1nm93NMn5flt7itpaDlG', 'Y', 30
FROM DUAL
WHERE EXISTS (SELECT 1 FROM edu_course WHERE course_id = 26)
  AND NOT EXISTS (SELECT 1 FROM edu_lesson_playlists WHERE course_id = 26);

INSERT INTO edu_lesson_playlists (course_id, course_code, playlist_id, playlist_url, auto_update, sync_interval_days)
SELECT 21, 'OAR', 'PL7Z1WD6vWCfh72LJJGmW_ZFzm-p5OLpXQ', 'https://www.youtube.com/playlist?list=PL7Z1WD6vWCfh72LJJGmW_ZFzm-p5OLpXQ', 'Y', 30
FROM DUAL
WHERE EXISTS (SELECT 1 FROM edu_course WHERE course_id = 21)
  AND NOT EXISTS (SELECT 1 FROM edu_lesson_playlists WHERE course_id = 21);

INSERT INTO edu_lesson_playlists (course_id, course_code, playlist_id, playlist_url, auto_update, sync_interval_days)
SELECT 30, 'SHSAT', 'PL7Z1WD6vWCfiDQAYkyf-XUq9lwajGTMKa', 'https://www.youtube.com/playlist?list=PL7Z1WD6vWCfiDQAYkyf-XUq9lwajGTMKa', 'Y', 30
FROM DUAL
WHERE EXISTS (SELECT 1 FROM edu_course WHERE course_id = 30)
  AND NOT EXISTS (SELECT 1 FROM edu_lesson_playlists WHERE course_id = 30);

INSERT INTO edu_adminmenu (name, url, menu_desc, parent_id, status, ord, pic)
SELECT 'Course Lesson Playlist Videos', 'course-lesson-playlist', '', 62, 'Y', 3, ''
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM edu_adminmenu WHERE url = 'course-lesson-playlist');
