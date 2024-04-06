<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #5A2E00; color: white;">
                    <h4><i class="fas fa-user-plus"></i> Registration</h4>
                </div>
                <div class="card-body">
                    <form id="registration-form" action="register" method="post">
                        <div class="form-group">
                            <label for="login">Login</label>
                            <input type="text" class="form-control" id="login" placeholder="Enter login" name="username">
                            <small class="form-text text-danger" id="login-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                            <small class="form-text text-danger" id="email-error"></small>
                        </div>

                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" placeholder="Enter first name" name="first_name">
                            <small class="form-text text-danger" id="firstName-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Enter last name" name="last_name">
                            <small class="form-text text-danger" id="lastName-error"></small>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" placeholder="Enter phone number" name="phone">
                            <small class="form-text text-danger" id="phone-error"></small>
                        </div>


                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                            <small class="form-text text-danger" id="password-error"></small>
                        </div>

                        <div class="form-group">
                            <label for="isAdmin">Admin User</label><br>
                            <label class="switch">
                                <input type="checkbox" name="is_admin">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <button type="submit" class="btn" style="background-color: #5A2E00; color: white;"><i class="fas fa-user-plus"></i> Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("registration-form").addEventListener("submit", function(event) {
        resetFormValidation();

        var login = document.getElementById("login").value;
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var firstName = document.getElementById("firstName").value;
        var lastName = document.getElementById("lastName").value;
        var phone = document.getElementById("phone").value;

        if (!login) {
            document.getElementById("login-error").textContent = "Login is required";
            document.getElementById("login").classList.add("is-invalid");
            event.preventDefault(); 
            return;
        }

        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            document.getElementById("email-error").textContent = "Invalid email format";
            document.getElementById("email").classList.add("is-invalid");
            event.preventDefault(); 
            return;
        }

        if (!firstName) {
            document.getElementById("firstName-error").textContent = "First name is required";
            document.getElementById("firstName").classList.add("is-invalid");
            event.preventDefault(); 
            return;
        }

        if (!lastName) {
            document.getElementById("lastName-error").textContent = "Last name is required";
            document.getElementById("lastName").classList.add("is-invalid");
            event.preventDefault(); 
            return;
        }

        if (!phone) {
            document.getElementById("phone-error").textContent = "Phone number is required";
            document.getElementById("phone").classList.add("is-invalid");
            event.preventDefault(); 
            return;
        }


        if (!password) {
            document.getElementById("password-error").textContent = "Password is required";
            document.getElementById("password").classList.add("is-invalid");
            event.preventDefault(); 
            return;
        }


    });

    function resetFormValidation() {
        document.getElementById("login-error").textContent = "";
        document.getElementById("email-error").textContent = "";
        document.getElementById("password-error").textContent = "";
        document.getElementById("firstName-error").textContent = "";
        document.getElementById("lastName-error").textContent = "";
        document.getElementById("phone-error").textContent = "";
        document.getElementById("login").classList.remove("is-invalid");
        document.getElementById("email").classList.remove("is-invalid");
        document.getElementById("password").classList.remove("is-invalid");
        document.getElementById("firstName").classList.remove("is-invalid");
        document.getElementById("lastName").classList.remove("is-invalid");
        document.getElementById("phone").classList.remove("is-invalid");
    }
</script>
