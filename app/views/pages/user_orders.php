<div class="container mt-5">
        <h1 class="mb-4"><i class="fas fa-shopping-bag"></i> My Orders</h1>
        <div class="row">
            <?php foreach ($orders as $order): ?>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Order ID: <?= $order['order_id'] ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Order Code: <?= $order['order_code'] ?></h5>
                            <p class="card-text">Estimated Delivery Date: <?= $order['estimated_delivery_date'] ?></p>
                            <p class="card-text">Status: <?= $order['status_name'] ?></p>
                            <p class="card-text">Total Amount: <?= $order['total_amount'] ?></p>
                            <p class="card-text">Delivery Address: <?= $order['delivery_address'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>