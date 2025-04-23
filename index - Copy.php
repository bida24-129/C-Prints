<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
   <style>
    body {
    background-image: url('https://cdn.pixabay.com/photo/2018/01/05/18/05/bench-3063558_1280.jpg'); /* Background image */
    background-size: cover;
    background-position: center;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    flex-direction: row; /* Align items in a row */
}
.left-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    width: 35%; /* Left side takes up 35% of the width */
    margin-right: 10%; /* Space between the left and right columns */
}
.envelope {
    width: 60%;
    background-color: #fff;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    padding: 30px;
    text-align: center;
    border-radius: 15px;
    position: relative;
    cursor: pointer;
    transition: transform 0.5s ease-in-out, box-shadow 0.5s ease-in-out;
}
.envelope::before {
    content: "✉️";
    font-size: 70px;
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
}
.envelope .message {
    display: none;
    font-size: 18px;
    color: #333;
    margin-top: 20px;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}
.envelope.open .message {
    display: block;
    opacity: 1;
}
.notification-bubble {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: #ff6347;
    color: white;
    font-size: 22px;
    padding: 5px 10px;
    border-radius: 50%;
}
.notification-text {
    font-size: 14px;
    color: #333;
    margin-top: 10px;
}
.store-button {
    padding: 12px 20px;
    background-color: #ff6347;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 30%; /* Button width reduced to 20% */
    text-align: center;
    transition: background-color 0.3s;
    margin-top: 20px;
}
.store-button:hover {
    background-color: #333;
}
.right-column {
    width: 50%; /* Right side takes up 50% of the width */
}
.login-form {
    width: 70%;
    padding: 30px;
    background-color: rgba(255, 255, 255, 0.8); /* Light background for contrast */
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.login-form h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333; /* Darker text color */
}
.login-form input {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: none;
    background-color: transparent; /* Transparent background for inputs */
    border-bottom: 2px solid #ff6347; /* Bottom border for input fields */
    border-radius: 0;
    box-sizing: border-box;
    font-size: 16px;
    color: #333; /* Text color */
}
.login-form input[type="submit"] {
    background-color: #ff6347;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
    width: 100%;
    padding: 12px;
}
.login-form input[type="submit"]:hover {
    background-color: #333;
}
.login-form a {
    text-decoration: none;
    color: #ff6347;
    font-size: 14px;
}
.message {
    margin-top: 20px;
}
.message p {
    font-size: 12px;
}
   </style>
</head>
<body>

<div class="left-column">
    <div class="envelope" onclick="toggleMessage()">
        <div class="notification-bubble" id="notificationBubble">1</div> <!-- Notification bubble -->
        <div class="message" id="dailyMessage">
            <!-- Message will be inserted by JavaScript -->
        </div>
    </div>

    <!-- Text below envelope to prompt user -->
    <div class="notification-text" id="notificationText">
        Press on the envelope to view your notification.
    </div>

    <!-- View Our Store Button below envelope -->
    <div class="store-button" onclick="window.location.href='store.html';">
        View Our Store
    </div>
</div>

<div class="right-column">
    <div class="login-form">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Enter username" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" value="Login">
            <a href="forgot-password.php">Forgot password?</a>
        </form>
        <div class="message">
            <p>Don't have an account? <a href="create.php">Create one</a></p>
        </div>
    </div>
</div>

<script>
    // Array of daily messages
    const dailyMessages = [
        "Welcome to Style Fix! Start your day with a new outfit!",
        "Inspiration for today: 'Your clothing should be a reflection of your spirit.'",
        "Don't forget to check out our new arrivals and exclusive offers!",
        "Stay blessed and stylish. Every day is a good day to wear faith!",
        "Thank you for being part of the Style Fix family. Stay stylish!"
    ];

    // Function to determine today's message based on the date
    function getDailyMessage() {
        const today = new Date();
        const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / 86400000); // Get the day of the year
        const messageIndex = dayOfYear % dailyMessages.length; // Cycle through the messages array
        return dailyMessages[messageIndex];
    }

    // Set the message in the envelope
    document.getElementById("dailyMessage").innerText = getDailyMessage();

    // Toggle the envelope animation and reveal the message
    function toggleMessage() {
        const envelope = document.querySelector(".envelope");
        const messageElement = document.getElementById("dailyMessage");
        const notificationBubble = document.getElementById("notificationBubble");
        const notificationText = document.getElementById("notificationText");

        envelope.classList.toggle("open");  // Toggle the envelope open/close animation

        if (envelope.classList.contains("open")) {
            messageElement.style.display = "block";  // Show the message
            notificationBubble.style.display = "none";  // Hide notification bubble after opening
            notificationText.style.display = "none";  // Hide the prompt text
        } else {
            messageElement.style.display = "none";  // Hide the message
            notificationBubble.style.display = "block";  // Show the notification bubble if message is hidden
            notificationText.style.display = "block";  // Show the prompt text
        }
    }
</script>

</body>
</html>
