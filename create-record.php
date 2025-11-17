<?php
include "./includes/header.php";
include "./auth/is-logged-in.php";

# Error message
$error_message = '';

# Success message
$error_message = '';

# Error variables
$item_error = '';
$quantity_error = '';
$price_error = '';

# Execute action if method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   # Request inputs
   $item = trim($_POST['item']);
   $quantity = trim($_POST['quantity']);
   $price = trim($_POST['price']);

   # Validate inputs
   if (empty($item)) {
      $item_error = "Item is required";
      return;
   }

   if (empty($quantity)) {
      $quantity_error = "Quantity is required";
      return;
   }

   if (empty($price)) {
      $price_error = "Price is required";
      return;
   }

   # Insert into database
   $id = random_int(1, 999999999999999999);
   $sql = "INSERT INTO items (id, item, quantity, price) VALUES ($1, $2, $3, $4)";
   $result = pg_query_params($conn, $sql, [$id, $item, $quantity, $price]);

   if (!$result) {
      $error_message = 'Failed to add item';
   } else {
      $success_message = 'Item added successfully';
   }

}
?>

<div class="d-flex">
   <?php include './includes/sidebar.php'; ?>

   <div class="w-75 bg-body-secondary px-3 py-3">
      <div class="mx-auto mt-4 w-50">
         <div class="card px-4 py-4 mt-5">
            <form method="POST">
               <p class="fw-bold fs-5">Create your item</p>
               <label>Enter your item</label>
               <input type="text" name="item" placeholder="Rice" class="form-control mt-2" />

               <?php if ($item_error): ?>
                  <p class="text-danger"><?php echo $item_error; ?></p>
               <?php endif ?>

               <label class="mt-3">Enter your quantity</label>
               <input type="text" name="quantity" placeholder="5" class="form-control mt-2" />

               <?php if ($quantity_error): ?>
                  <p class="text-danger"><?php echo $quantity_error; ?></p>
               <?php endif ?>

               <label class="mt-3">Enter your price</label>
               <input type="text" name="price" placeholder="$20" class="form-control mt-2" />

               <?php if ($price_error): ?>
                  <p class="text-danger"><?php echo $price_error; ?></p>
               <?php endif ?>

               <button class="btn btn-primary mt-3 w-100" type="submit">Submit</button>
            </form>
         </div>
      </div>
   </div>
</div>