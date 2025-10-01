<?= $this->extend('layouts/base'); ?>

<?= $this->section('title'); ?>
    <?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2>Featured Books</h2>
    <div id="featuredBooksCarousel" class="carousel carousel-multi slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <?php if (!empty($featured_books)):
                $isFirst = true;
                foreach ($featured_books as $book): ?>
                    <div class="carousel-item <?= $isFirst ? 'active' : '' ?>">
                        <div class="d-flex justify-content-center">
                            <div class="card card-block mx-2" style="min-width: 300px;">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($book->title) ?></h5>
                                    <p class="card-text">Author: <?= esc($book->author) ?></p>
                                    <p class="card-text">Price: $<?= esc($book->price) ?></p>
                                    <a href="#" class="btn btn-primary">Add to Cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                $isFirst = false;
                endforeach;
            else: ?>
                <div class="carousel-item active">
                    <p>No featured books found.</p>
                </div>
            <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#featuredBooksCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: black; border-radius: 50%;"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#featuredBooksCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: black; border-radius: 50%;"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<?= $this->endSection(); ?>