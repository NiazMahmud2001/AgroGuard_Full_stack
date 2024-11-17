<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php 
        // Database connection variables
        $servername = "localhost:8111"; // or "127.0.0.1: port number of mysql(not apache)"
        $username = "root";        
        $password = "";    
        $dbname = "agroguard";
        
        $Py_server = new mysqli($servername ,$username, $password ,$dbname);
        // Check connection
        if ($Py_server-> connect_errno) {
            echo "Failed to connect to MySQL: " . $Py_server-> connect_error;
            exit();
        }else{
            echo "<br> Successfully connected to the db <br><br>";
        }

        // get all the user info 
        $cusName = $_POST["user_name"];
        $cusEmail = $_POST["user_email"];
        $deliveryAddress = $_POST["address"];
        $cusEID = $_POST["uid"];
        $droneModel = $_POST["model"];
        $selectedCity = $_POST['subject'];

        $lat = $_POST['latitude'];
        $lon = $_POST['longitude'];

        $deliveryDateInt = strtotime("+4 day");
        $dateDeliveryStr = date('d/m/y', $deliveryDateInt);
        $agentId; # i will retrieve it from DB


        // validation state: =========================================
        //[$cusName, $deliveryAddress, $cusEID, $droneModel, $cusEmail]
        $redpArr = [false , false , false, false, false]; 
        # true =>fine || false=>have error

        if(strlen($cusName)<=50){
            $redpArr[0] = true;
        }
        if(strlen($deliveryAddress)<=100){
            $redpArr[1] = true;
        };
        function isValidEmiratesID($id) {
            $pattern = "/^784-\d{4}-\d{7}-\d$/";
            if (preg_match($pattern, $id)) {
                return true;
            } else {
                return false;
            }
        };
        function isValidEmail($email){
            $patternMail = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
            if (preg_match($patternMail, $email)) {
                return true;
            } else {
                return false;
            };
        };


        if (isValidEmiratesID($cusEID)) {
            $redpArr[2]=True;
        };
        if(strlen($droneModel)<= 10){
            $redpArr[3] = true;
        };

        if(isValidEmail($cusEmail)){
            $redpArr[4]=True;
        }

        # get the agentId using $selectedCity from database==========================
        $sql_get_agentID = "SELECT agentId from deliveryagent WHERE `agentArea`='$selectedCity';";
        $result_agentID  = $Py_server -> query($sql_get_agentID);
        
        $agentId;
        if ($result_agentID == TRUE) {
            echo "<br> data submitted in database successfully <br><br><br>"; 
            if ($result_agentID->num_rows > 0) {
                // Fetch each row
                while ($row = $result_agentID->fetch_assoc()) {
                    $agentId = $row['agentId'];
                };
            };
        } else {
            echo "Query failed: " . $Py_server->error;
        };
        echo "Agent ID: " . $agentId . "<br>";


        #Now insert the data to database: ========================================
        $sql_insert = "INSERT INTO preorder_info (`cusName`, `deliveryAddress`, `cusEID`, `deliveryLon` , `deliveryLat`, `desireModel`, `deliverystate`, `estDeliveryDate`, `agentID`) VALUES ('$cusName', '$deliveryAddress', '$cusEID', $lon, $lat,'$droneModel','$selectedCity', $deliveryDateInt, '$agentId');";
        $result_insert = $Py_server -> query($sql_insert);

        if ($result_insert == TRUE) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                //after processing data => redirect to "index.php"
                header("Location: ../index.php");
                exit();
            }
        } else {
            if ($redpArr[0] == false){
                echo "<p>Please Enter Customer Name length less than 50 character</p>";
            };
            if ($redpArr[1] == false){
                echo "<p>Please Enter delivery address length less than 100 character</p>";
            };
            if ($redpArr[2] == false){
                echo "<p>Please Enter the Emirates ID with '-'</p>";
            };
            if ($redpArr[3] == false){
                echo "<p>Please Enter Drone model less than 10 characte</p>";
            };
            if ($redpArr[4] == false){
                echo "<p>Please Enter a valid email adderss</p>";
            };
        };
            
    ?>

</body>
</html>






































































