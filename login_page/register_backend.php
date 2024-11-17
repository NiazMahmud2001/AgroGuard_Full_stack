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
        $userName = $_POST["user_name_reg"];
        $userPass = $_POST["user_pass_reg"];
        $droneID = $_POST["drone_id_reg"];

        $droneIDArr = explode('-', $droneID); 
        $droneMainID = $droneIDArr[0];
        


        // validation state: =========================================
        //[$cusName, $deliveryAddress, $cusEID, $droneModel]
        $redpArr = [false , false , false]; 
        # true =>fine || false=>have error

        if(strlen($userName)<=20){
            $redpArr[0] = true;
        }
        if(strlen($userPass)<=10 and strlen($userPass)>4){
            $redpArr[1] = true;
        };
        if(strlen($droneID)<= 20){
            $redpArr[2] = true;
        };

        if($redpArr[0] == false or $redpArr[1] == false or $redpArr[2] == false){
            if ($redpArr[0] == false){
                echo "<p>Please Enter userName length less than 20 and greater than 4 character!!!</p>";
            };
            if ($redpArr[1] == false){
                echo "<p>Please Enter user password less than 10 and greater than 4 character!!!</p>";
            };
            if ($redpArr[2] == false){
                //drone main id(droneid1,2,3,4....)-dub id(1,2,3,4...)
                echo "<p>Please Enter your Drone id of 20 Character with ' - '!!!</p>";
            };
        }else{
            $sql_droneid = "SELECT id from droneids WHERE `id`='$droneMainID';";
            $result_droneID  = $Py_server -> query($sql_droneid);
            if ($result_droneID == TRUE){
                if ($result_droneID->num_rows > 0) {
                    while ($row = $result_droneID->fetch_assoc()) {
                        $gets_ids = $row['id'];
                        if($gets_ids==$droneMainID){
                            $sql_insert = "INSERT INTO userreg (`userName`, `userPass`, `droneId`) VALUES ('$userName', '$userPass', '$droneID');";
                            $result_insert = $Py_server -> query($sql_insert);
        
                            if($result_insert == TRUE) {
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    header("Location: ../index.php");
                                    exit();
                                };
                            };
                        }else{
                            echo "<p>Please Enter a valid Drone ID</p>";
                        };
                    };
                }else{
                    echo "<p>Please Enter a valid Drone ID</p>";
                }
            }else{
                echo "<p>You have Entered an invalid Drone ID</p>";
            };
        };
        mysqli_close($Py_server)
    ?>

</body>
</html>






































































