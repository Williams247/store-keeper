<?php

  include "./includes/header.php";

  $errors = [];

  $response_message = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];

    if (empty($email)) {
      $errors['email'] = "Email is required";
    }

    else if (empty($name)) {
      $errors['name'] = "Name is required";
    }

    else if (empty($password)) {
      $errors['password'] = "Password is required";
    } else {
      $id = rand(1, 1010101010101010101);
      $sql = "INSERT INTO users (id, name, email, password) VALUES ('$id', '$name', '$email', '$password')";
      $result = pg_query($conn, $sql);

      if ($result) {
        $response_message = 'User Registered Successfully';
      } else {
        $response_message = 'Failed to register user';
       }
     }
    
  }

?>

<?php if ($response_message): ?>
  <script>
    function onShowResponseMessage(message) {
      alert(message);
    }
    onShowResponseMessage(<?php echo $response_message ?>);
  </script>
<?php endif ?>

<div class="w-100 bg-body-secondary d-flex justify-content-center h-100 align-items-center">
  <div class="w-25">
  	<h5 class="text-center">
  		<span class="text-secondary">Store</span> 
  		<span class="text-dark-emphasis">Keeper</span>
    </h5>

  	<div class="w-full bg-white border px-3 pt-4 pb-2 mt-3">
  	 <form method="POST">
      <input type="text" name="name" class="form-control" placeholder="Full Name" />

      <?php if (isset($errors['name'])): ?>
        <p class="text-danger mt-1"><?= htmlspecialchars($errors['name']) ?></p>
      <?php endif ?>
      
  	 	<input type="email" name="email" class="form-control mt-3" placeholder="admin@sample.com" />

      <?php if (isset($errors['email'])): ?>
        <p class="text-danger mt-1"><?= htmlspecialchars($errors['email']) ?></p>
      <?php endif ?>

  	 	<input type="password" name="password" class="form-control mt-3" placeholder="*******" />


      <?php if (isset($errors['password'])): ?>
        <p class="text-danger mt-1"><?= htmlspecialchars($errors['password']) ?></p>
      <?php endif ?>


  	 	<button type="submit" class="btn btn-primary text-white mt-3 w-100">Register</button>
  	 </form>
  	 <a href="/store-keeper/index.php">Login</a>
  	</div>
  </div>
</div>

