<form id="edit_profile_form" class="mt-4 mb-4 mr-5 ml-5" method="post" action="edit_profile">
    <div class="form-group">
        <label for="first_name"><i class="fas fa-user"></i> First Name:</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($data['first_name']); ?>">
    </div>

    <div class="form-group">
        <label for="last_name"><i class="fas fa-user"></i> Last Name:</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($data['last_name']); ?>">
    </div>

    <div class="form-group">
        <label for="phone"><i class="fas fa-phone"></i> Phone:</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>">
    </div>

    <div class="form-group">
        <label for="username"><i class="fas fa-user"></i> Username:</label>
        <input type="text" class="form-control" id="username" name="username" readonly value="<?php echo htmlspecialchars($data['username']); ?>">
    </div>

    <div class="form-group">
        <label for="email"><i class="fas fa-envelope"></i> Email:</label>
        <input type="email" class="form-control" id="email" name="email" readonly value="<?php echo htmlspecialchars($data['email']); ?>">
    </div>

    <div class="form-group">
        <label for="password"><i class="fas fa-password"></i> Password:</label>
        <input type="text" class="form-control" id="password" name="password">
    </div>

    <?php foreach ($data['addresses'] as $index => $address): ?>
        <div class="address-block">
            <input type="hidden" name="address_id[]" value="<?php echo $address['address_id']; ?>">
            <div class="form-group">
                <label for="street<?php echo $index; ?>"><i class="fas fa-map-marker-alt"></i> Street:</label>
                <input type="text" class="form-control" id="street<?php echo $index; ?>" name="street[]" value="<?php echo htmlspecialchars($address['street']); ?>">
            </div>

            <div class="form-group">
                <label for="city<?php echo $index; ?>"><i class="fas fa-city"></i> City:</label>
                <input type="text" class="form-control" id="city<?php echo $index; ?>" name="city[]" value="<?php echo htmlspecialchars($address['city']); ?>">
            </div>
        </div>
    <?php endforeach; ?>

    <button type="button" id="add_address" class="btn btn-primary"><i class="fas fa-plus"></i> Add Address</button><br><br>

    <input type="submit" class="btn btn-success" value="Update Profile">
</form>

<script>
    document.getElementById("add_address").addEventListener("click", function() {
        var form = document.getElementById("edit_profile_form"); // Target the form directly
        var addressFields = document.createElement("div");
        addressFields.classList.add("address-block");

        addressFields.innerHTML = `
            <div class="form-group">
                <label for="street"><i class="fas fa-map-marker-alt"></i> Street:</label>
                <input type="text" class="form-control" id="street" name="street[]">
            </div>

            <div class="form-group">
                <label for="city"><i class="fas fa-city"></i> City:</label>
                <input type="text" class="form-control" id="city" name="city[]">
            </div>
        `;

        form.insertBefore(addressFields, this); 
    });

    document.getElementById("edit_profile_form").addEventListener("submit", function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", this.action, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        alert("Operation completed successfully.");
                        window.location.href = "profile";
                    } else {
                        alert("Error occurred during the operation.");
                        window.location.href = "profile";
                    }
                } else {
                    alert("Error: " + xhr.status);
                }
            }
        };

        xhr.send(formData);
    });
</script>
