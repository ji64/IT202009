<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/buttons.css">
    <link rel="stylesheet" href="css/item.css">
    <title>Shop</title>
</head>
<body>
    

<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT name, category, description, quantity, price FROM Products WHERE id=:id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }

}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["name"]); ?>
            <?php if (has_role("Admin")): ?>
                <div><a href="admin/edit_product.php?id=<?php safer_echo($id); ?>">Edit</a></div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div><p>Price: $<?php safer_echo($result["price"]); ?></p></div>
            <div><p>Category: <?php safer_echo($result["category"]); ?></p></div>
            <div><p>Desc: <?php safer_echo($result["description"]); ?></p></div>
        </div>
    </div>

    <?php if($result['quantity']>0): ?>

    <form method="POST">
        <button class="button1" onclick="addToCart(<?php safer_echo($id); ?>)">Add to Cart</button>
    </form>

    <?php endif ?>

    
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");?>
</body>

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