<?php
# Log out user if they are not logged in
if (!isset($_COOKIE['STORE_KEEPER_USER'])) {
    header('Location: /store-keeper/index.php');
    exit();
}
?>
