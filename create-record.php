<?php
include "./includes/header.php";
include "./auth/is-logged-in.php";

# Success & Error messages
$success_message = '';
$error_message = '';
$general_error = '';

# Error variables
$item_error = '';
$quantity_error = '';
$price_error = '';

# Execute action if method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   # Request inputs
   $item = trim($_POST['item'] ?? '');
   $quantity = trim($_POST['quantity'] ?? '');
   $price = trim($_POST['price'] ?? '');

   # If all fields are missing, stop execution
   if (!isset($_POST['item']) || !isset($_POST['quantity']) || !isset($_POST['price'])) {
      return;
   }

   # Validate inputs
   if (empty($item)) {
      $item_error = "Item is required";
   }

   if (empty($quantity)) {
      $quantity_error = "Quantity is required";
   } elseif (!ctype_digit($quantity)) {
      $quantity_error = "Quantity must be a number";
   }

   if (empty($price)) {
      $price_error = "Price is required";
   } elseif (!ctype_digit($price)) {
      $price_error = "Price must be a number";
   }

   # If any errors exist, stop now
   if ($item_error || $quantity_error || $price_error) {
     $general_error = 'An error occurred.';
   } else {
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
}
?>

<div class="d-flex">
   <?php include './includes/sidebar.php'; ?>

   <div class="w-75 bg-body-secondary px-3 py-3">
      <div class="mx-auto mt-4 w-50">
         <div class="card px-4 py-4 mt-5">

            <?php if ($general_error): ?>
               <div class="alert alert-danger">
                  <?php echo $general_error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
               <p class="alert alert-success"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if ($error_message): ?>
               <p class="alert alert-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form method="POST">
               <p class="fw-bold fs-5">Create your item</p>

               <label>Enter your item</label>
               <input type="text" name="item" placeholder="Rice" class="form-control mt-2"
                  value="<?= htmlspecialchars($item ?? '') ?>" />
               <?php if ($item_error): ?>
                  <p class="text-danger"><?php echo $item_error; ?></p>
               <?php endif; ?>

               <label class="mt-3">Enter your quantity</label>
               <input type="text" name="quantity" placeholder="5" class="form-control mt-2"
                  value="<?= htmlspecialchars($quantity ?? '') ?>" />
               <?php if ($quantity_error): ?>
                  <p class="text-danger"><?php echo $quantity_error; ?></p>
               <?php endif; ?>

               <label class="mt-3">Enter your price</label>
               <input type="text" name="price" placeholder="100" class="form-control mt-2"
                  value="<?= htmlspecialchars($price ?? '') ?>" />
               <?php if ($price_error): ?>
                  <p class="text-danger"><?php echo $price_error; ?></p>
               <?php endif; ?>

               <button class="btn btn-primary mt-3 w-100" type="submit">Submit</button>
            </form>
         </div>
      </div>
   </div>
</div>