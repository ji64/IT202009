<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
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

<form method="POST">
	<label>Product Name</label>
	<input name="name" placeholder="Name" required/>
    <label>Category</label>
    <select name="category" required>
        <option value="technology">Technology</option>
        <option value="cooking">Cooking</option>
        <option value="clothes">Clothes</option>
        <option value="food">Food</option>
        <option value="storage">Storage</option>
        <option value="furniture">Furniture</option>
    </select>
    <label>Product Description</label>
    <textarea name="description" placeholder="Description" required></textarea>
	<label>Quantity</label>
	<input type="number" min="1" name="quantity" required/>
	<label>Price</label>
	<input type="number" min="0" name="price" required/>
    <label>Visibility</label>
    <select name="visibility">
        <option value=0>Private</option>
        <option value=1>Public</option>
    </select>
	<input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description =  $_POST["description"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $visiblity = $_POST["visibility"];
    $user = get_user_id();
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO Products (name, category, description, quantity, price, user_id, visibility) VALUES(:name, :category, :description, :quantity, :price, :user, :visibility)");
	$r = $stmt->execute([
        ":name"=>$name,
        ":category"=>$category,
		":description"=>$description,
		":quantity"=>$quantity,
        ":price"=>$price,
        ":user"=>$user,
        ":visibility"=>$visiblity
	]);
	if($r){
		flash("Created successfully with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
	}

}
?>
<?php require(__DIR__ . "/../partials/flash.php");?>