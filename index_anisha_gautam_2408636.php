<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "weather1";

    $conn = mysqli_connect($servername,$username,$password,$database);
    if ($conn){
       
    }else{
        echo "Failed to connect".mysqli_connect_error();
    }
    
    if(isset($_GET['cityname'])){
        $cityName = $_GET['cityname'];
        //echo $cityName;
    }else{
        $cityName = "Madison";
    }

    $apiEndpoint = 'https://api.openweathermap.org/data/2.5/weather?units=metric&appid=72ca5d48b415850897dbcc076e3e7af2&q=';
    $city = urlencode($cityName);

    // Make the API request
    $apiUrl = $apiEndpoint . $city;
    $apiData = file_get_contents($apiUrl);
    //echo $apiData;
    if($data['cod']===404){
        header('location:notfoundpage_anisha_gautam_2408636.php');
    }else{
        $data = json_decode($apiData, true);
        //echo $data;
    
        // Check if JSON decoding was successful
        if ($data === NULL) {
            // Handle error, e.g., return an error response
            return ['status' => 'error', 'message' => 'Failed to decode API data'];
        }
        
        $city = $data['name'];
        $temperature = $data['main']['temp'];
        $pressure = $data['main']['pressure'];
        $humidity = $data['main']['humidity'];
        $weather_description = $data['weather'][0]['description'];
        $wind_speed = $data['wind']['speed'];
        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y-m-d H:i:s");
        $currentDayOfWeek = date("l");
    
    
        $existingData = "SELECT * FROM weatherData WHERE City='$city' AND Day_Of_Week='$currentDayOfWeek'";
        $result = mysqli_query($conn, $existingData);
    
        if (mysqli_num_rows($result) > 0) {
        // Data for the same city and day of the week already exists, perform an UPDATE
        $updateData = "UPDATE weatherData SET 
        Temperature=$temperature, 
        Pressure=$pressure, 
        Humidity=$humidity, 
        Weather_Description='$weather_description', 
        Wind_Speed=$wind_speed,
        Date_='$currentDate'
        WHERE City='$city' AND Day_Of_Week='$currentDayOfWeek'";
    
    
        if (mysqli_query($conn, $updateData)) {
            // echo "Data updated for $city on $currentDayOfWeek";
        } else {
            echo "Failed to update data: " . mysqli_error($conn);
        }
    } else {
        // Data doesn't exist, perform an INSERT
        $insertData = "INSERT INTO weatherData (City, Temperature, Pressure, Humidity, Weather_Description, Wind_Speed, Date_, Day_Of_Week)
                       VALUES ('$city', $temperature, $pressure, $humidity, '$weather_description', $wind_speed, '$currentDate', '$currentDayOfWeek')";
    
        if (mysqli_query($conn, $insertData)) {
            echo "Data inserted for $city on $currentDayOfWeek";
        } else {
            echo "Failed to insert data: " . mysqli_error($conn);
        }
    }
        
    }
   
mysqli_close($conn);
?>