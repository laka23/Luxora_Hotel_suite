<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "hotel_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>
        alert('Please log in first.');
        window.location.href = 'loging.html';
    </script>";
    exit;
}

// Get logged-in user's email from session
$email = $_SESSION['email'];

// Get user data using prepared statement
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found");
}

// Get notification count for the badge
$notification_query = "SELECT COUNT(*) as unread_count FROM notifications 
                      WHERE user_id = ? AND is_read = 0";
$stmt = $conn->prepare($notification_query);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$notification_result = $stmt->get_result();
$unread_count = $notification_result->fetch_assoc()['unread_count'];
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Profile | Harborlights</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" type="text/css" href="Css/profile.css">
  
</head>
<body class="bg-light" style="font-family: 'Poppins', sans-serif;">
    <!-- Header -->
    <header class="header" id="header" style="background: linear-gradient(135deg, #0c4b33, #1a6b4a);">
        <div class="container header-container">
            <a href="index.html" class="logo" style="color: #fff; font-size: 28px; font-weight: 700;">Harbor<span style="color: #d4af37;">lights</span></a>
            <button class="nav-toggle" id="navToggle" style="color: #fff;">
                <i class="fas fa-bars"></i>
            </button>
            <nav class="nav" id="nav">
                <ul class="nav-menu" id="navMenu" style="list-style: none; display: flex; gap: 25px;">
                    <li class="nav-item"><a href="index.html#home" class="nav-link" style="color: #fff; font-weight: 500;">Home</a></li>
                    <li class="nav-item"><a href="index.html#rooms" class="nav-link" style="color: #fff; font-weight: 500;">Rooms</a></li>
                    <li class="nav-item"><a href="index.html#services" class="nav-link" style="color: #fff; font-weight: 500;">Services</a></li>
                    <li class="nav-item"><a href="index.html#about" class="nav-link" style="color: #fff; font-weight: 500;">About</a></li>
                    <li class="nav-item"><a href="index.html#contact" class="nav-link" style="color: #fff; font-weight: 500;">Contact</a></li>
                </ul>
            </nav>
    </header>

    <!-- Main Profile Content -->
    <div class="container mt-5" style="padding-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0" style="color: #0c4b33; font-weight: 700;">My Profile</h2>
                            <a href="logout.php" class="btn btn-link text-decoration-none" style="color: #d4af37;">
                                <i class="fas fa-sign-out-alt me-1"></i> LogOut
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="position-relative mb-4">
                                    <?php if (!empty($user['profile_picture'])) { ?>
                                        <img src="<?= htmlspecialchars($user['profile_picture']) ?>" class="rounded-circle shadow" width="180" height="180" style="object-fit: cover; border: 5px solid #f8f9fa;">
                                    <?php } else { ?>
                                        <img src="assets/images/default-profile.jpg" class="rounded-circle shadow" width="180" height="180" style="object-fit: cover; border: 5px solid #f8f9fa;">
                                    <?php } ?>
                                    <label for="profileUpload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" style="width: 40px; height: 40px; cursor: pointer;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                </div>
                                <h4 class="mb-1"><?= htmlspecialchars ($user['first_name']) . ' ' . htmlspecialchars($user['last_name']) ?></h4>
                                <p class="text-muted mb-3">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                                
                                <div class="d-flex justify-content-center gap-2 mb-4">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill" href="index.html#contact">
                                        <i class="fas fa-envelope me-1"></i><a href="index.html#contact"> Message 
                                    </button></a>
                                   
                                </div>
                                
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body p-3">
                                        <h6 class="mb-3" style="color: #0c4b33;">Account Status</h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="flex-shrink-0 bg-success rounded-circle p-1 me-2">
                                                <i class="fas fa-check text-white" style="font-size: 0.7rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <small class="text-muted">Verified Account</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 bg-success rounded-circle p-1 me-2">
                                                <i class="fas fa-check text-white" style="font-size: 0.7rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <small class="text-muted">Active Member</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" id="profileUpload" name="profile_picture" class="d-none" accept="image/*">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    
                                    <div class="mb-4">
                                        <h5 class="mb-3" style="color: #0c4b33; border-bottom: 1px solid #eee; padding-bottom: 8px;">Personal Information</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">First Name</label>
                                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px 15px;">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px 15px;">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly style="border-radius: 8px; border: 1px solid #ddd; padding: 10px 15px; background-color: #f8f9fa;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5 class="mb-3" style="color: #0c4b33; border-bottom: 1px solid #eee; padding-bottom: 8px;">Contact Details</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Phone</label>
                                                <input type="text" name="Phone" class="form-control" value="<?= htmlspecialchars($user['Phone'] ?? '') ?>" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px 15px;">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address'] ?? '') ?>" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px 15px;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5 class="mb-3" style="color: #0c4b33; border-bottom: 1px solid #eee; padding-bottom: 8px;">Security</h5>
                                        <div class="alert alert-warning" style="border-radius: 8px;">
                                            <i class="fas fa-exclamation-circle me-2"></i> For security reasons, you can only change your password in the security settings.
                                        </div>
                                        <a href="New_Password.html" class="btn btn-outline-secondary" style="border-radius: 8px;">
                                            <i class="fas fa-lock me-2"></i> Change Password
                                        </a>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <button href="" type="button" class="btn btn-outline-danger" style="border-radius: 8px;"><a href="delete.php">
                                            <i class="fas fa-trash-alt me-2"></i> Delete Account
                                        </button></a>
                                        <button type="submit" class="btn btn-primary" style="background: #d4af37; border: none; border-radius: 8px; padding: 10px 25px;">
                                            <i class="fas fa-save me-2"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5" style="background: #0c4b33; color: #fff; padding: 60px 0 30px;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h3 style="font-size: 24px; margin-bottom: 20px;">Harbor<span style="color: #d4af37;">lights</span></h3>
                    <p style="opacity: 0.8; margin-bottom: 20px;">Where elegance meets comfort. Your luxurious stay awaits by the harbor.</p>
                    <div class="social-links d-flex gap-3">
                        <a href="#" style="color: #fff; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: #fff; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: #fff; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h4 style="font-size: 18px; margin-bottom: 20px; position: relative; padding-bottom: 10px;">Quick Links</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 10px;"><a href="index.html#rooms" style="color: #fff; opacity: 0.8; text-decoration: none; transition: all 0.3s;">Rooms</a></li>
                        <li style="margin-bottom: 10px;"><a href="index.html#services" style="color: #fff; opacity: 0.8; text-decoration: none; transition: all 0.3s;">Services</a></li>
                        <li style="margin-bottom: 10px;"><a href="index.html#about" style="color: #fff; opacity: 0.8; text-decoration: none; transition: all 0.3s;">About Us</a></li>
                        <li style="margin-bottom: 10px;"><a href="index.html#contact" style="color: #fff; opacity: 0.8; text-decoration: none; transition: all 0.3s;">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h4 style="font-size: 18px; margin-bottom: 20px; position: relative; padding-bottom: 10px;">Contact Info</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 15px; display: flex; align-items: flex-start;">
                            <i class="fas fa-map-marker-alt me-3" style="margin-top: 4px; color: #d4af37;"></i>
                            <span style="opacity: 0.8;">123 Harbor Street, Seaview City</span>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; align-items: flex-start;">
                            <i class="fas fa-phone me-3" style="margin-top: 4px; color: #d4af37;"></i>
                            <span style="opacity: 0.8;">+1 (555) 123-4567</span>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; align-items: flex-start;">
                            <i class="fas fa-envelope me-3" style="margin-top: 4px; color: #d4af37;"></i>
                            <span style="opacity: 0.8;">info@harborlights.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-5 pt-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="opacity: 0.7; margin-bottom: 0;">&copy; 2025 Harborlights Hotel & Resort. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('show');
        });

        // Profile picture upload preview
        const profileUpload = document.getElementById('profileUpload');
        const profileImg = document.querySelector('.rounded-circle');
        
        profileUpload.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    profileImg.src = event.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>