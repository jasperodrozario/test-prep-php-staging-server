
<main>

<div class="page-title-area bg-25">
    <div class="container">
        <div class="page-title-content">
            <h2><?= strtoupper($db_value['header']) ?></h2>
            <h4><?= strtoupper($db_value['tagline']) ?>
                <?php
                if ($_GET['location']) {
                    echo " in <b>" . ucwords(str_replace("-", " ", $_GET['location'])) . "</b>";
                }
                ?>
            </h4>
        </div>
    </div>
</div>

<section class="single-course-area ptb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="tab single-course-tab">
                    <ul class="tabs">
                        <!--                        <li><a href="#">Overview of <?= strtoupper($_GET['seo_url']) ?></a></li>-->
                        <li><a href="#">Overview</a></li>
                        <li><a href="#">Curriculum</a></li>
                        <li><a href="#">Course Video</a></li>
                        <li><a href="#">Reviews</a></li>
                        <?php if ($ft_cat_list) { ?>
                            <li><a href="#">Online Free Test</a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab_content">
                        <div class="tabs_item">
                            <div class="single-course-content">
                                <?php if (!empty($db_value['picture'])): ?>
                                    <script src="https://cdn.plyr.io/3.6.12/plyr.js"></script>
                                    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.12/plyr.css" />
                                    <style>
                                        .player-container {
                                            margin: 50px auto;
                                            max-width: 1080px;
                                        }
                                    </style>
                                    <div class="player-container">
                                        <video id="player" playsinline controls data-poster="https://ocs.asvab-tutoring.online/">
                                            <source src="<?= $configs['vpath'] . $db_value['picture'] ?>" type="video/mp4" size="796" />
                                        </video>
                                    </div>
                                    <script>
                                        const player = new Plyr('#player');
                                    </script>
                                    <script src="https://cdn.plyr.io/3.6.12/demo.js" crossorigin="anonymous"></script>
                                <?php endif; ?>

                                <h3><b>Course Description</b></h3>
                                <div style="margin: 10px;">
                                    <?= html_entity_decode($db_value["details"]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tabs_item">
                            <div class="curriculum-content">
                                <h3>Curriculum Summary</h3>
                                <div class="curriculum-list">
                                    <?= html_entity_decode($db_value["curriculum"]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tabs_item">
                            <div class="instructor-content">
                                <div class="row align-items-center">
                                    <?php
                                    if ($db_value['video']) {
                                        $videos = explode(",", $db_value['video']);
                                        for ($i = 0; $i < count($videos); $i++) {
                                    ?>
                                            <div class="col-lg-12"><iframe width="790" height="415" src="<?= $videos[$i] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                                            <div class='clearfix'><br></div>
                                    <?php
                                        }
                                    }else{
                                        echo "<div class='col-12 text-center text-muted'><p>No video available to view at the moment.</p></div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="tabs_item">
                            <div class="review-content">
                                <div class="review-header">
                                    <h3>Customer Reviews</h3>
                                    <div class="review-rating">
                                        <?php
                                        $rating = $avrg_rating;
                                        $fullStars = floor($rating);
                                        $halfStar = ceil($rating - $fullStars);
                                        $emptyStars = 5 - $fullStars - $halfStar;

                                        // Output full stars
                                        for ($i = 0; $i < $fullStars; $i++) {
                                            echo '<i class="bx bxs-star"></i>';
                                        }

                                        // Output half star
                                        if ($halfStar) {
                                            echo '<i class="bx bxs-star-half"></i>';
                                        }

                                        // Output empty stars
                                        for ($i = 0; $i < $emptyStars; $i++) {
                                            echo '<i class="bx bx-star"></i>';
                                        }
                                        ?>
                                        <small>(<?= $total_review ?> Reviews)</small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="review-list">
                                    <?php
                                    foreach ($feedback as $d) {
                                        if (!$d['pic']) {
                                            $d['pic'] = "img/noimage.png";
                                        }
                                    ?>
                                        <div class="review-item">
                                            <div class="reviewer-info">
                                                <img src="<?= $configs['vpath'] ?>image.php?image=<?= $configs['vpath'] . $d['pic'] ?>&w=50&h=50" alt="<?= $d['name'] ?>" class="reviewer-image">
                                                <div class="reviewer-details">
                                                    <h4><?= $d['name'] ?></h4>
                                                    <div class="review-rating">
                                                        <?php
                                                        $rating = $d['rating'];
                                                        $fullStars = floor($rating);
                                                        $halfStar = ceil($rating - $fullStars);
                                                        $emptyStars = 5 - $fullStars - $halfStar;

                                                        // Output full stars
                                                        for ($i = 0; $i < $fullStars; $i++) {
                                                            echo '<i class="bx bxs-star"></i>';
                                                        }

                                                        // Output half star
                                                        if ($halfStar) {
                                                            echo '<i class="bx bxs-star-half"></i>';
                                                        }

                                                        // Output empty stars
                                                        for ($i = 0; $i < $emptyStars; $i++) {
                                                            echo '<i class="bx bx-star"></i>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="review-text"><?= html_entity_decode($d['feedback']); ?></p>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <br><br>

                            <!-- Start Leave a review section -->
                            <div class="review-item mt-150 mb-150">
                                <div class="container">
                                    <div class="section-title text-center">
                                        <h3 style="color: orange; font-weight: bold;">Leave A Feedback</h3>
                                    </div>
                                    <form method="post" id="form" enctype="multipart/form-data">
                                        <input type='hidden' name='action' value='user_feedback'>

                                        <div id="response"></div>

                                        <label for="name">Your Name:</label>
                                        <input type="text" id="name" name="name" placeholder="Enter Your Name" required>

                                        <label for="email">Your Email:</label>
                                        <input type="email" id="email" name="email" placeholder="Enter Email" required>

                                        <label for="course_id">Course:</label>
                                        <?php
                                        $courses = getQuery("ocs_course", "*", array("status" => "Y"));
                                        echo '<select id="course_id" name="course_id" required>';
                                        echo '<option value="">Select a course</option>';
                                        foreach ($courses as $c) {
                                            echo '<option value=' . $c['course_id'] . '>' . $c['name'] . '</option>';
                                        }
                                        ?>
                                        </select>
                                        <br>

                                        <label for="rating">Rating:</label>
                                        <select id="rating" name="rating" required>
                                            <option value="">Select a Rating</option>
                                            <option value="5">5 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="2">2 Stars</option>
                                            <option value="1">1 Star</option>
                                        </select>
                                        <br>
                                        <label for="feedback">Your Review:</label>
                                        <textarea id="feedback" name="feedback" rows="4" required></textarea>

                                        <button type="submit" name="submit" class="btn btn-warning">Submit Review</button>

                                    </form>
                                </div>
                            </div>
                            <!-- End Leave a Review section -->
                        </div>

                        <!-- start of online free test section -->
                        <div class="tabs_item">
                            <div class="online-test-content">
                                <div class="container py-4">
                                    <h3 class="text-center">Free <?= htmlspecialchars($course_name) ?> Practice Test</h3>
                                    <!-- Category Navigation -->
                                    <div class="category-nav">
                                        <ul class="nav nav-pills" id="categoryTabs">
                                            <!-- Tabs will be dynamically added here -->
                                        </ul>
                                    </div>

                                    <!-- Questions Container -->
                                    <div id="questionsContainer">
                                        <!-- Questions will be dynamically added here -->
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="submit-btn-container">
                                        <button id="submitTest" class="btn btn-warning">Submit Test</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end of online free test section -->

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class='col-md-12 left_header text-center'>
                    <h3>SUBSCRIBE <?= strtoupper($_GET['seo_url']); ?> TEST</h3>
                </div>
                <div class="account-wrap">
                    <ul>
                        <li>1 Month Subscription <span class="bold">$<?= number_format($configs['1month'], 2); ?></span></li>
                        <li>3 Months Subscription <span class="bold">$<?= number_format($configs['3month'], 2) ?></span></li>
                        <li>6 Months Subscription <span class="bold">$<?= number_format($configs['6month'], 2) ?></span></li>
                        <li>12 Months Subscription <span class="bold">$<?= number_format($configs['12month'], 2) ?></span></li>
                    </ul>
                    <?php if ($_GET['seo_url'] == "asvab-gt") { ?>
                        <a href="https://prep.asvab-tutoring.com/register" class="default-btn">Subscribe Now</a>
                    <?php } else { ?>
                        <a href="<?= $configs['vpath'] ?>register/<?= $_GET['seo_url'] ?>" class="default-btn">Subscribe Now</a>
                    <?php } ?>
                </div>
                <div class='clearfix'><br></div>
                <!--                <div class="account-wrap">-->
                <!--                    <img src='<?= $configs['vpath'] ?>img/best.jpg'>-->
                <div class="image-container">
                    <img src="<?= $configs['vpath'] . "template/template1/assets/img/slider_block.jpg"; ?>" height="300">
                    <div class="overlay-text">
                        <ul>
                            <?
                            $total_ques = getTotalRow("lesson_questions", array("course_id"=>$course_id));
                            $total_ques = ceil($total_ques / 10) * 10;
                            ?>
                            <li><? echo $total_ques; ?>+ Practice questions prepared by expert tutors</li>
                            <li>Updated Contents</li>
                            <li>Homework and Practice Test with Explanation</li>
                            <li>The Simulation of the Original Test</li>
                            <li>Progress Tracker</li>
                            <li>High Score Guarantee</li>
                            <li>Low Cost</li>
                        </ul>
                    </div>
                </div>
                <!--                </div>-->
                <div class='clearfix'><br></div>
                <div class="account-wrap">
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Course price section -->

<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3 style="color: orange;"><b><?= strtoupper($_GET['seo_url']) ?> Web Subcription Pricing</b></h3>
                </div>
                <div class="pricing-details" style="padding-left: 25%;">
                    <h4>Each subscription offers full access to:</h4>
                    <ul style="list-style-type: none;">
                        <li><span style="color: green; font-size: 1.2em;">&#10004;</span> <b>Highly comprehensive Lesson summary.</b></li>
                        <li><span style="color: green; font-size: 1.2em;">&#10004;</span> <b>Categorized Practice Tests</b> for targeted practice sessions.</li>
                        <li><span style="color: green; font-size: 1.2em;">&#10004;</span> <b>Comprehensive Prediction Tests</b> simulate the actual exam format for thorough evaluation.</li>
                        <li><span style="color: green; font-size: 1.2em;">&#10004;</span> <b>Additional Math Final Practice tests.</b></li>
                        <li><span style="color: green; font-size: 1.2em;">&#10004;</span> <b>Flashcards for increasing skill with basic formulas and rules.</b></li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="single-product-item">
                    <h3 class="bordered-heading">1 Month</h3>
                    <p class="product-price bold"> <span class="price">15$</span>/month</p>
                    <p class="product-price bold bottom-margin"> Total $15</p>
                    <p class="product-discount"> No Discount</p>
                    <a href="<?= $configs['vpath'] ?>register/<?= $_GET['seo_url'] ?>" class="cart-btn"><i class="fas fa-bell"></i> Subscribe Now</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="single-product-item">
                    <h3 class="bordered-heading">3 Months</h3>
                    <p class="product-price bold"> <span class="price">13.33$</span>/month</p>
                    <p class="product-price bold bottom-margin"> Total $40</p>
                    <p class="product-discount-save"> Save 10%</p>
                    <a href="<?= $configs['vpath'] ?>register/<?= $_GET['seo_url'] ?>" class="cart-btn"><i class="fas fa-bell"></i> Subscribe Now</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 offset-md-3 offset-lg-0 text-center">
                <div class="single-product-item">
                    <h3 class="bordered-heading">6 Months</h3>
                    <p class="product-price bold"> <span class="price">12.5$</span>/month</p>
                    <p class="product-price bold bottom-margin"> Total $75</p>
                    <p class="product-discount-save"> Save 16%</p>
                    <a href="<?= $configs['vpath'] ?>register/<?= $_GET['seo_url'] ?>" class="cart-btn"><i class="fas fa-bell"></i> Subscribe Now</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 offset-md-3 offset-lg-0 text-center">
                <div class="single-product-item">
                    <h3 class="bordered-heading">12 Months</h3>
                    <p class="product-price bold"> <span class="price">11.66$</span>/month</p>
                    <p class="product-price bold bottom-margin"> Total $140</p>
                    <p class="product-discount-save"> Save 22%</p>
                    <a href="<?= $configs['vpath'] ?>register/<?= $_GET['seo_url'] ?>" class="cart-btn"><i class="fas fa-bell"></i> Subscribe Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end course price section -->

<!-- Single Tutoring Cards section -->

<div class="card-section mt-150 mb-150">
    <div class="container">
        <?php
        foreach ($tutoring_detail_list as $d) {
        ?>
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="section-title" style="padding-top: 5%;">
                        <h3 style="color: orange;"><b><?= $d['title'] ?></b></h3>
                    </div>
                    <?php echo html_entity_decode($d['description']); ?>
                </div>
            </div>

            <div class="row">
                <?php
                foreach ($tutoring_package_list[$d['det_id']] as $session) {
                ?>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="single-card">
                            <div class="content">
                                <h3 class="orange-color"><strong> <?= htmlspecialchars($session['title']); ?> </strong></h3>
                            </div>
                            <div class="icon">
                                <img src="<?= $configs['vpath'] . "template/template1/assets/img/get-tutor-icon.png"; ?>" alt="image">
                            </div>
                            <div class="content">
                                <h4 class="orange-color"> Total $<?= htmlspecialchars($session['price']); ?></h4>
                                <p class="bold" style="color: red;">
                                    <?= !empty($session['discount']) ? htmlspecialchars($session['discount']) : 'No Discount'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?
        }
        ?>
    </div>
</div>


<!-- Start Question Answer section -->
<?php if ($total_faq) { ?>
    <div class="faq-section mt-150 mb-150">
        <div class="container">
            <div class="section-title text-center">
                <h3 style="color: orange; font-weight: bold;">Frequently Asked Questions</h3>
            </div>
            <div class="faq-accordion">
                <ul class="accordion">
                    <?php
                    $i = 0;
                    foreach ($faqs as $d) {
                        $show = "";
                        if (!$i) {
                            $show = "show";
                        }
                        echo '<li class="accordion-item">
                            <a class="accordion-title active" href="javascript:void(0)">
                            <i class="bx bx-plus"></i>' . $d['question'] . '</a>
                            <div class="accordion-content ' . $show . '">' . html_entity_decode($d['answers']) . '</div>
                          </li>';
                        $i++;
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
<!-- End Question Answer section -->


<!--  Start Related Courses -->
<?php
if ($number_of_courses) {
?>
    <section class="courses-area-style mt-150 mb-150">
        <div class="container">
            <div class="section-title">
                <br>
                <h3 style="color: orange; font-weight: bold;">Related Courses
                    <?php if ($_GET['location']) {
                        echo " <b>" . ucwords($_GET['location']) . "</b>";
                    } ?>
                </h3>
            </div>

            <div class="row">
                <?php foreach ($similar_courses as $c) { ?>
                    <div class="col-lg-4 col-md-6 col-sm-12"> <!-- Adjusted column classes for better responsiveness -->
                        <div class="single-course">
                            <a href="<?= $configs['vpath'] ?>course/<?= $c['seo_url'] ?>">
                                <img src="<?= $configs['vpath'] ?>image.php?image=<?= $configs['vpath'] . $c['thumb'] ?>&w=386&h=187" alt="<?= $c['name']; ?>" class='simage'>
                            </a>
                            <div class="course-content">
                                <a href="<?= $configs['vpath'] ?>course/<?= $c['seo_url'] ?>">
                                    <h3><?= $c['name'] ?></h3>
                                </a>
                                <ul class="rating">
                                    <li><i class="bx bxs-star"></i></li>
                                    <li><i class="bx bxs-star"></i></li>
                                    <li><i class="bx bxs-star"></i></li>
                                    <li><i class="bx bxs-star"></i></li>
                                    <li><i class="bx bxs-star"></i></li>
                                    <li><span>5</span><a href="#">(5 rating)</a></li>
                                </ul>
                                <p><?= $c['meta_desc'] ?></p>
                                <div class='text-center'><a href="<?= $configs['vpath'] ?>register/<?= $c['seo_url'] ?>" class="default-btn text-center">Subscribe Now</a></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </section>
<?php
} else {
?>
    <section class="courses-area-style mt-150 mb-150">
        <div class="container">
            <div class="section-title">
                <br>
                <h3 style="color: orange; font-weight: bold;">Related Courses
                    <?php if ($_GET['location']) {
                        echo " <b>" . ucwords($_GET['location']) . "</b>";
                    } ?>
                </h3>
            </div>
        </div>
        <div style="display: flex; justify-content: center;">
            No Related Course To View.
        </div>
    </section>
<?php
}
?>
<br>
<!-- end related course -->
</main>
<?php
function decodeQuestionHtml($str) {
    if (!$str) {
        return $str;
    }
    $str = str_replace('&#92;', '\\', $str);
    for ($i = 0; $i < 3; $i++) {
        $str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    return $str;
}

foreach ($ft_ques_list as $cat_name => $questions) {
    foreach ($questions as $idx => $q) {
        $ft_ques_list[$cat_name][$idx]['question'] = decodeQuestionHtml($q['question']);
        $ft_ques_list[$cat_name][$idx]['option_a'] = decodeQuestionHtml($q['option_a']);
        $ft_ques_list[$cat_name][$idx]['option_b'] = decodeQuestionHtml($q['option_b']);
        $ft_ques_list[$cat_name][$idx]['option_c'] = decodeQuestionHtml($q['option_c']);
        $ft_ques_list[$cat_name][$idx]['option_d'] = decodeQuestionHtml($q['option_d']);
    }
}
?>
<script>
    window.MathJax = {
        tex: {
            inlineMath: [['\\(', '\\)'], ['$', '$']],
            displayMath: [['\\[', '\\]'], ['$$', '$$']],
            processEscapes: true
        }
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
    const ft_cat_list = <?php echo json_encode($ft_cat_list); ?>;
    const cat_list = ft_cat_list.map(category => ({
        cat_name: category
    }));

    const ft_ques_list = <?php echo json_encode($ft_ques_list, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    function prepareQuestionHtml(str) {
        if (!str) return '';
        return str;
    }

    function typesetMath(elements) {
        if (typeof MathJax === 'undefined') return;
        const run = () => {
            if (MathJax.typesetPromise) {
                return MathJax.typesetPromise(elements);
            }
            MathJax.typeset(elements);
        };
        if (MathJax.startup && MathJax.startup.promise) {
            MathJax.startup.promise.then(run);
        } else {
            run();
        }
    }

    const ques = {};
    for (const [category, questions] of Object.entries(ft_ques_list)) {
        ques[category] = questions.map(q => {
            const options = [q.option_a, q.option_b, q.option_c, q.option_d]
                .filter(opt => opt !== null)
                .map(opt => prepareQuestionHtml(opt));
            return {
                question: prepareQuestionHtml(q.question),
                options,
                correctAnswer: q.answer,
                id: q.id
            };
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const categoryTabs = document.getElementById('categoryTabs');
        const questionsContainer = document.getElementById('questionsContainer');

        cat_list.forEach((category, index) => {
            const tabItem = document.createElement('li');
            tabItem.className = 'nav-item';

            const tabLink = document.createElement('a');
            tabLink.className = `nav-link ${index === 0 ? 'active' : ''}`;
            tabLink.href = `#${category.cat_name.replace(/\s+/g, '-')}`;
            tabLink.dataset.bsToggle = 'tab';
            tabLink.textContent = category.cat_name;
            tabLink.dataset.catIndex = index; 

            tabItem.appendChild(tabLink);
            categoryTabs.appendChild(tabItem);
        });

        cat_list.forEach((category, catIndex) => {
            const categoryDiv = document.createElement('div');
            categoryDiv.className = `tab-pane ${catIndex === 0 ? 'active' : 'hidden'}`;
            categoryDiv.id = category.cat_name.replace(/\s+/g, '-');

            if (ques[category.cat_name] && ques[category.cat_name].length > 0) {
                const categoryTitle = document.createElement('h5');
                categoryTitle.className = 'mb-4';
                categoryTitle.textContent = category.cat_name;
                categoryDiv.appendChild(categoryTitle);

                ques[category.cat_name].forEach((question, qIndex) => {
                    const card = document.createElement('div');
                    card.className = 'card question-card';
                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body';

                    const questionText = document.createElement('div');
                    questionText.className = 'mb-3 d-flex align-items-start';
                    const questionNumber = document.createElement('span');
                    questionNumber.className = 'question-number me-2';
                    questionNumber.textContent = `${qIndex + 1}.`;
                    const questionContent = document.createElement('div');
                    questionContent.className = 'question-content';
                    questionContent.innerHTML = question.question;
                    questionText.appendChild(questionNumber);
                    questionText.appendChild(questionContent);
                    cardBody.appendChild(questionText);

                    question.options.forEach((option, oIndex) => {
                        const optionDiv = document.createElement('div');
                        optionDiv.className = 'form-check mb-2';

                        const input = document.createElement('input');
                        input.className = 'form-check-input';
                        input.type = 'radio';
                        input.name = `q-${catIndex}-${qIndex}`;
                        input.id = `q-${catIndex}-${qIndex}-o-${oIndex}`;
                        input.value = oIndex;

                        const label = document.createElement('label');
                        label.className = 'form-check-label';
                        label.htmlFor = `q-${catIndex}-${qIndex}-o-${oIndex}`;
                        label.innerHTML = option;

                        optionDiv.appendChild(input);
                        optionDiv.appendChild(label);
                        cardBody.appendChild(optionDiv);
                    });

                    card.appendChild(cardBody);
                    categoryDiv.appendChild(card);
                });
            } else {
                const noQuestions = document.createElement('div');
                noQuestions.className = 'alert alert-info';
                noQuestions.textContent = 'No questions available for this category.';
                categoryDiv.appendChild(noQuestions);
            }

            questionsContainer.appendChild(categoryDiv);
        });

        document.querySelectorAll('#categoryTabs .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('#questionsContainer > div').forEach(pane => {
                    pane.classList.add('hidden');
                });
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).classList.remove('hidden');
                document.querySelectorAll('#categoryTabs .nav-link').forEach(tab => tab.classList.remove('active'));
                this.classList.add('active');

                typesetMath();
            });
        });

        document.getElementById('submitTest').addEventListener('click', function() {
            const submitBtn = document.getElementById('submitTest');
            const submitBtnContainer = submitBtn.parentElement;
            const activeTab = document.querySelector('#categoryTabs .nav-link.active');
            const activeCategoryId = activeTab.getAttribute('href').substring(1);
            const activeCategoryDiv = document.getElementById(activeCategoryId);
            const activeCategoryName = activeTab.textContent;
            const catIndex = parseInt(activeTab.dataset.catIndex);
            const courseId = <?php echo json_encode($course_id); ?>;

            let score = 0;
            let totalQuestions = 0;
            const questionResults = [];

            if (ques[activeCategoryName]) {
                ques[activeCategoryName].forEach((question, qIndex) => {
                    totalQuestions++;
                    const selectedOption = document.querySelector(`input[name="q-${catIndex}-${qIndex}"]:checked`);
                    const selectedVal = selectedOption ? parseInt(selectedOption.value) : null;
                    const isCorrect = selectedVal === (question.correctAnswer - 1);

                    if (isCorrect) score++;

                    questionResults.push({
                        question: question.question,
                        options: question.options,
                        selectedOption: selectedVal,
                        correctAnswer: question.correctAnswer - 1, // Convert to 0-based index
                        isCorrect
                    });
                });

                // [Keep your existing score calculation code...]

                activeCategoryDiv.innerHTML = '';

                // [Keep your existing results table code...]

                const reviewSection = document.createElement('div');
                reviewSection.className = 'answer-review';

                questionResults.forEach((result, index) => {
                    const questionCard = document.createElement('div');
                    questionCard.className = `card mb-3 ${result.isCorrect ? 'border-success' : 'border-danger'}`;

                    // Create options display with coloring
                    const optionsHtml = result.options.map((option, oIndex) => {
                        let optionClass = '';
                        if (oIndex === result.correctAnswer) {
                            optionClass = 'text-success fw-bold'; // Correct answer (green)
                        } else if (result.selectedOption !== null && oIndex === result.selectedOption && !result.isCorrect) {
                            optionClass = 'text-danger'; // Wrong selected answer (red)
                        }
                        return `<div class="${optionClass}">${String.fromCharCode(65 + oIndex)}. ${option}</div>`;
                    }).join('');

                    questionCard.innerHTML = `
                <div class="card-header ${result.isCorrect ? 'bg-success text-white' : 'bg-danger text-white'}">
                    Question ${index + 1}: ${result.isCorrect ? 'Correct' : 'Incorrect'}
                </div>
                <div class="card-body">
                    <div class="question-text mb-3">${result.question}</div>
                    <div class="options-display mb-3">
                        <strong>Options:</strong>
                        ${optionsHtml}
                    </div>
                </div>
            `;
                    reviewSection.appendChild(questionCard);
                });

                activeCategoryDiv.appendChild(reviewSection);

                // Create centered retake button
                const buttonContainer = document.createElement('div');
                buttonContainer.className = 'text-center my-4';

                const retakeButton = document.createElement('button');
                retakeButton.className = 'btn btn-warning';
                retakeButton.textContent = 'Retake This Test';
                retakeButton.addEventListener('click', function() {
                    location.reload();
                });

                buttonContainer.appendChild(retakeButton);
                activeCategoryDiv.appendChild(buttonContainer);

                typesetMath([reviewSection]);

                // Hide the main submit button
                submitBtn.style.display = 'none';
            }
        });

        // Add this CSS for better option display
        const style = document.createElement('style');
        style.textContent = `
    .options-display div {
        margin: 5px 0;
        padding: 5px;
        border-radius: 4px;
    }
    .text-success {
        color: #28a745 !important;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    .fw-bold {
        font-weight: bold;
    }
`;
        document.head.appendChild(style);

        // Add this to your initialization code to show the submit button when switching tabs
        document.querySelectorAll('#categoryTabs .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                document.getElementById('submitTest').style.display = 'block';
            });
        });

        typesetMath([questionsContainer]);
    });
</script>




<style>
    #questionsContainer,
    #questionsContainer * {
        color: #000 !important;
    }

    #categoryTabs,
    #categoryTabs * {
        color: #000 !important;
    }

    .question-card img {
        max-width: 100%;
        height: auto;
    }

    .math-tex {
        display: inline-block;
    }
</style>

<!-- start Online free test -->
<style>
    .category-nav {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .category-nav .nav-link {
        color: #495057;
        font-weight: 500;
    }

    .category-nav .nav-link.active {
        background-color: #ffb607;
        font-weight: 600;
    }

    .question-card {
        margin-bottom: 20px;
        border-left: 4px solid #0d6efd;
    }

    .question-number {
        font-weight: bold;
        margin-right: 10px;
    }

    .submit-btn-container {
        text-align: center;
        margin: 30px 0;
    }

    .hidden {
        display: none;
    }
</style>
<!-- end Online free test -->

<style>
    .cust-tabs {
        list-style-type: none;
        padding-left: 0;
        text-align: center;
        border-bottom: 1px solid #eaedff;
    }

    .cust-tabs li {
        display: inline-block;
        line-height: initial;
        margin-right: 15px;
        margin-left: 15px;
        margin-bottom: 20px;
    }

    .cust-tabs li a {
        display: inline-block;
        position: relative;
        color: #333333;
        border-bottom: none;
        line-height: 1;
        font-size: 16px;
        font-weight: 500;
    }

    .cust-tabs li a:hover {
        color: var(--main-color);
    }

    .cust-tabs li:last-child {
        margin-right: 0;
    }

    .cust-tabs li.current a {
        color: var(--main-color);
    }

    .tabs_item_div {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .review-section {
        max-width: 600px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }


    form {
        display: flex;
        flex-direction: column;
    }


    input[type="text"],
    input[type="email"],
    select,
    textarea {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    textarea {
        resize: none;
    }

    button {
        padding: 10px 20px;
        background-color: #ffb607;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #ffb607;
    }




    .review-content {
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .review-header h3 {
        font-size: 24px;
        color: #333;
    }

    .review-rating {
        display: flex;
        align-items: center;
    }

    .rating-stars {
        color: #ffa500;
        font-size: 20px;
        margin-right: 10px;
    }

    .review-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        grid-gap: 20px;
    }

    .review-item {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .reviewer-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .reviewer-details h4 {
        font-size: 16px;
        color: #333;
        margin-bottom: 5px;
    }

    .review-rating {
        color: #ffa500;
        font-size: 16px;
    }

    .review-text {
        font-size: 16px;
        color: #666;
        text-align: justify;
    }


    .faq-section {
        background-color: #f9f9f9;
        padding: 50px 0;
    }

    /* Style for the product section */
    .product-section {
        background-color: #f9f9f9;
        padding: 50px 0;
    }

    .section-title h3 {
        font-size: 36px;
        color: #333;
        /*        margin-bottom: 10px;*/
    }

    .section-title p {
        font-size: 16px;
        color: #666;
    }

    .single-product-item {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .single-product-item:hover {
        transform: scale(1.07);
    }

    .single-product-item-tutor {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .single-product-item-tutor:hover {
        transform: scale(1.07);
    }

    .product-image img {
        max-width: 100%;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    h3 {
        font-size: 24px;
        color: #333;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 1em;
        /*        color: #f90;*/
    }

    .bottom-margin {
        margin-bottom: 15px;
    }

    .product-price .price {
        font-size: 1.5em;
        color: red;
        /* Set color to red */
    }

    .cart-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #f90;
        color: #fff;
        text-decoration: none;
        border-radius: 15px;
        transition: background-color 0.3s;
    }

    .cart-btn:hover {
        background-color: green;
        color: #fff;
    }

    .pricing-details-tutor {
        /*        background-color: #f9f9f9;*/
        padding: 30px;
        border-radius: 5px;
        margin-top: 10px;
        text-align: left;
    }

    .pricing-details {
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 5px;
        margin-top: 10px;
        text-align: left;
    }

    .pricing-details h4 {
        font-size: 20px;
        color: #333;
        margin-bottom: 20px;
    }

    .pricing-details ul {
        list-style-type: disc;
        padding-left: 20px;
    }

    .pricing-details li {
        font-size: 16px;
        color: #666;
        margin-bottom: 10px;
    }

    .section-title {
        text-align: center;
    }

    .section-title h3 {
        font-size: 36px;
        color: #333;
        margin-bottom: 20px;
    }

    .section-title p {
        font-size: 16px;
        color: #666;
    }

    .bordered-heading {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
    }

    .bold {
        font-weight: bold;
        /* Make font bold */
    }

    .product-discount {
        padding: 8px;
        /* Add padding for better readability */
        background-color: #f0f0f0;
        /* Set background color to lighter ash */
        color: #444;
        /* Set font color to dark ash */
        font-weight: bold;
        display: inline-block;
        /* Ensure the background color covers the entire width of the content */
        border-radius: 50px;
        /* Add rounded corners */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Add a subtle box shadow */
    }

    .product-discount-save {
        padding: 8px;
        /* Add padding for better readability */
        background-color: #D4EFDF;
        /* Set background color to lighter ash */
        color: green;
        /* Set font color to dark ash */
        font-weight: bold;
        display: inline-block;
        /* Ensure the background color covers the entire width of the content */
        border-radius: 50px;
        /* Add rounded corners */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Add a subtle box shadow */
    }

    .single-card {
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Align items vertically */
        align-items: center;
        /* Align items horizontally */
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        margin: 30px;
        margin-bottom: 20%;
        border: 1px solid #8C8C8C;
    }

    .single-card:hover {
        border: 1px solid #f90;
    }

    .single-card:hover .orange-color {
        color: #f90;
    }

    .icon {
        margin-bottom: 20px;
        width: 50%;
        height: 80%;
    }


    .faq-section {
        background-color: #f9f9f9;
        padding: 50px 0;
    }

    .section-title h3 {
        font-size: 36px;
        color: #333;
        margin-bottom: 20px;
    }

    .faq-item {
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .question-answer {
        text-align: left;
    }

    .question {
        font-size: 20px;
        color: #333;
        margin-bottom: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .toggle-icon {
        font-size: 24px;
        font-weight: bold;
        margin-right: 10px;
        transition: transform 0.3s ease;
    }

    .answer {
        font-size: 16px;
        color: #666;
        display: none;
        transition: max-height 0.3s ease-in-out;
        overflow: hidden;
    }

    .image-container {
        position: relative;
    }

    .overlay-text {
        position: absolute;
        top: 61%;
        left: 45%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
        text-align: left;
        /*    padding: 20px;*/
    }

    .overlay-text ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .overlay-text li {
        margin-bottom: 6px;
    }
</style>

<style>
    /* Dropdown container */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown toggle button */
    .dropdown-toggle {
        color: black;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        margin-right: 20px;
        /* Moved from inline style */
    }

    /* Toggle icon */
    .toggle-icon {
        margin-left: 10px;
        /* Space between text and icon */
        font-size: 12px;
        /* Adjust icon size */
        transition: transform 0.3s ease;
        /* Smooth rotation animation */
    }

    /* Dropdown content */
    .dropdown-content {
        display: none;
        color: black;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Dropdown options */
    .dropdown-option {
        color: black;
        position: relative;
    }

    /* Dropdown option toggle button */
    .dropdown-option-toggle {
        color: black;
        width: 100%;
        text-align: left;
        padding: 10px;
        background-color: #f1f1f1;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .dropdown-option-toggle:hover {
        background-color: #F1C40F;
    }

    /* Dropdown submenu */
    .dropdown-submenu {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Show dropdown content when active */
    .dropdown-content.show {
        display: block;
    }

    /* Show submenu when active */
    .dropdown-submenu.show {
        display: block;
    }
</style>

<style>
    .study-btn {
        margin-left: 15px;
        margin-top: 5px;
        width: 120px;
        border-color: red;
        background-color: white;
        color: red;
        font-weight: bold;
        border-width: 2px;
    }

    .study-btn:hover {
        transform: scale(1.05);
        color: red;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .default-btn {
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 270px;
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 12px;
    }

    .dropdown-content a:hover {
        background-color: #F1C40F;
    }

    .dropdown-content.show {
        display: block;
    }

    .nested-dropdown-content {
        display: none;
    }

    .nested-dropdown:hover .nested-dropdown-content {
        display: block;
    }
</style>

<script>
    document.querySelectorAll('.question').forEach(function(question) {
        question.addEventListener('click', function() {
            this.classList.toggle('active');
            var answer = this.nextElementSibling;
            if (answer.style.display === 'block') {
                answer.style.display = 'none';
                this.querySelector('.toggle-icon').textContent = '+';
            } else {
                answer.style.display = 'block';
                this.querySelector('.toggle-icon').textContent = '-';
            }
        });
    });
</script>