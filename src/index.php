<?php
// $servername = "localhost";
// $username = "root";  //user name
// $password = "12345678";  //password used to login MySQL server
// $dbname = "PlantCareSystem";
// $port = 3000;

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname, $port);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully ";

// //Use prepared statement in SQL
// $stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
// $name = "mmm";
// $passwd = "kayak";
// $stmt->bind_param("ss", $name, $passwd); //ss lists types of parameters, they two strings
// $stmt->execute();
// echo "New records created successfully";

// $stmt->close();
// $conn->close();

// Redirect page to correct destination
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Login page redirect
    if (isset($_POST['index-btn-login'])) {
        header("Location: login.html");
        exit();

    // Plants page redirect
    } elseif (isset($_POST['index-btn-plants'])) {
        header("Location: plants.html");
        exit();

    // About page redirect
    } elseif (isset($_POST['index-btn-about'])) {
        header("Location: about.html");
        exit();
    
    // Home page redirect
    } elseif (isset($_POST['index-btn-home'])) {
        header("Location: index.html");
        exit();
    }
}
?>