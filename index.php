<?php
include "./includes/header.php";

// Initialize variables
$email = '';
$password = '';
$email_error = '';
$password_error = '';
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Trim inputs
	$email = trim($_POST['email']);
	$password = $_POST['password'];

	$has_error = false;

	// Validate email
	if (empty($email)) {
		$email_error = "Email is required";
		$has_error = true;
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$email_error = "Invalid email format";
		$has_error = true;
	}

	// Validate password
	if (empty($password)) {
		$password_error = "Password is required";
		$has_error = true;
	}

	// Proceed if no validation errors
	if (!$has_error) {
		$sql = "SELECT * FROM users WHERE email = $1";
		$result = pg_query_params($conn, $sql, [$email]);

		if ($result && pg_num_rows($result) > 0) {
			$user = pg_fetch_assoc($result);

			if (password_verify($password, $user['password'])) {
				// Successful login
				$_SESSION['STORE_KEEPER_USER'] = $email;
				setcookie('STORE_KEEPER_USER', $email, time() + 86400 * 30, '/store-keeper/');
				header('Location: /store-keeper/dashboard.php');
				exit();
			} else {
				$password_error = "Incorrect password";
			}
		} else {
			$email_error = "Email not found";
		}
	}
}
?>

<div class="w-100 bg-body-secondary d-flex justify-content-center h-100 align-items-center">
	<div class="w-25">
		<h5 class="text-center">
			<span class="text-secondary">Store</span>
			<span class="text-dark-emphasis">Keeper</span>
		</h5>
		<div class="w-full bg-white border px-3 pt-4 pb-2 mt-4">
			<form method="POST">
				<input type="email" name="email" class="form-control" placeholder="admin@sample.com"
					value="<?php echo htmlspecialchars($email); ?>" />
				<?php if ($email_error): ?>
					<p class="text-danger mt-1"><?php echo $email_error; ?></p>
				<?php endif ?>

				<input type="password" name="password" class="form-control mt-3" placeholder="*******" />
				<?php if ($password_error): ?>
					<p class="text-danger mt-1"><?php echo $password_error; ?></p>
				<?php endif ?>

				<button type="submit" class="btn btn-primary text-white mt-3 w-100">Login</button>
			</form>
			<a href="/store-keeper/register.php">Register</a>
		</div>
	</div>
</div>