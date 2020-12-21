<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/flash.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="stylesheet" href="css/cart.css">
    <title>Shop</title>
</head>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();

if(isset($_POST["delete"])){
    $stmt = $db->prepare("DELETE FROM Cart where id = :id");
    $r = $stmt->execute([":id"=>$_POST["cartId"]]);
    //fix for example bug
    //$stmt = $db->prepare("DELETE FROM F20_Cart where id = :id AND user_id = :uid");
    //$r = $stmt->execute([":id"=>$_POST["cartId"], ":uid"=>get_user_id()]);
    if($r){
        flash("Deleted item from cart", "success");
    }
}

if(isset($_POST["clear"])){
    $stmt = $db->prepare("DELETE FROM Cart where user_id = :id");
    $r = $stmt->execute([":id"=>get_user_id()]);
    if($r){
        flash("Cleared cart", "success");
    }
}

if(isset($_POST["update"])){

    if($_POST["quantity"]<=0){
        $stmt = $db->prepare("DELETE FROM Cart where id = :id");
        $r = $stmt->execute([":id"=>$_POST["cartId"]]);
        if($r){
            flash("Deleted item from cart", "success");
        }
    } else {
        $stmt = $db->prepare("UPDATE Cart set quantity = :q where id = :id");
        $r = $stmt->execute([":id"=>$_POST["cartId"], ":q"=>$_POST["quantity"]]);
        if($r){
            flash("Updated quantity", "success");
        }
    }    
}


$stmt = $db->prepare("SELECT c.id, c.product_id, p.name, c.price, c.quantity, (c.price * c.quantity) as sub from Cart c JOIN Products p on c.product_id = p.id where c.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
?>
    <div class="container-fluid">
        <h3>Your Cart</h3>

        <div class="flash">
            <?php require(__DIR__ . "/partials/flash.php");?>
        </div>

        <div class="list-group">
        <?php if($results && count($results) > 0):?>
            <?php foreach($results as $r):?>
                <?php $total_price+= $r['sub']?>
            <div class="list-group-item">
                <form method="POST">
                <div class="row">
                    <div class="name">
                    <a class="cardlink" href="view_product.php?id=<?php safer_echo($r["product_id"]); ?>"><?php echo $r["name"];?></a>
                    </div>
                    <div class="price">
                        $<?php echo $r["price"];?>
                    </div>
                    <div class="col">

                            <input type="number" min="0" name="quantity" value="<?php echo $r["quantity"];?>"/>
                            <input type="hidden" name="cartId" value="<?php echo $r["id"];?>"/>

                    </div>
                    <div class="col">
                        $<?php echo $r["sub"];?>
                    </div>
                    <div class="col">
                        <!-- form split was on purpose-->
                        <input type="submit" class="btn btn-success" name="update" value="Update"/>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="cartId" value="<?php echo $r["id"];?>"/>
                            <input type="submit" class="remove" name="delete" value="Remove Item"/>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach;?>

            <div>
            <div>Total: $<?php echo($total_price)?></div>
        </div>

        <form  method="post">
            <input type="submit" class="button1" name="clear" value="Clear Cart"/>
        </form>

        <a href="order.php">
            <div class="button1">Purchase</div>
        </a>
        <?php else:?>
        <div class="list-group-item">
            No items in cart
        </div>
        <?php endif;?>
        </div>

        
    </div>
