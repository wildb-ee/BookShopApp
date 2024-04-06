<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-user"></i> <strong>Username:</strong> <?= $username; ?></p>
                    <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?= $email; ?></p>
                    <p><i class="fas fa-user"></i> <strong>First Name:</strong> <?= $first_name; ?></p>
                    <p><i class="fas fa-user"></i> <strong>Last Name:</strong> <?= $last_name; ?></p>
                    <p><i class="fas fa-phone"></i> <strong>Phone Number:</strong> <?= $phone; ?></p>



                    <?php if (!empty($data['addresses'])): ?>
                        <ul class="address-list">
                            <?php foreach ($data['addresses'] as $address): ?>
                                <li>
                                    <i class="fas fa-map-marker-alt"></i> <strong>Street:</strong> <?= htmlspecialchars($address['street']); ?><br>
                                    <i class="fas fa-city"></i> <strong>City:</strong> <?= htmlspecialchars($address['city']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No addresses found.</p>
                    <?php endif; ?>

                    <a href="/hws/my_prj/public_html/user/edit_profile" class="btn" style="background-color: #5A2E00; color: white;"><i class="fas fa-edit"></i> Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>