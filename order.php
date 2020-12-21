<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/flash.css">
    <link rel="stylesheet" href="css/card.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="stylesheet" href="css/order.css">
   
    <title>Shop</title>
</head>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<body>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$db = getDB();

$stmt = $db->prepare("SELECT c.id, c.product_id, p.name, c.price, c.quantity, (c.price * c.quantity) as sub from Cart c JOIN Products p on c.product_id = p.id where c.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT id, p");
$total_price = 0;
foreach($results as $r){
    $total_price+= $r['sub'];
}
?>

<?php
$address = null;
$zipcode = null;
$city = null;
$state = null;    
?>

<?php
if(isset($_POST["Purchase"])){
    $address = $_POST["address"];
    $zipcode = $_POST["zipcode"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $exit = FALSE;
    $stmt = $db->prepare("SELECT quantity, product_id from Cart where user_id = :id");
    $stmt->execute([":id"=>get_user_id()]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $e = $stmt->errorInfo();
    if($e[0] != "00000"){
        flash(var_export($e, true), "alert");
    }
    foreach($cart as $c){
        $stmt = $db->prepare("SELECT name, quantity from Products where id = :id");
        $stmt->execute([":id"=>$c['product_id']]);
        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $e = $stmt->errorInfo();
        if($e[0] != "00000"){
            flash(var_export($e, true), "alert");
            die(header("Location: my_cart.php"));
        }
        if($c['quantity']>$stock[0]['quantity']){
            if($stock[0]['quantity'] == 0){
                flash($stock[0]['name'] . " is out of stock");
            } else {
                flash($stock[0]['name'] . " does not have " . $c['quantity'] . " avaliable. Only " . $stock[0]['quantity'] . ($stock[0]['quantity']>1 ? " are" : " is") . " avaliable");
            }
            
            die(header("Location: my_cart.php"));
            //exit();
            $exit=TRUE;
        }
    }
    
    if(!$exit){
        $order_id = 0;
        $fullAddress = $address . " " . $city . " " . $state . " " . $zipcode;
        $user = get_user_id();
        $stmt = $db->prepare("INSERT INTO Orders (user_id, total_price, payment_method, shipping_address) VALUES(:user, :total_price, :payment_method, :shipping_address)");
        $r = $stmt->execute([
            ":user"=>$user,
            ":total_price"=>$total_price,
            ":payment_method"=>$_POST["payment"],
            ":shipping_address"=>$fullAddress
        ]);

        if($r){
            $order_id = $db->lastInsertId();

            flash("Successfully placed order with order id: " . $db->lastInsertId());
        }
        else{
            $e = $stmt->errorInfo();
            flash("Error creating: " . var_export($e, true));
            die(header("Location: my_cart.php"));
        }
        foreach($results as $r){
            $stmt = $db->prepare("INSERT INTO OrderItems (order_id, product_id, quantity, price) VALUES(:order_id, :product_id, :quantity, :price)");
            $in = $stmt->execute([
                ":order_id"=>$order_id,
                ":product_id"=>$r["product_id"],
                ":quantity"=>$r["quantity"],
                ":price"=>$r["price"]
            ]);
            if($in){
                //echo("SUCCESS!");
            }
            else{
                $e = $stmt->errorInfo();
                flash("Error creating: " . var_export($e, true));
                die(header("Location: my_cart.php"));
                
            }
        }

        $stmt = $db->prepare("SELECT product_id, quantity from Cart where user_id = :id");
        $stmt->execute([":id"=>get_user_id()]);
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($cart){
            //echo("SUCCESS!!");
        }
        else{
            $e = $stmt->errorInfo();
            flash("Error creating: " . var_export($e, true));
            die(header("Location: my_cart.php"));
        }
        foreach($cart as $c){
            $stmt = $db->prepare("SELECT id, quantity from Products where id = :id");
            $stmt->execute([":id"=>$c['product_id']]);
            $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = $db->prepare("UPDATE Products set quantity=:quantity where id=:id");
            $quantity = ($stock[0]['quantity']-$c['quantity'] <=0? 0 : $stock[0]['quantity']-$c['quantity']);
            $update = $stmt->execute([
                ":quantity"=>$quantity,
                ":id"=>$c["product_id"]
            ]);
        }

        $stmt = $db->prepare("DELETE FROM Cart where user_id = :id");
        $r = $stmt->execute([":id"=>get_user_id()]);
        die(header("Location: orderplaced.php"));
    }
}
?>


    <div>
        <?php foreach($results as $r):?>
            <div><?php echo $r["name"];?>, $<?php echo $r["price"];?>  x<?php echo $r["quantity"];?> $<?php echo $r["sub"];?></div>
        <?php endforeach;?>
        <div>Total: $<div id="total"><?php echo($total_price)?></div></div>
        
    </div>

    <form method="post">
        <label>Shipping Address</label>
        <input type="text" id="address" name="address" class="input" placeholder="Street Address" required value="<?php safer_echo($address); ?>">
        <input type="text" id="zipcode" name="zipcode" class="input" placeholder="Zipcode" pattern="[0-9]*" required value="<?php safer_echo($zipcode); ?>">
        <input type="text" id="city" name="city" class="input" placeholder="City" required value="<?php safer_echo($city); ?>">
        <input type="text" id="state" name="state" class="input" placeholder="State" required value="<?php safer_echo($state); ?>">
        <div class="gap"></div>
        <label>Payment Method</label>
        <select name="payment" required>
            <option value="cash">Cash</option>
            <option value="Visa">Visa</option>
            <option value="MasterCard">MasterCard</option>
            <option value="Amex">Amex</option>
        </select>
        <input type="submit" value="Purchase" name="Purchase" class="button1">
    </form>



<div class="flash">
        <?php require(__DIR__ . "/partials/flash.php");?>
    </div>
</body>
