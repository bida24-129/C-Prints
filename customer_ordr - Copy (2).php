<?php
// db connection
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect data from form (or API)
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$quantity = (int)$_POST['quantity'];

// Check if user exists
$sql = "SELECT * FROM customer_orders WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Customer exists, update quantity
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + $quantity;

    $update = $conn->prepare("UPDATE customer_orders SET quantity = ? WHERE name = ?");
    $update->bind_param("is", $newQuantity, $name);
    $update->execute();
    echo "Quantity updated for existing customer.";
} else {
    // New customer, insert data
    $insert = $conn->prepare("INSERT INTO customer_orders (name, email, phone, quantity) VALUES (?, ?, ?, ?)");
    $insert->bind_param("sssi", $name, $email, $phone, $quantity);
    $insert->execute();
    echo "New customer order recorded.";
}

$conn->close();
?>
