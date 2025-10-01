<?= $this->extend('layouts/base'); ?>

<?= $this->section('content'); ?>

<div class="container mt-4">
    <h2>Search Results for "<?= esc($query); ?>"</h2>
    
    <hr>
    
    <?php if (!empty($results)): ?>
        <p>Found <?= count($results); ?> results.</p>
        <ul class="list-group" id="bks">
            <?php foreach ($results as $book): ?>
                <li class="list-group-item">
                    <?php if (!empty($book->cover_photo)): ?>
                        <img src="<?= base_url('public/images/covers/' . esc($book->cover_photo)); ?>" alt="<?= esc($book->title); ?> cover photo" class="img-thumbnail" style="width: auto; height: 100px; margin-right: 15px;">
                    <?php endif; ?>
                    <h4><?= esc($book->title); ?></h4>
                    <p><strong>Author:</strong> <?= esc($book->author); ?></p>
                    <p><strong>ISBN:</strong> <?= esc($book->isbn); ?></p>
                    <p><strong>Price:</strong> LKR. <?= esc(number_format($book->price,2)); ?></p>
                    <?php if (isset($cart_books[$book->id])):
                        $quantity = $cart_books[$book->id]; ?>
                        <p class="text-success">Added to cart (quantity: <?= $quantity ?>)</p>
                    <?php endif; ?>
                    <button class="btn btn-primary add-to-cart-btn" data-book-id="<?= esc($book->id); ?>">Add to Cart</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Sorry, no results found for your search query.</p>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>