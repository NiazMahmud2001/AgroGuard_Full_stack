<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php 
        session_start(); 
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
        $userName = trim($_POST["user_name_login"]);
        $userPass = trim($_POST["user_pass_login"]);

        echo "<p>$userName </p>";
        echo "<p>$userPass </p>";

        // validation state: =========================================
        //[$cusName, $deliveryAddress, $cusEID, $droneModel]
        $redpArr = [false , false]; 
        # true =>fine || false=>have error

        if(strlen($userName)<=20){
            $redpArr[0] = true;
        }
        if(strlen($userPass)<=10 and strlen($userPass)>4){
            $redpArr[1] = true;
        };


        if($redpArr[0] == false or $redpArr[1] == false){
            if ($redpArr[0] == false){
                echo "<p>Please Enter userName length less than 20 and greater than 4 character!!!</p>";
            };
            if ($redpArr[1] == false){
                echo "<p>Please Enter user password less than 10 and greater than 4 character!!!</p>";
            };
        }else{ 
            $sql_select = "SELECT userName, userPass FROM userreg WHERE `userName`='$userName' AND `userPass`='$userPass';";
            $result_insert = $Py_server -> query($sql_select);
            $rows = mysqli_fetch_row($result_insert);

            if($result_insert==TRUE and $rows!=null) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $_SESSION['userName'] = $userName;
                    header("Location: ../index.php");
                    exit();
                };
                //echo "<p>$rows[0] , $rows[1]</p>";
            }else{
                echo "<p>You are not REgister not yet!!!</p>";
            };
        };
        mysqli_close($Py_server)
    ?>

</body>
</html>






































































