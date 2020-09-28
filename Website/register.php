<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
</head>
<body>
    <form class="box" method="POST">
        <h1>Register!</h1>
        <input type="text" id="email" name="email" class="input" placeholder="Email" required>
        <input type="password" id="p1" name="password" class="input" placeholder="Password" required>
        <input type="password" id="p2" name="confirm" class="input" placeholder="Confirm Password" required>
        <input type="submit" value="Register" name="register" class="button">
    </form>
    <div id="registartion">

    </div>
</body>
</html>

<?php
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
