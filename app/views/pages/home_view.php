<?php 
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
?>


<div class="container mt-5">

        <div class="container mt-5">
        <h2 class="text-center mb-6 special-offer-title">Special Offers</h2>
        <div class="row">

            <?php
            if (!empty($data)) {
                foreach ($data as $book) {
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?= $book['book_image'] ?? 'https://via.placeholder.com/300'; ?>" class="card-img-top" alt="<?= $book['book_name'] ?? 'Book Image'; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $book['book_name'] ?? 'Book Title'; ?></h5>
                                <p class="card-text"><?= $book['book_description'] ?? 'Book Description'; ?></p>
                                <p class="card-text"><?= $book['book_type'] ?? 'Book Description'; ?></p>

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
                    <?php
                }
            } else {

                echo "<p>No special offers available.</p>";
            }
            ?>


        </div>
        </div>




        <div class="container mt-5">
        <h2 class="sponsors-title">Sponsors</h2>

        <div class="row">
            <ul class="sponsor-list">
            <li class="col-md-3">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#sponsor1" aria-expanded="false" aria-controls="sponsor1">
                <img src="https://www.uctoday.com/wp-content/uploads/2021/12/What-is-Meta.jpg" class="d-block w-100" alt="Book 1">
                </button>
                <div class="collapse collapse-content" id="sponsor1">
                <p>Contribution 1</p>
                <p>Contribution 2</p>
                <p>Contribution 3</p>
                </div>
            </li>
            <li class="col-md-3">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#sponsor2" aria-expanded="false" aria-controls="sponsor2">
                <img src="https://img-prod-cms-rt-microsoft-com.akamaized.net/cms/api/am/imageFileData/RWCZER?ver=1433&q=90&m=6&h=195&w=348&b=%23FFFFFFFF&l=f&o=t&aim=true" class="d-block w-100" alt="Book 1">
                </button>
                <div class="collapse collapse-content" id="sponsor2">
                <p>Contribution 1</p>
                <p>Contribution 2</p>
                <p>Contribution 3</p>
                </div>
            </li>

            </ul>
        </div>
        </div>


        <section class="mb-5">
            <h2 class="featured-books-title">Featured Books</h2>
            <div id="featuredBooksCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <img src="https://s26162.pcdn.co/wp-content/uploads/2019/07/books.jpg" class="d-block w-100" alt="Book 1">
                        <div class="carousel-caption">
                            <h5>Book Title 1</h5>
                            <p>Book Description 1</p>
                            <a href="#" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://s26162.pcdn.co/wp-content/uploads/2019/07/books.jpg" class="d-block w-100" alt="Book 2">
                        <div class="carousel-caption">
                            <h5>Book Title 2</h5>
                            <p>Book Description 2</p>
                            <a href="#" class="btn btn-primary">View Details</a>
                        </div>
                    </div>

                </div>


                <a class="carousel-control-prev" href="#featuredBooksCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#featuredBooksCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    </div>