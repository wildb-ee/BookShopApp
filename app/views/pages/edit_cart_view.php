<div class="mb-5 mt-5">

<?php if (!empty($cart_items)): ?>
    <?php foreach ($cart_items as $item): ?>
        <div class="card mb-3">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="<?= $item['book_image'] ?? ''; ?>" class="card-img" alt="<?= $item['book_name'] ?? ''; ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= $item['book_name'] ?? ''; ?></h5>
                        <p class="card-text"><?= $item['book_description'] ?? ''; ?></p>
                        <p class="card-text"><strong>Price:</strong> $<?= $item['price'] ?? ''; ?></p>
                        <p class="card-text"><strong>Book Type:</strong> <?= $item['book_type'] ?? ''; ?></p>

                        <form action="edit_cart" method="POST">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?? ''; ?>">
                            <div class="input-group">
                                <input type="number" class="form-control" name="quantity" value="<?= $item['quantity'] ?? ''; ?>" min="1">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" name="update_quantity">Update Quantity</button>
                                </div>
                            </div>
                        </form>
                        
                        <form method="post" action="edit_cart">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?? ''; ?>">
                            <button class="btn btn-danger mt-2" type="submit" name="delete_item">Remove from Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="text-center">
        <button class="btn btn-success" onclick="window.location.href='/hws/my_prj/public_html/cart/pay'">Pay</button>
    </div>
<?php else: ?>
    <p class="text-center">No items in the cart.</p>
<?php endif; ?>





</div>
