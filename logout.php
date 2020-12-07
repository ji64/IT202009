<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <title>Shop</title>
</head>
<?php
session_start();
// remove all session variables
session_unset();
// destroy the session
session_destroy();
?>
<?php require_once(__DIR__ . "/partials/nav.php");/*ultimately, this is just here for the function to be loaded now*/ ?>
<?php

flash("You have been logged out");
die(header("Location: login.php"));
?>
