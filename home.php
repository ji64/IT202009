<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Shop</title>
</head>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>
<p>Welcome, <?php echo $email; ?></p>
<style>
    body{
        background-color: #E5E5E5;
        margin: 0 0;
        padding: 0 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    p{
        text-align: center;
    }
</style>
<?php require(__DIR__ . "/partials/flash.php");