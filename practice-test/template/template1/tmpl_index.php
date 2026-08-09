<div class="question_wrap">

    <?php if (!empty($_SESSION['has_multi_courses'])) { ?>

        <div id="multiCourseAlert" class="multi-course-alert">
            <div class="multi-course-alert-content">
                <strong>Multiple Courses</strong>
                <p>
                    You’re registered for multiple courses. Use the navbar to switch courses.
                </p>
            </div>

            <button
                type="button"
                class="multi-course-alert-close"
                onclick="closeMultiCourseAlert()"
                aria-label="Close">
                &times;
            </button>
        </div>

    <?php } ?>

    <?php if ($total_course_videos) { ?>
        <strong>
            <h2><?php echo $_SESSION['test_name']; ?> Course Overview</h2>
        </strong>

        <?php
        $desktopUrl = $course_study_video['desktop_youtube_link'] ?? '';
        $mobileUrl  = $course_study_video['mobile_youtube_link'] ?? '';

        preg_match('/(?:shorts\/|v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $desktopUrl, $d);
        preg_match('/(?:shorts\/|v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $mobileUrl, $m);

        $desktopId = $d[1] ?? null;
        $mobileId  = $m[1] ?? null;
        ?>

        <div id="videoWrapper" class="youtube-wrapper landscape">
            <iframe id="courseVideo"
                src=""
                allowfullscreen>
            </iframe>
        </div>

    <?php } ?>


    <div class="container">
        <strong>
            <h2><?php echo $_SESSION['test_name']; ?> Online Test Prep</h2>
            <p>Enhance your exam preparation with our <?php echo $_SESSION['test_name']; ?> Online Test Prep.</p>
        </strong>
        <div class="upcoming-test-items">
            <?
            foreach ($test as $d) {
                if (!$d['icon']) {
                    $d['icon'] = "uploads/test.png";
                }
            ?>
                <div class="col-md-3">
                    <?
                    if ($d['name'] == "Flashcards") {
                    ?>
                        <a class="item boxes_free_mock" href="<?= $configs['vpath'] ?>exam/<?= seo_url($d['name']); ?>-<?= $d['id']; ?>" title="<?= $d['name']; ?>">
                        <?

                    } else if ($d['pred_test'] == 1) {
                        ?>
                            <a class="item boxes_free_mock" href="<?= $configs['vpath'] ?>exam/prediction-test/<?= $d['id']; ?>-<?= seo_url($d['name']); ?>" title="<?= $d['name']; ?>">
                            <?

                        } else if ($d['course'] == 1) {
                            ?>
                                <a class="item boxes_free_mock" href="<?= str_replace("/practice-test", "", $configs['vpath']) ?>login" title="<?= $d['name']; ?>">
                                <?
                            } else {
                                ?>
                                    <a class="item boxes_free_mock" href="<?= $configs['vpath'] ?>exam/<?= $_SESSION['test_id']; ?>-<?= $d['id']; ?>-<?= seo_url($d['name']); ?>" title="<?= $d['name']; ?>">
                                    <?

                                }
                                    ?>
                                    <figure style="width: 100%; height: 150px;">
                                        <div class="hthamb-img">
                                            <img src="<?= $configs['vpath'] . "image.php?src=" . $configs['vpath'] . $d['icon']; ?>&w=80&h=80" alt="<?= $d['name']; ?>">
                                        </div>
                                        <div class="exam_content">
                                            <p class="name">
                                                <?= $d['name']; ?>
                                            </p>
                                        </div>
                                    </figure>
                                    </a>
                </div>
            <?
            }
            if ($_SESSION['test_id']) {
            ?>
                <div class="col-md-3">
                    <a class="item boxes_free_mock" href="<?= $configs['vpath'] ?>exam/<?= $_SESSION['test_id']; ?>-<?= 4444; ?>-<?= seo_url($_SESSION['test_name'] . " prediction test"); ?>" title="<?= $_SESSION['test_name']; ?>">

                        <figure style="width: 100%; height: 150px;">
                            <div class="hthamb-img">
                                <img src="<?= $configs['vpath'] . "image.php?src=" . $configs['vpath'] . "uploads/test.png"; ?>&w=80&h=80" alt="<?= $_SESSION['test_name']; ?> Prediction Test">
                            </div>
                            <div class="exam_content">
                                <p class="name">
                                    <?= $_SESSION['test_name']; ?> Prediction Test
                                </p>
                            </div>
                        </figure>
                    </a>
                </div>
            <?
            }
            ?>
        </div>
    </div>
</div>
<div class='clearfix'></div>

<script>
    function closeMultiCourseAlert() {
        const alert = document.getElementById('multiCourseAlert');

        if (alert) {
            alert.remove();
            localStorage.setItem('multiCourseAlertClosed', '1');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('multiCourseAlert');

        if (
            alert &&
            localStorage.getItem('multiCourseAlertClosed') === '1'
        ) {
            alert.remove();
        }
    });
</script>

<script>
    const desktopVideo = "<?= $desktopId ? 'https://www.youtube.com/embed/' . $desktopId : '' ?>";
    const mobileVideo = "<?= $mobileId ? 'https://www.youtube.com/embed/' . $mobileId : '' ?>";

    const iframe = document.getElementById("courseVideo");
    const wrapper = document.getElementById("videoWrapper");

    let currentMode = null;

    function setLayoutOnly() {
        const isMobile = window.matchMedia("(max-width: 768px)").matches;
        const mode = isMobile ? "portrait" : "landscape";

        // only update class (NO iframe reload)
        if (mode !== currentMode) {
            wrapper.classList.remove("portrait", "landscape");
            wrapper.classList.add(mode);
            currentMode = mode;
        }
    }

    // set video ONLY ONCE
    function initVideo() {
        const isMobile = window.matchMedia("(max-width: 768px)").matches;

        iframe.src = isMobile ? mobileVideo : desktopVideo;

        setLayoutOnly();
    }

    initVideo();

    // only update layout on resize
    window.addEventListener("resize", setLayoutOnly);
</script>

<style>
    .youtube-wrapper {
        position: relative;
        width: 100%;
        max-width: 720px;
        margin: 20px auto;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    /* Desktop (landscape) */
    .youtube-wrapper.landscape {
        aspect-ratio: 16 / 9;
    }

    /* Mobile (portrait) */
    .youtube-wrapper.portrait {
        aspect-ratio: 9 / 16;
        max-width: 420px;
        /* optional better portrait fit */
    }

    .youtube-wrapper iframe {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    .multi-course-alert {
        position: fixed;
        top: 110px;
        right: 25px;
        z-index: 9999;

        width: 360px;
        max-width: calc(100vw - 30px);

        background: #fff;
        border: 1px solid #ddd;
        border-left: 4px solid #007bff;
        border-radius: 6px;

        padding: 15px 40px 15px 18px;

        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);

        display: flex;
        align-items: flex-start;
    }

    .multi-course-alert-content strong {
        display: block;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .multi-course-alert-content p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
        color: #555;
    }

    .multi-course-alert-close {
        position: absolute;
        top: 8px;
        right: 10px;

        border: 0;
        background: transparent;

        font-size: 24px;
        line-height: 1;

        color: #777;
        cursor: pointer;
    }

    .multi-course-alert-close:hover {
        color: #000;
    }

    @media (max-width: 767px) {
        .multi-course-alert {
            top: 70px;
            right: 15px;
            left: 15px;
            width: auto;
        }
    }
</style>