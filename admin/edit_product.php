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
<?php
//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description =  $_POST["description"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $visiblity = $_POST["visibility"];
	$user = get_user_id();
	$db = getDB();
	if(isset($id)){
		$stmt = $db->prepare("UPDATE Products set name=:name, category=:category, description=:description, quantity=:quantity, price=:price where id=:id");
		//$stmt = $db->prepare("INSERT INTO F20_Eggs (name, state, base_rate, mod_min, mod_max, next_stage_time, user_id) VALUES(:name, :state, :br, :min,:max,:nst,:user)");
		$r = $stmt->execute([
			":name"=>$name,
			":category"=>$category,
			":description"=>$description,
			":quantity"=>$quantity,
			":price"=>$price,
			":id"=>$id
		]);
		if($r){
			flash("Updated successfully with id: " . $id);
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error updating: " . var_export($e, true));
		}
	}
	else{
		flash("ID isn't set, we need an ID in order to update");
	}
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM Products where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<form method="POST">
	<label>Product Name</label>
	<input name="name" placeholder="Name" required value="<?php echo $result["name"];?>"/>
    <label>Category</label>
    <select name="category" required value="<?php echo $result["category"];?>">
        <option value="technology" <?php echo ($result["category"] == "technology"?'selected="selected"':'');?>>Technology</option>
        <option value="cooking" <?php echo ($result["category"] == "cooking"?'selected="selected"':'');?>>Cooking</option>
        <option value="clothes" <?php echo ($result["category"] == "clothes"?'selected="selected"':'');?>>Clothes</option>
        <option value="food" <?php echo ($result["category"] == "food"?'selected="selected"':'');?>>Food</option>
        <option value="storage" <?php echo ($result["category"] == "storage"?'selected="selected"':'');?>>Storage</option>
        <option value="furniture" <?php echo ($result["category"] == "furniture"?'selected="selected"':'');?>>Furniture</option>
    </select>
    <label>Product Description</label>
    <textarea name="description" placeholder="Description" required><?php echo $result["description"];?></textarea>
	<label>Quantity</label>
	<input type="number" min="1" name="quantity" required value="<?php echo $result["quantity"];?>"/>
	<label>Price</label>
	<input type="number" min="0" name="price" required value="<?php echo $result["price"];?>"/>
    <label>Visibility</label>
    <select name="visibility" value="<?php echo $result["name"];?>">
        <option value=0 <?php echo ($result["visibility"] == "0"?'selected="selected"':'');?>>Private</option>
        <option value=1 <?php echo ($result["visibility"] == "1"?'selected="selected"':'');?>>Public</option>
    </select>
	<input type="submit" name="save" value="Save"/>
</form>


<?php require(__DIR__ . "/../partials/flash.php");