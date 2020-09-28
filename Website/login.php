<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Shop</title>
</head>
<body>
    <div class = "container">
        <div class = "login">
            <form method="POST">
                <h1>Login</h1>
                <input type="text" id="email" name="email" class="input" placeholder="Email" required>
                <input type="password" id="password" name="password" class="input" placeholder="Password" required>
                <input type="submit" value="login" name="login" class="button1">
            </form>
        </div>
        
        <div class="output">
        <?php
        if(isset($_POST["login"])){
        $email = null;
        $password = null;
        if(isset($_POST["email"])){
            $email = $_POST["email"];
        }
        if(isset($_POST["password"])){
            $password = $_POST["password"];
        }
        $isValid = true;
        if(!isset($email) || !isset($password)){
        $isValid = false; 
        }
        
        if(!strpos($email, "@")){
        $isValid = false;
            echo "<br>Invalid email<br>";
        }
        if($isValid){
            require_once("db.php");
            $db = getDB();
            if(isset($db)){
                //here we'll use placeholders to let PDO map and sanitize our data
                $stmt = $db->prepare("SELECT email, password from Users WHERE email = :email LIMIT 1");
                //here's the data map for the parameter to data
                $params = array(":email"=>$email);
                $r = $stmt->execute($params);
                //let's just see what's returned
                echo "db returned: " . var_export($r, true);
                $e = $stmt->errorInfo();
                if($e[0] != "00000"){
                    echo "uh oh something went wrong: " . var_export($e, true);
                }
                //since it's a select command we must fetch the results
                //we'll tell pdo to give it to us as an associative array
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if($result && isset($result["password"])){
                    $password_hash_from_db = $result["password"];
                    if(password_verify($password, $password_hash_from_db)){
                    echo "<br>Welcome! You're logged in!<br>"; 
                    }
                    else{
                    echo "<br>Invalid password, get out!<br>"; 
                    }
                }
                else{
                    echo "<br>Invalid user<br>";
                }
            }
        }
        else{
        echo "There was a validation issue"; 
        }
        }
        ?>
        </div>

        <div class="register">
            <h1>Not Registered?</h1>
            <a id="register" href="register.php"><div class="button2">Register now!</div></a>
            
        </div>
    </div>
</body>
</html>