<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "weather1";

$conn = mysqli_connect($servername, $username, $password, $database);
if ($conn) {
    //  echo "Connect successfully";
} else {
    echo "Failed to connect";
}

if (isset($_GET['cityname'])) {
    $city = $_GET['cityname'];
} else {
    $city = "Madison";
}

$selectAllData = "SELECT * FROM weatherData WHERE city='$city'";
$result = mysqli_query($conn, $selectAllData);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    $json_data = json_encode($rows); //Converst associative array to json format
    echo $json_data;
    header('Content-Type: application/json');
} else {
    $errorResponse = ['error' => true, 'message' => 'City Not found'];
    $json_data = json_encode($errorResponse);
    echo $json_data;
    header('Content-Type: application/json');
}

?>