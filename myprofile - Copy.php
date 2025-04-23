<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch user profile
$sql = "SELECT username, email, age, address, gender, phone FROM account_registration WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $age, $address, $gender, $phone);
$stmt->fetch();
$stmt->close();

// Fetch user orders
$order_sql = "SELECT name, phone, order_date, product_type, quantity FROM customer_orders WHERE customer_id = ? ORDER BY order_date DESC";
$order_stmt = $conn->prepare($sql);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Profile</title>
  <link rel="stylesheet" href="footer.css" />
  <link rel="stylesheet" href="profile.css" />
  <link rel="stylesheet" href="nav.css" />
  <style>
    body {
        background-color: #f4f4f4;
    }
    .container {
      max-width: 900px;
      margin: 50px auto;
      padding: 20px;
    }
    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }
    .profile-pic {
      text-align: center;
      margin-bottom: 20px;
    }
    .profile-pic img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
    }
    .edit-icon img {
      width: 20px;
      height: 20px;
      margin-left: 10px;
    }
    .actions a {
      display: inline-block;
      margin-right: 15px;
      padding: 10px 15px;
      background-color: #007BFF;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
    }
    .order-history {
      margin-top: 30px;
    }
    .order-history h3 {
      margin-bottom: 15px;
    }
    .order-table {
      width: 100%;
      border-collapse: collapse;
    }
    .order-table th, .order-table td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: left;
    }
    .order-table th {
      background-color: #f2f2f2;
    }
  </style>


<header>
  <nav>
  <a href="home.php">Home</a>
  <a href="about.html">About</a>
   <a href="service.html">Services</a>
    <a href="FAQ.html">FAQ</a>
    <a href="testimonial.html">Testimonials</a>
    <a href="gallery.html">Gallery</a>
    <a href="order.html">Order</a>
    <a href="cart.html">Cart</a>
    <a href="contact.html">Contact us</a>
    <a href="myprofile.php" class="active">Profile</a>
    <a href="search.php">Search</a>
  </nav>
</header>
</head>
<body>


<div class="container">
  <h2>My Profile</h2>

  <div class="profile-pic">
    <img src="<?php echo file_exists("uploads/{$user_id}.jpg") ? "uploads/{$user_id}.jpg" : "default_avatar.png"; ?>" alt="Profile Picture">
    <a href="upload-profile-pic.php" class="edit-icon"><img src="edit_icon.png" alt="Edit"></a>
  </div>

  <div class="profile-grid">
    <p><strong>Username:</strong> <?php echo htmlspecialchars($username ?? 'Not set'); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email ?? 'Not set'); ?></p>
    <p><strong>Age:</strong> <?php echo htmlspecialchars($age ?? 'Not set'); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($address ?? 'Not set'); ?></p>
    <p><strong>Gender:</strong> <?php echo htmlspecialchars($gender ?? 'Not set'); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone ?? 'Not set'); ?></p>
  </div>

  <div class="actions">
    <a href="edit-profile.php">Edit Profile</a>
    <a href="logout.php" class="logout">Logout</a>
  </div>

  <div class="order-history">
    <h3>My Orders</h3>
    <?php if ($order_result->num_rows > 0): ?>
      <table class="order-table">
        <thead>
          <tr>
            <th>Name(s)</th>
            <th>Phone</th>
            <th>Order Date</th>
            <th>Product Type</th>
            <th>Quantity</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $order_result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo htmlspecialchars($row['phone']); ?></td>
              <td><?php echo htmlspecialchars($row['order_date']); ?></td>
              <td><?php echo htmlspecialchars($row['product_type']); ?></td>
              <td><?php echo htmlspecialchars($row['quantity']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>You haven't placed any orders yet.</p>
    <?php endif; ?>
  </div>
</div>

<div class="footer">
  <div class="social-icons">
    <a href="#"><img src="facebook.png" alt="Facebook"></a>
    <a href="#"><img src="instagram.png" alt="Instagram"></a>
    <a href="https://wa.me/c/26773684840"><img src="whatsapp.png" alt="Whatsapp"></a>
    <a href="#"><img src="tiktok.png" alt="Tiktok"></a>
  </div>
</div>

<script>
  // Store profile info into localStorage for use in cart or other pages
  localStorage.setItem('customerData', JSON.stringify({
    name: <?php echo json_encode($username); ?>,
    email: <?php echo json_encode($email); ?>,
    phone: <?php echo json_encode($phone); ?>
  }));
</script>

</body>
</html>
