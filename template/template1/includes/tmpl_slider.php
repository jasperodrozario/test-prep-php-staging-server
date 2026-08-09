<main>
<section class="banner-area-two">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container-fluid social">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="banner-content">
                            <h1 class="wow animate__animated animate__fadeInLeft" data-wow-delay="0.3s">Welcome to Our Online Test Prep Courses Developed by Test Prep Experts.</h1>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="banner-img wow animate__animated animate__fadeInRight" data-wow-delay="0.3s">

                            <div class="image-container">
                                <!--    <img src="<?= $configs['vpath'] . "template/template1/assets/img/slider_block.jpg"; ?>" height="250"> -->
                                <div class="overlay-text">
                                    <h3 class="text-heading">WHY WE ARE THE BEST CHOICE</h3>
                                    <ul>
                                        <?
                                        $total_ques = getTotalRow("question", "");
                                        $total_ques = ceil($total_ques / 10) * 10;
                                        ?>
                                        <li><i class="fas fa-check-circle"></i> <? echo $total_ques; ?>+ Practice questions prepared by expert tutors</li>
                                        <li><i class="fas fa-check-circle"></i> Updated Contents</li>
                                        <li><i class="fas fa-check-circle"></i> Homework and Practice Test with Explanation</li>
                                        <li><i class="fas fa-check-circle"></i> The Simulation of the Original Test</li>
                                        <li><i class="fas fa-check-circle"></i> Progress Tracker</li>
                                        <li><i class="fas fa-check-circle"></i> High Score Guarantee</li>
                                        <li><i class="fas fa-check-circle"></i> Low Cost</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="banner-shape-1">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-1.png" alt="Image">
                            </div>
                            <div class="banner-shape-2">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-2.png" alt="Image">
                            </div>
                            <div class="banner-shape-3">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-3.png" alt="Image">
                            </div>
                            <div class="banner-shape-4">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-4.png" alt="Image">
                            </div>
                            <div class="banner-shape-5 rotated">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-5.png" alt="Image">
                            </div>
                            <div class="banner-shape-6">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-6.png" alt="Image">
                            </div>
                            <div class="banner-shape-7 rotated">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-7.png" alt="Image">
                            </div>
                            <div class="banner-shape-8">
                                <img src="<?= $configs['template_path'] ?>assets/img/banner-img/shape-img-8.png" alt="Image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .image-container {
        position: relative;
    }

    .text-heading {
        color: white;
        font-size: 20px;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 10px;
        padding: 10px;
        border-bottom: 3px solid #F1C40F;
        /* Golden underline */
    }

    /* Overlay text */
    .overlay-text {
        background-color: #09233e;
        border: 16px solid #F1C40F;
        /* Thicker border with gold color */
        position: absolute;
        top: 50%;
        /* Center vertically */
        left: 50%;
        /* Center horizontally */
        transform: translate(-50%, -50%);
        /* Adjust for exact centering */
        color: white;
        font-size: 18px;
        font-weight: bold;
        text-align: left;
        width: 80%;
        /* Adjust width to fit content */
        max-width: 600px;
        /* Limit maximum width for larger screens */
        padding: 15px;
        /* Add some padding for better appearance */
        box-shadow: 0px 0px 10px rgba(241, 196, 15, 0.5);
        /* Optional: adds glow effect */
    }


    .overlay-text ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .overlay-text li {
        margin-bottom: 6px;
        /* Adjust spacing between list items */
    }

    .overlay-text li i {
        margin-right: 5px;
        /* Space between icon and text */
        color: #F1C40F;
        /* Icon color (yellow) */
    }

    @media (max-width: 2560px) {
        .overlay-text {
            position: relative;
            top: auto;
            left: auto;
            transform: none;
            width: 100%;
            max-width: none;
        }
    }
</style>