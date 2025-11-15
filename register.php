<?php
include "./includes/header.php";

# Initialize error and response variables
$email_error = "";
$name_error = "";
$password_error = "";
$response_message = '';

$name = '';
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  # Trim inputs
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $has_error = false;

  # Validate inputs
  if (empty($name)) {
    $name_error = "Name is required";
    $has_error = true;
  }

  if (empty($email)) {
    $email_error = "Email is required";
    $has_error = true;
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format";
    $has_error = true;
  }

  if (empty($password)) {
    $password_error = "Password is required";
    $has_error = true;
  }

  # Check if user already exists
  if (!$has_error) {
    $sql = "SELECT * FROM users WHERE email = $1";
    $result = pg_query_params($conn, $sql, [$email]);

    if ($result && pg_num_rows($result) > 0) {
      $email_error = "Email already exists";
      $has_error = true;
    }
  }

  # Insert into database if no errors
  if (!$has_error) {
    # Generate a unique ID
    $id = random_int(1, 999999999999999999);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (id, name, email, password) VALUES ($1, $2, $3, $4)";
    $result = pg_query_params($conn, $sql, [$id, $name, $email, $hashed_password]);

    if ($result) {
      $response_message = 'User Registered Successfully';
      # Clear form values after successful registration
      $name = $email = $password = '';
    } else {
      $response_message = 'Failed to register user. Please try again.';
    }
  }
}
?>

<?php if ($response_message): ?>
  <script>
    function onShowResponseMessage(message) {
      alert(message);
    }
    onShowResponseMessage("<?php echo $response_message; ?>");
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
        <input type="text" name="name" class="form-control" placeholder="Full Name"
          value="<?php echo htmlspecialchars($name); ?>" />
        <?php if ($name_error): ?>
          <p class="text-danger mt-1"><?php echo $name_error; ?></p>
        <?php endif ?>

        <input type="email" name="email" class="form-control mt-3" placeholder="admin@sample.com"
          value="<?php echo htmlspecialchars($email); ?>" />
        <?php if ($email_error): ?>
          <p class="text-danger mt-1"><?php echo $email_error; ?></p>
        <?php endif ?>

        <input type="password" name="password" class="form-control mt-3" placeholder="*******" />
        <?php if ($password_error): ?>
          <p class="text-danger mt-1"><?php echo $password_error; ?></p>
        <?php endif ?>

        <button type="submit" class="btn btn-primary text-white mt-3 w-100">Register</button>
      </form>
      <a href="/store-keeper/index.php">Login</a>
    </div>
  </div>
</div>
