<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/flash.css">
    <link rel="stylesheet" href="css/card.css">
    <link rel="stylesheet" href="css/product_list.css">
   
    <title>Shop</title>
</head>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
//https://www.digitalocean.com/community/tutorials/how-to-implement-pagination-in-mysql-with-php-on-ubuntu-18-04
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
$stmt = $db->prepare("SELECT count(*) as total from Products WHERE visibility = 1");
if(has_role("Admin")){
    $stmt = $db->prepare("SELECT count(*) as total from Products");
}

$stmt->execute([":id"=>get_user_id()]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($result){
    $total = (int)$result["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
$stmt = $db->prepare("SELECT id, name, category, quantity, price, visibility from Products WHERE visibility = 1 LIMIT :offset, :count ");
if(has_role("Admin")){
    $stmt = $db->prepare("SELECT id, name, category, quantity, price, visibility from Products LIMIT :offset, :count ");
}
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
    <div class="container-fluid">
    <h3>Products</h3>
    <div class="row">
    <div class="card-group">
    <?php if($results && count($results) > 0):?>
        <?php foreach($results as $r):?>
            
                <div class="card">
                    <div class="card-body">
                    <a class="cardlink" href="view_product.php?id=<?php safer_echo($r['id']); ?>">
                        <div class="card-title">
                            <?php safer_echo($r["name"]);?>
                        </div>
                        <div class="card-text">
                            <div class="price">$<?php safer_echo($r["price"]);?></div>
                            <div class="category"><?php safer_echo($r["category"]);?></div>
                            <?php if($r["quantity"]<=0):?>
                                Out of Stock
                            <?php else:?>
                                <?php safer_echo($r["quantity"]); ?> in stock
                            <?php endif; ?>
                            
                        </div>
                            </a>
                        <div class="card-footer">
                            <div class="cart-button">
                            <?php if($r["quantity"]>0):?>
                                <form method="POST">
                                    <button onclick="addToCart(<?php safer_echo($r['id']); ?>)">Add to Cart</button>
                                </form>
                            <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
        <?php endforeach;?>

    <?php else:?>
    <div class="col-auto">
        <div class="card">
        There are no Products
    </div>
    <?php endif;?>
    </div>
    </div>


    <script>
        function addToCart(itemId){
            //https://www.w3schools.com/xml/ajax_xmlhttprequest_send.asp
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let json = JSON.parse(this.responseText);
                    if (json) {
                        if (json.status == 200) {
                            alert(json.message);
                        } else {
                            alert(json.error);
                        }
                    }
                }
            };
            xhttp.open("POST", "api/add_to_cart.php", true);
            //this is required for post ajax calls to submit it as a form
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //map any key/value data similar to query params
            xhttp.send("itemId="+itemId);
        }
    </script>
<div class="flash">
    <?php require(__DIR__ . "/partials/flash.php");?>
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
        
    </div>
