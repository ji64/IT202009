<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/register.css">
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
            if(!strpos($email, "@")){
                $isValid = false;
                echo "<br>Invalid email<br>";
            }
            //TODO other validation as desired, remember this is the last line of defense
            if($isValid){
                $hash = password_hash($password, PASSWORD_BCRYPT);
                require_once("db.php");
                $db = getDB();
                if(isset($db)){
                    //here we'll use placeholders to let PDO map and sanitize our data
                    $stmt = $db->prepare("INSERT INTO Users(email, password) VALUES(:email, :password)");
                    //here's the data map for the parameter to data
                    $params = array(":email"=>$email, ":password"=>$hash);
                    $r = $stmt->execute($params);
                    //let's just see what's returned
                    echo "db returned: " . var_export($r, true);
                    $e = $stmt->errorInfo();
                    if($e[0] == "00000"){
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
