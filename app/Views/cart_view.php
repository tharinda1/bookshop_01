<?= $this->extend('layouts/base'); ?>

<?= $this->section('content'); ?>

<div class="container mt-4">
    <h2>Your Shopping Cart</h2>

    <?php if (!empty($cartItems)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($cartItems as $item): ?>
                    <tr data-item-id="<?= $item['id'] ?>">
                        <td data-label="Book Title"><?= esc($item['title']); ?></td>
                        <td data-label="Quantity" class="quantity-controls" data-item-id="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
                            <button class="btn btn-sm btn-secondary quantity-decrease" <?= ($item['quantity'] <= 1) ? 'disabled' : '' ?>>-</button>
                            <input type="text" class="form-control quantity-input" value="<?= $item['quantity'] ?>" style="width: 60px; display: inline-block; text-align: center; font-family: monospace;">
                            <button class="btn btn-sm btn-secondary quantity-increase">+</button>
                        </td>
                        <td data-label="Price" class="price">LKR. <?= esc(number_format($item['price'], 2)); ?></td>
                        <td data-label="Subtotal" class="subtotal">LKR. <?= esc(number_format($item['quantity'] * $item['price'], 2)); ?></td>
                        <td data-label="Action">
                            <?= form_open("cart/remove/" . $item['id'], ['class' => 'd-inline']); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            <?= form_close(); ?>
                        </td>
                    </tr>
                    <?php $total += ($item['quantity'] * $item['price']); ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td class="total-amount"><strong>LKR. <?= esc(number_format($total, 2)); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        <div class="text-end mt-3">
            <a href="<?= site_url('cart/checkout'); ?>" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Your cart is empty.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>

<script>
$(document).ready(function() {
    function updateCartItem(cartItemId, quantity) {
        return $.ajax({
            url: '<?= site_url('cart/update') ?>',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                cart_item_id: cartItemId,
                quantity: quantity
            }),
            dataType: 'json'
        });
    }

    function updateTotal() {
        let total = 0;
        $('tbody tr').each(function() {
            const controls = $(this).find('.quantity-controls');
            const quantity = parseInt(controls.find('.quantity-input').val());
            const price = parseFloat(controls.data('price'));
            if (!isNaN(quantity) && !isNaN(price)) {
                total += (quantity * price);
            }
        });
        $('tfoot .total-amount strong').text('LKR. ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    $('.quantity-controls').each(function() {
        const controls = $(this);
        const itemId = controls.data('item-id');
        const price = parseFloat(controls.data('price'));
        const input = controls.find('.quantity-input');
        const decreaseBtn = controls.find('.quantity-decrease');
        const increaseBtn = controls.find('.quantity-increase');
        const row = controls.closest('tr');
        const subtotalCell = row.find('.subtotal');

        function updateRow(quantity) {
            if (quantity < 1) {
                quantity = 1;
            }

            const subtotal = quantity * price;
            subtotalCell.text('LKR. ' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            input.val(quantity);
            decreaseBtn.prop('disabled', quantity <= 1);

            updateCartItem(itemId, quantity)
                .done(function(response) {
                    if (response.status === 'success') {
                        console.log('Cart updated for item ' + itemId);
                    } else {
                        console.error('Failed to update cart:', response.message);
                    }
                })
                .fail(function(error) {
                    console.error('Error updating cart:', error);
                })
                .always(function() {
                    updateTotal();
                });
        }

        decreaseBtn.on('click', function() {
            let quantity = parseInt(input.val());
            if (quantity > 1) {
                updateRow(quantity - 1);
            }
        });

        increaseBtn.on('click', function() {
            let quantity = parseInt(input.val());
            updateRow(quantity + 1);
        });

        input.on('change', function() {
            let quantity = parseInt(input.val());
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
            }
            updateRow(quantity);
        });
    });
});
</script>
<?= $this->endSection(); ?>