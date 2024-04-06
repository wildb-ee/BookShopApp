
<div class="container mt-5">
    <h2 class="text-center mb-5">Admin Order View</h2>

    <div class="card mb-4">
            <div class="card-header">
                <h5>Order Statistics</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Orders Made Today</th>
                            <td><?= $statistics['orders_made_today']; ?></td>
                        </tr>
                        <tr>
                            <th>Average Order Amount Today</th>
                            <td><?= '$' . number_format($statistics['average_order_amount_today'], 2); ?></td>
                        </tr>
                        <tr>
                            <th>Orders in Delivery</th>
                            <td><?= $statistics['orders_in_delivery']; ?></td>
                        </tr>
                        <tr>
                            <th>Orders Delivered</th>
                            <td><?= $statistics['orders_delivered']; ?></td>
                        </tr>
                        <tr>
                            <th>Orders Canceled</th>
                            <td><?= $statistics['orders_canceled']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>










    <?php foreach ($data["orders"] as $order): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order #<?= $order['order_id']; ?></h5>
                <p>Estimated Delivery Date: <?= $order['estimated_delivery_date']; ?></p>
                <p>Their username: <?= $order['username']; ?></p>
                <p>The Customer's first name: <?= $order['first_name']; ?></p>
                <p>The Customer's last name: <?= $order['last_name']; ?></p>
                <p>Phone Number: <?= $order['phone']; ?></p>


                <form action="/hws/my_prj/public_html/order/change_address" method="post">
                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                    <div class="form-group">
                        <label for="delivery_address">Delivery Address:</label>
                        <select class="form-control" id="delivery_address" name="delivery_address">
                            <?php foreach ($user_addresses as $address): ?>
                                <?php if ($address['user_id'] == $order['user_id']): ?>
                                <option value="<?= $address['street'] . ', ' . $address['city']; ?>" <?= ($order['delivery_address'] == $address['street'] . ', ' . $address['city']) ? 'selected' : ''; ?>>
                                    <?= $address['street'] . ', ' . $address['city']; ?>
                                </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Address</button>
                </form>




                <form action="/hws/my_prj/public_html/order/change_status" method="post">
                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                    <div class="form-group">
                        <label for="new_status">Change Status:</label>
                            <select class="form-control" id="new_status" name="new_status">
                                <option value="Pending" <?= ($order["status_name"] == "Pending") ? 'selected' : '' ?>>Pending</option>
                                <option value="Processing" <?= ($order["status_name"]  == "Processing") ? 'selected' : '' ?>>Processing</option>
                                <option value="Shipped" <?= ($order["status_name"]  == "Shipped") ? 'selected' : '' ?>>Shipped</option>
                                <option value="Delivered" <?= ($order["status_name"]  == "Delivered") ? 'selected' : '' ?>>Delivered</option>
                                <option value="Canceled" <?= ($order["status_name"]  == "Canceled") ? 'selected' : '' ?>>Canceled</option>

                            </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Status</button>
                </form>
            </div>
            <div class="card-body">
                <?php foreach ($data["order_details"]  as $detail): ?>
                    <?php if ($detail['order_id'] == $order['order_id']): ?>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p><strong>Book Name:</strong> <?= $detail['book_name']; ?></p>
                                <p><strong>Book Description:</strong> <?= $detail['book_description']; ?></p>
                                <p><strong>Author:</strong> <?= $detail['author_name']; ?></p>
                                <p><strong>Quantity:</strong> 
                                    <?= $detail['quantity']; ?>
                                    <form action="/hws/my_prj/public_html/order/update_details" method="post" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                        <input type="hidden" name="book_id" value="<?= $detail['book_id']; ?>">
                                        <input type="number" name="new_quantity" value="<?= $detail['quantity']; ?>" class="form-control" style="width: 80px;">
                                        <button type="submit" class="btn btn-primary btn-sm">Change</button>
                                    </form>
                                </p>
                                <p><strong>Price:</strong> $<?= $detail['price']; ?></p>

                                <form action="/hws/my_prj/public_html/order/delete_detail" method="post" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                    <input type="hidden" name="book_id" value="<?= $detail['book_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="card-footer">
                <form action="/hws/my_prj/public_html/order/cancel" method="post" class="d-inline">
                    <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                    <button type="submit" class="btn btn-danger">Delete Order</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
