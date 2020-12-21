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
    <link rel="stylesheet" href="css/ordercompleted.css">
   
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

    <div class="flash">
        <?php require(__DIR__ . "/partials/flash.php");?>
    </div>

    <a href= "list_product.php"><div class = "button1">Go Back to the store</div></a>

</body>