
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>

<ul class="navbar">
    <li class="nav-item"><a href="/../~ji64/home.php">Home</a></li>
    <li class="nav-item"><a href="/../~ji64/list_product.php">Shop</a></li>
    <?php if (!is_logged_in()): ?>
        <li class="nav-item"><a href="/../~ji64/login.php">Login</a></li>
        <li class="nav-item"><a href="/../~ji64/register.php">Register</a></li>
    <?php endif; ?>
    <?php if (has_role("Admin")): ?>
      <div class="dropdown">
        <button class="dropbtn">
          Test ▼
        </button>
        <div class="dropdown-content">
          <li class="nav-item"><a href="/../~ji64/test/test_create_egg.php">Create Egg</a></li>
          <li class="nav-item"><a href="/../~ji64/test/test_list_egg.php">View Eggs</a></li>
          <li class="nav-item"><a href="/../~ji64/test/test_create_incubator.php">Create Incubator</a></li>
          <li class="nav-item"><a href="/../~ji64/test/test_list_incubators.php">View Incubator</a></li>
        </div>
      </div>

      <div class="dropdown">
        <button class="dropbtn">
          Manage Products ▼
        </button>
        <div class="dropdown-content">
          <li class="nav-item"><a href="/../~ji64/admin/create_product.php">Create Product</a></li>
          <li class="nav-item"><a href="/../~ji64/list_product.php">View Products</a></li>
        </div>
      </div>
        
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
      <li class="nav-item"><a href="/../~ji64/my_cart.php">Cart</a></li>
      <div class="dropdown" id="account">
        <button class="dropbtn">
          My Account ▼
        </button>
        <div class="dropdown-content">
        <li class="nav-item"><a>Balance: <?php echo getBalance(); ?></a></li>
        <li class="nav-item"><a href="/../~ji64/profile.php">Profile</a></li>
        <li class="nav-item"><a href="/../~ji64/logout.php">Logout</a></li>
        </div>
      </div>
      
      
    <?php endif; ?>
</ul>

<style>
.navbar {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #50A4F2;
  margin-bottom: 3em;
  box-shadow: 0px 1px 25px grey;
}

.nav-item {
  float: left;
}

.nav-item a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

.nav-item a:hover {
  background-color: rgba(0,0,0,0.3);
}

/*Dropdown Menu From w3schools*/

/* The dropdown container */
.dropdown {
  float: left;
  overflow: hidden;
}

/* Dropdown button */
.dropdown .dropbtn {
  font-size: 16px;
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit; /* Important for vertical align on mobile phones */
  margin: 0; /* Important for vertical align on mobile phones */
}

/* Add a red background color to navbar links on hover */
.navbar a:hover, .dropdown:hover .dropbtn {
  background-color: rgba(0,0,0,0.3);
}

/* Dropdown content (hidden by default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  float: none;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

/* Add a grey background color to dropdown links on hover */
.dropdown-content a:hover {
  background-color: #ddd;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

#account{
  margin-right: 50px;
  float: right;
}

</style>