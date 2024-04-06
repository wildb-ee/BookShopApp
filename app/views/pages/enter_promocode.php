<div class="container mt-5">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <h2 class="mb-4">Enter Promo Code</h2>
        <form action="/hws/my_prj/public_html/order/promocode" method="post">
            <div class="form-group">
                <label for="promo_code">Promo Code:</label>
                <input type="text" class="form-control" id="promo_code" name="promo_code" required>
            </div>
            <button type="submit" class="btn btn-primary">Apply Promo Code</button>
        </form>

        <?php if (isset($discounted_cart)): ?>
            <h3 class="mt-5">Discounted Cart Items</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Discounted Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($discounted_cart as $item): ?>
                        <tr>
                            <td><?php echo $item['book_name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo number_format($item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="/hws/my_prj/public_html/order/create" class="btn btn-primary mr-2 mb-5">Make Order With Discount</a>

            

        <?php endif; ?>

        <a href="/hws/my_prj/public_html/order/create" class="btn btn-danger mt-5 mr-2 mb-5">Make Order Without Any Discout</a>

    </div>