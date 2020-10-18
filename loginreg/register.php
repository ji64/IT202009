<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" href="css/register.css">-->
    <title>Shop</title>
</head>
<body>
    <div class="container">
        <div class="register">
            <form class="box" method="POST">
                <h1>Register!</h1>
                <input type="text" id="email" name="email" class="input" placeholder="Email" required>
                <input type="password" id="password" name="password" class="input" placeholder="Password" required>
                <input type="password" id="confirm" name="confirm" class="input" placeholder="Confirm Password" required>
                <input type="submit" value="Register" name="Register" class="button1">
            </form>
        </div>
        
    
        <div class="output">
            <?php
            //$_POST["NAME"]
            if(isset($_POST["Register"])){
                echo "PHP WORKS? <br>";
                $email = null;
                $password = null;
                $confirm = null;
                if(isset($_POST["email"])){
                    $email = $_POST["email"];
                }
                if(isset($_POST["password"])){
                    $password = $_POST["password"];
                }
                if(isset($_POST["confirm"])){
                    $confirm = $_POST["confirm"];
                }
                $isValid = true;
                //check if passwords match on the server side
                if($password == $confirm){
                    echo "Passwords match <br>"; 
                }
                else{
                    echo "Passwords don't match<br>";
                    $isValid = false;
                }
                if(!isset($email) || !isset($password) || !isset($confirm)){
                    $isValid = false; 
                }
                //TODO other validation as desired, remember this is the last line of defense
                echo "Start Database <br>";
                if($isValid){
                    echo "1 <br>";
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    echo "2 <br>";
                    require_once(__DIR__."/../lib/db.php");
                    echo "3 <br>";
                    $db = getDB();
                    echo "4 <br>";
                    if(isset($db)){
                        echo "5 <br>";
                        //here we'll use placeholders to let PDO map and sanitize our data
                        $stmt = $db->prepare("INSERT INTO Users(email, password) VALUES(:email, :password)");
                        echo "6 <br>";
                        //here's the data map for the parameter to data
                        $params = array(":email"=>$email, ":password"=>$hash);
                        echo "7 <br>";
                        $r = $stmt->execute($params);
                        echo "8 <br>";
                        //let's just see what's returned
                        echo "db returned: " . var_export($r, true);
                        echo "9 <br>";
                        $e = $stmt->errorInfo();
                        echo "10 <br>";
                        if($e[0] == "00000"){
                            echo "11 <br>";
                            echo "<br>Welcome! You successfully registered, please login.";
                        }
                        else{
                            echo "uh oh something went wrong: " . var_export($e, true);
                        }
                    }
                }
                else{
                    echo "There was a validation issue"; 
                }
                echo "end database <br>";
            }
            ?>
        </div>
        

        <div class="login">
            <h1>Already Have an Account?</h1>
            <a href="login.php" id="login"><div class=button2>LOGIN</div></a>

        </div>
    </div>
    
</body>


</html>
