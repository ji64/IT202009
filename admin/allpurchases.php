<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/flash.css">
    <link rel="stylesheet" href="../css/card.css">
    <link rel="stylesheet" href="../css/product_list.css">
    <link rel="stylesheet" href="../css/purchasehistory.css">
   
    <title>Shop</title>
</head>
<?php require_once(__DIR__ . "/../partials/nav.php"); ?>

<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: /../~ji64/login.php"));
}
?>

<?php
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $e){

    }
}
$db = getDB();
$stmt = $db->prepare("SELECT count(*) as total from Orders");

$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
$stmt = $db->prepare("SELECT id, user_id, total_price, payment_method, created from Orders ORDER BY id desc LIMIT :offset, :count ");

//need to use bindValue to tell PDO to create these as ints
//otherwise it fails when being converted to strings (the default behavior)
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->bindValue(":count", $per_page, PDO::PARAM_INT);

$stmt->execute();
$e = $stmt->errorInfo();
if($e[0] != "00000"){
    flash(var_export($e, true), "alert");
}
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div>
<?php if($results && count($results) > 0):?>
    <?php foreach($results as $r):?>
        <div class="order">
            <?php echo("Order ID: " . $r['id'] . " | User: " . $r['user_id'] . " | Payed with " . $r['payment_method'] . " Total: $" . $r['total_price']); ?>
        </div>
    <?php endforeach;?>

<?php else:?>
    <div class="order">You have not made a purchase yet.</div>
<?php endif;?>
</div>

<div class="flash">
    <?php require(__DIR__ . "/../partials/flash.php");?>
</div>

    <div>
        <nav aria-label="Products">
            <ul class="pagination">
                <li class="page-item-<?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item-<?php echo ($page-1) == $i?"active":"";?>"><a class="page-num" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item-<?php echo ($page+1) > $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>

    </div>
