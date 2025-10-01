<?= $this->extend('layouts/base'); ?>

<?= $this->section('title'); ?>
    <?= $page_title ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container">
    <h2>Featured Books</h2>
    <!-- The main carousel wrapper, which will contain all the book cards -->
        <div class="carousel-wrapper relative overflow-hidden rounded-lg shadow-xl bg-white p-4">
        <!-- This is the container that will be animated by jQuery -->
        <div id="book-carousel" class="flex flex-nowrap -ml-2 carousel-slides slide-transition">
            <?php if (!empty($featured_books)):

                foreach ($featured_books as $book): ?>
                    <div class="book-item flex-shrink-0 w-full sm:w-1/3 p-2">
                        <div class="card bg-gray-50 rounded-lg shadow-md p-6 h-full flex flex-col justify-between">
                            
                                <div>
                                    <h5 class="card-title card-title text-xl font-semibold mb-2 text-gray-900"><?= esc($book->title) ?></h5>
                                    <p class="card-text card-text text-gray-600 mb-1">Author: <?= esc($book->author) ?></p>
                                    <p class="card-text card-text text-gray-600">Price: $<?= esc($book->price) ?></p>
                                    <?php if (isset($cart_books[$book->id])):
                                        $quantity = $cart_books[$book->id]; ?>
                                        <p class="text-success">Added to cart (quantity: <?= $quantity ?>)</p>
                                    <?php endif; ?>
                                    <button class="btn btn-primary add-to-cart-btn" data-book-id="<?= esc($book->id) ?>">Add to Cart</button>
                                    
                                    
                                </div>
                            
                        </div>
                    </div>
                <?php 

                endforeach;
            else: ?>
                <div class="carousel-item active">
                    <p>No featured books found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
        $(document).ready(function() {
            const carousel = $('#book-carousel');
            const items = carousel.find('.book-item');
            const totalItems = items.length;
            const slideDuration = 500; // Animation duration in ms
            const intervalDuration = 3000; // 3 seconds

            let visibleItemsCount;

            // Function to update the number of visible items based on screen size
            const updateVisibleItems = () => {
                if ($(window).width() >= 640) { // Tailwind's 'sm' breakpoint
                    visibleItemsCount = 3;
                } else {
                    visibleItemsCount = 1;
                }
            };

            // Initial setup and on window resize
            $(window).on('resize', updateVisibleItems);
            updateVisibleItems();

            // Function to perform the slide animation
            const slideNext = () => {
                const itemWidth = items.first().outerWidth();
                const itemsToMove = 1;

                // Animate the carousel container to the left
                carousel.css('transition-duration', `${slideDuration}ms`);
                carousel.css('transform', `translateX(-${itemWidth * itemsToMove}px)`);

                // Wait for the animation to finish
                setTimeout(() => {
                    // Move the first 'itemsToMove' elements to the end of the list
                    for (let i = 0; i < itemsToMove; i++) {
                        carousel.append(carousel.find('.book-item:first-child'));
                    }
                    // Reset the transform without a transition to 'snap' the carousel back
                    carousel.css('transition-duration', '0s');
                    carousel.css('transform', `translateX(0)`);
                }, slideDuration);
            };

            // Start the auto-slide interval
            let interval = setInterval(slideNext, intervalDuration);

            // Add a mouse enter/leave effect to pause on hover
            $('.carousel-wrapper').hover(
                function() {
                    clearInterval(interval);
                },
                function() {
                    interval = setInterval(slideNext, intervalDuration);
                }
            );
        });
    </script>
<?= $this->endSection(); ?>
