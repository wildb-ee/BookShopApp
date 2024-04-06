<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!empty($data)) {
    foreach ($data as $book) {
        ?>
        <div class="card mb-3">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="<?= $book['book_image'] ?? ''; ?>" class="card-img" alt="<?= $book['book_name'] ?? ''; ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= $book['book_name'] ?? ''; ?></h5>
                        <p class="card-text"><?= $book['book_description'] ?? ''; ?></p>
                        <p class="card-text"><strong>Price:</strong> $<?= $book['price'] ?? ''; ?></p>
                        <p class="card-text"><strong>Stock Quantity:</strong> <?= $book['stock_quantity'] ?? ''; ?></p>
                        <p class="card-text"><strong>Book Type:</strong> <?= $book['book_type'] ?? ''; ?></p>

                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form class="input-group mb-3" action="/hws/my_prj/public_html/cart/add" method="post">
                                <input type="hidden" name="book_id" value="<?= $book['book_id']; ?>">
                                <input type="number" class="form-control" name="quantity" placeholder="Quantity" aria-label="Quantity" aria-describedby="quantity-addon">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Add to Cart</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <a href="/hws/my_prj/public_html/user/login" class="btn btn-primary">Login to add to cart</a> 
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>No books available.</p>";
}
?>
