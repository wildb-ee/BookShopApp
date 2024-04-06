<h1><?= $data["title"] ?></h1>
<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data["cart_items"] as $item): ?>
                <tr>
                    <td><?= $item['first_name'] ?></td>
                    <td><?= $item['last_name'] ?></td>

                    <td><?= $item['book_name'] ?></td>

                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['price'] ?></td>
                    <td><?= $item['price_total'] ?></td>


                </tr>
            <?php endforeach; ?>
        </tbody>


</table>
<div>
    <h2>Total Price:</h2>
    <?php
    $totalPrice = 0;
    foreach ($data["cart_items"] as $item) {
        $totalPrice += $item['price_total'];
    }
    echo $totalPrice;
    ?>
</div>