<?php
include "./includes/header.php";
include "./auth/is-logged-in.php";

$user_email = $_COOKIE['STORE_KEEPER_USER'];
?>

<div class="d-flex">
   <?php include './includes/sidebar.php'; ?>
   
   <div class="w-75 bg-body-secondary px-3 py-3">
      <div class="mx-auto mt-4 w-75">
         <?php
         $query = "SELECT * FROM stores WHERE email = $1";
         $result = pg_query_params($conn, $query, [$user_email]);

         if ($result && pg_num_rows($result) > 0) {
             $store = pg_fetch_assoc($result);

             $display_name = $store['fullname'] 
                 ?? $store['store_name'] 
                 ?? $store['name'] 
                 ?? $store['owner_name'] 
                 ?? $user_email;

             echo "<h4>Welcome, " . htmlspecialchars($display_name) . "</h4>";
         } else {
             echo "<p class='text-danger'>User not found.</p>";
         }
         ?>
      </div>
   </div>
</div>
