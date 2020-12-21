<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/flash.css">
    <title>Shop</title>
</head>

<?php
    if (isset($_POST["Register"])) {
        $email = null;
        $password = null;
        $confirm = null;
        $username = null;
        if (isset($_POST["email"])) {
            $email = $_POST["email"];
        }
        if (isset($_POST["password"])) {
            $password = $_POST["password"];
        }
        if (isset($_POST["confirm"])) {
            $confirm = $_POST["confirm"];
        }
        if (isset($_POST["username"])) {
            $username = $_POST["username"];
        }
        $isValid = true;
        //check if passwords match on the server side
        if ($password == $confirm) {
            //not necessary to show
            //echo "Passwords match <br>";
        } else {
            flash("Passwords don't match");
            $isValid = false;
        }
        if (!isset($email) || !isset($password) || !isset($confirm)) {
            $isValid = false;
        }
        if (!strpos($email, "@")) {
            $isValid = false;
            //echo "<br>Invalid email<br>";
            flash("Invalid email ");
        }
        //TODO other validation as desired, remember this is the last line of defense
        if ($isValid) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
    
            $db = getDB();
            if (isset($db)) {
                //here we'll use placeholders to let PDO map and sanitize our data
                $stmt = $db->prepare("SELECT id, email, username FROM Users WHERE email=:email");
                //here's the data map for the parameter to data
                $params = array(":email" => $email);
                $stmt->execute($params);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if($results){
                    if($results[0]['username']==$username){
                        $stmt = $db->prepare("UPDATE Users set password=:password where id=:id");
                        //here's the data map for the parameter to data
                        $params = array(":password" => $hash, ":id"=>$results[0]['id']);
                        $r = $stmt->execute($params);
                       
                        if ($r) {
                            flash("Successfully reset password! Please login.");
                            die(header("Location: login.php"));
                        } else{
                            $e = $stmt->errorInfo();
                            flash("Error creating: " . var_export($e, true));
                        }
                    } else {
                        flash("Username does not match existing account.");
                    }
                    
                } else {
                    $e = $stmt->errorInfo();
                    flash("Error. This email does not exist.");
                }
                
            }
        }
    } else {
            
    }
    
    //safety measure to prevent php warnings
    if (!isset($email)) {
        $email = "";
    }
    if (!isset($username)) {
        $username = "";
    }
        ?>

<body>
    <div class="flash">
        <?php require(__DIR__ . "/partials/flash.php");?>
    </div>
    <div class="container">
        <div class="register">
            
            <form class="box" method="POST">
                <h1>Reset Password</h1>
                <input type="text" id="email" name="email" class="input" placeholder="Email" required value="<?php safer_echo($email); ?>"/>
                <input type="text" id="user" name="username" class="input" placeholder="Username" required maxlength="60" value="<?php safer_echo($username); ?>"/>
                <input type="password" id="password" name="password" class="input" placeholder="Password" required>
                <input type="password" id="confirm" name="confirm" class="input" placeholder="Confirm Password" required>
                <input type="submit" value="Reset" name="Register" class="button1">
            </form>
        </div>
        
    
        <div class="output">
        
        </div>
        

        <div class="login">
            <h1>Know your password?</h1>
            <a href="login.php" id="login"><div class=button2>LOGIN</div></a>

        </div>
    </div>
    
</body>


</html>