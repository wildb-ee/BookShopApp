<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}?>



<div class="container mt-5 mb-5">
    <div class="row">
        
        <div class="col-md-4">
            <form action="search" method="GET">
                
                <div class="form-group">
                    <label for="searchInput">Search:</label>
                    <input type="text" class="form-control" id="searchInput" name="find" placeholder="Enter keywords">
                </div>
                
                <div class="form-group">
                    
                    <label for="authorFirstNameInput">Author First Name:</label>
                    <input type="text" class="form-control" id="authorFirstNameInput" name="author_first_name" placeholder="Enter first name">
                </div>
                <div class="form-group">
                    
                    <label for="authorLastNameInput">Author Last Name:</label>
                    <input type="text" class="form-control" id="authorLastNameInput" name="author_last_name" placeholder="Enter last name">
                </div>
                <div class="form-group">
                   
                    <label for="genreInput">Genre:</label>
                    <input type="text" class="form-control" id="genreInput" name="genre" placeholder="Enter genre">
                </div>
                <div class="form-group">
                    
                    <label for="priceRangeInput">Price Range:</label>
                    <input type="text" class="form-control" id="priceRangeInput" name="price_min" placeholder="Min Price">
                    <input type="text" class="form-control" id="priceRangeInput" name="price_max" placeholder="Max Price">
                </div>
                
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        
        <div class="col-md-8">
            <h2>Search Results</h2>
            <div class="list-group">
                <?php if (!empty($search_results)): ?>
                    <?php foreach ($search_results as $book): ?>
                                <div class="card mb-3">
                                    <div class="row no-gutters">
                                        <div class="col-md-4">
                                            <img src="<?= $book['book_image'] ?? ''; ?>" class="card-img" alt="<?= $book['book_name'] ?? ''; ?>">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $book['book_name'] ?? ''; ?></h5>
                                                <p class="card-text"><strong>Author:</strong> <?= $book['author_first_name'] ?? ''; ?> <?= $book['author_last_name'] ?? ''; ?></p>
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
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No results found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>