<div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-credit-card"></i> Payment</h4>
                    </div>
                    <div class="card-body">
                        <form id="payment_form" action="" method="post">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="card_number" id="card_number" placeholder="Enter Card Number">
                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cvv" placeholder="Enter CVV" name="cvv">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="first_name" placeholder="Enter First Name"  name="first_name">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="last_name" placeholder="Enter Last Name" name="last_name">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="pay_button"><i class="fas fa-money-check-alt"></i> Pay Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#pay_button").click(function(e) {
                e.preventDefault(); 
                

                var formData = $("#payment_form").serialize();


                $.post("/hws/my_prj/public_html/cart/pay", formData, function(data) {
                    console.log(data);
                    var otp = prompt("Enter OTP:");
                    if (otp == data) {
                        window.location.href = "/hws/my_prj/public_html/order/promocode";
                    } else {
                        alert("Invalid OTP.");
                    }
                });
                
            });
        });
    </script>