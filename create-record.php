<?php
include "./includes/header.php";
include "./auth/is-logged-in.php";

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
            <label class="mt-3">Enter your quantity</label>
            <input type="text" name="quantity" placeholder="5" class="form-control mt-2" />
            <label class="mt-3">Enter your price</label>
            <input type="text" name="price" placeholder="$20" class="form-control mt-2" />
            <button class="btn btn-primary mt-3 w-100" type="submit">Submit</button>
         </form>
        </div>
      </div>
   </div>
</div>
