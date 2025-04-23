<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $quantity = $_POST['quantity'];

    $conn = new mysqli('localhost', 'root', '', 'test');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user already exists
    $sql = "SELECT id, quantity FROM orders WHERE name = ? AND email = ? AND phone = ? AND address = ?  ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $email, $phone, $address, $quantity);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $existing_quantity);

    if ($stmt->num_rows > 0) {
        // User exists, update the quantity
        $stmt->fetch();
        $new_quantity = $existing_quantity + $quantity;
        $update_sql = "UPDATE orders SET quantity = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_quantity, $id);
        if ($update_stmt->execute()) {
            echo "Order updated successfully!";
        } else {
            echo "Error: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        // User does not exist, insert new order
        $insert_sql = "INSERT INTO orders (name, email, phone, address, quantity) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssssiiss", $name, $email, $phone, $address, $quantity, );
        if ($insert_stmt->execute()) {
            echo "Order placed successfully!";
        } else {
            echo "Error: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }

    $stmt->close();
    $conn->close();
}
?>