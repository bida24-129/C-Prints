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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];

    $sql = "UPDATE account_registration SET username = ?, email = ?, age = ?, address = ?, gender = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissi", $username, $email, $age, $address, $gender, $phone, $user_id);

    if ($stmt->execute()) {
        header("Location: myprofile.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$sql = "SELECT username, email, age, address, gender, phone FROM account_registration WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $age, $address, $gender, $phone);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="edit-profile.php" method="post">
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            <input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" required>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            <select name="gender" required>
                <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
            </select>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            <input type="submit" value="Save Changes">
        </form>
    </div>
</body>
</html>