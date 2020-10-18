<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" href="css/login.css">-->
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
              require_once(__DIR__."/../lib/db.php");
              $db = getDB();
              if(isset($db)){
                  $stmt = $db->prepare("SELECT id, email, password from Users WHERE email = :email LIMIT 1");
                  
                  $params = array(":email"=>$email);
                  $r = $stmt->execute($params);
                  echo "db returned: " . var_export($r, true);
                  $e = $stmt->errorInfo();
                  if($e[0] != "00000"){
                      echo "uh oh something went wrong: " . var_export($e, true);
                  }
                  $result = $stmt->fetch(PDO::FETCH_ASSOC);
                  if($result && isset($result["password"])){
                      $password_hash_from_db = $result["password"];
                      if(password_verify($password, $password_hash_from_db)){
                  session_start();//we only need to active session when it's worth activating it
                  unset($result["password"]);//remove password so we don't leak it beyond this page
                  //let's create a session for our user based on the other data we pulled from the table
                  $_SESSION["user"] = $result;//we can save the entire result array since we removed password
                  //on successful login let's serve-side redirect the user to the home page.
                        header("Location: home.php");
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