<?php 
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
?>

<nav class="navbar navbar-expand-lg navbar-dark dark-brown">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="https://static.vecteezy.com/system/resources/thumbnails/006/115/725/small/black-and-white-open-book-logo-illustration-on-white-background-free-vector.jpg" alt="Book Shop Logo" height="90" width="90">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/hws/my_prj/public_html/home">Home <span
                            class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/hws/my_prj/public_html/home/about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/hws/my_prj/public_html/home/contacts">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/hws/my_prj/public_html/book/catalog">Catalog</a>
                </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                    <a class="nav-link" href="/hws/my_prj/public_html/cart/edit_cart">Cart</a>
                    </li>

                    <li class="nav-item">
                    <a class="nav-link" href="/hws/my_prj/public_html/order/all">Orders</a>
                    </li>

                    <?php if($_SESSION['is_admin']): ?>
                        <li class="nav-item">
                        <a class="nav-link" href="/hws/my_prj/public_html/order/admin_change">Admin Panel</a>
                        </li>
                    <?php endif; ?>
                    
                <?php endif; ?>


            </ul>
            <form class="form-inline my-2 my-lg-0" action="http://localhost/hws/my_prj/public_html/book/search" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="searchInput">
                <button class="btn btn-outline-success my-2 my-sm-0" id="searchButton">Search</button>
            </form>
            <div class="ml-lg-2">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/hws/my_prj/public_html/user/sign_out" class="btn btn-outline-light mr-2">Logout</a>
                    <a href="/hws/my_prj/public_html/user/profile" class="btn btn-outline-light">Profile</a>
                <?php else: ?>
                    <a href="/hws/my_prj/public_html/user/register" class="btn btn-outline-light mr-2">Register</a>
                    <a href="/hws/my_prj/public_html/user/login" class="btn btn-outline-light">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
    function handleSearch(event) {
        event.preventDefault(); 
        
        var searchInput = document.getElementById('searchInput').value;
        var searchUrl = '/hws/my_prj/public_html/book/search?find=' + encodeURIComponent(searchInput);
        window.location.href = searchUrl;
    }

    document.getElementById('searchButton').addEventListener('click', handleSearch);
</script>