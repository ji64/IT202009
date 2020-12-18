<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/item.css">
    <title>Shop</title>
</head>
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
            <div>$<?php safer_echo($result["price"]); ?></div>
            <div>Cat: <?php safer_echo($result["category"]); ?></div>
            <div>Desc: <?php safer_echo($result["description"]); ?></div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");?>
</body>