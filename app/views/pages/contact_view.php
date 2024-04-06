<div class="container mt-5 contact-container">
    <div class="contact-info">
        <h3>Where to Find Us:</h3>
        <p>Address: Your Store Address</p>
        <p>Phone: +1234567890</p>
        <p>Email: info@example.com</p>
        <p>Website: www.example.com</p>
    </div>

    <h2 class="text-center mb-4">Contact Us</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form class="contact-form" method="POST" action="/hws/my_prj/public_html/user/save_message">
            <div class="form-group">
                <input type="text" class="form-control" name="theme" placeholder="Theme">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="message" rows="5" placeholder="Your Message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    <?php else: ?>
        <form class="contact-form" action="/hws/my_prj/public_html/user/save_message" method="POST">
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Your Email">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="first_name" placeholder="First Name">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                </div>
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" name="phone" placeholder="Your Phone">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="theme" placeholder="Theme">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="message" rows="5" placeholder="Your Message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    <?php endif; ?>
</div>