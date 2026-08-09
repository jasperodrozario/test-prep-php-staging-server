<main>
    <div class="page-title-area bg-5 text-center">
        <div class="container">
            <div class="page-title-content">
                <h2>Blog</h2>
            </div>
        </div>
    </div>

    <div class="blog-column-three-area ptb-100">
        <div class="container">
            <div class="row">
                <?php 
                $firstCategory = true; // Initialize a flag to identify the first category
                foreach ($blogs as $category => $blog) {
                    $categoryId = preg_replace('/\s+/', '-', strtolower($category)); // Generate a unique ID for each category ?>
                    <div class="category-section w-100 mb-4">
                        <h3 class="category-title bg-light p-3 mb-0" data-toggle="collapse" data-target="#<?=$categoryId?>" aria-expanded="<?= $firstCategory ? 'true' : 'false' ?>" aria-controls="<?=$categoryId?>">
                            <?=$category?> <i class="fas fa-chevron-down float-right"></i>
                        </h3>
                        
                        <div id="<?=$categoryId?>" class="collapse<?= $firstCategory ? ' show' : '' ?>">
                            <div class="row">
                                <?php foreach ($blog as $d) { ?>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="single-news card h-100 shadow-sm">
                                            <a href="<?=$configs['vpath']?>blog/<?=$d['id']?>-<?=seo_url($d['name']) ?>">
                                                <img src="<?=$configs['vpath']?>image.php?image=<?=$configs['vpath'].$d['pic']?>&w=386&h=238" alt="<?=$d['name']?>" class="card-img-top himage">
                                            </a>
                                            <div class="card-body">
                                                <a href="<?=$configs['vpath']?>blog/<?=$d['id']?>-<?=seo_url($d['name']) ?>">
                                                    <h5 class="card-title"><?=$d['name']?></h5>
                                                </a>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item">By: <a href="https://www.asvab-tutoring.com/" class="text-muted"><?=ucwords($d['author']) ?></a></li>
                                                    <li class="list-inline-item float-right text-muted">Posted on <?=showdate($d['date']) ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    $firstCategory = false; // Set the flag to false after the first category
                } ?>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap CSS -->


<!-- Custom CSS -->
<style>
    .category-title {
        cursor: pointer;
        font-weight: bold;
        color: orange;
    }
    .category-title:hover {
        background-color: #f8f9fa;
    }
    .single-news img.himage {
        transition: transform 0.5s ease;
    }
/*
    .single-news img.himage:hover {
        transform: scale(1.05);
    }
*/
    .single-news .card-body {
        padding: 15px;
    }
    .single-news .card-title {
        font-size: 1.25rem;
    }
    .card-body:hover .card-title {
        color: orange;
    }
</style>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
