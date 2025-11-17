<?php
# Menu urls and labels
$menus = [
    ["label" => "Dashboard", "url" => "/store-keeper/dashboard.php?current_page=dashboard"],
    ["label" => "Create Record", "url" => "/store-keeper/create-record.php?current_page=create_record"]
];

# Logout user if logout button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    # If logout request body is sent, redirect user to login page and reset cookie
    if (isset($_GET['logout']) && !empty($_GET['logout']) && $_GET['logout'] === 'true') {
        if ($_COOKIE['STORE_KEEPER_USER']) {
            setcookie("STORE_KEEPER_USER", "", time() - 3600);
            header("Location: /store-keeper/index.php");
            exit();
        }
    }
}
?>

<div class="w-25 bg-secondary px-3 py-1 d-flex flex-column justify-content-between" style="height: 100vh">
    <div>
        <p class="text-left mt-3 fw-bold text-white">PHP Store Keeper</p>
        <ul class="list-group">

            <?php foreach ($menus as $menu): 
                # Convert label to lowercase_underscore for comparison
                $label_key = strtolower(str_replace(' ', '_', $menu['label']));
            ?>
                <li class="list-group py-2">
                    <a href="<?= $menu['url']; ?>"
                       class="<?= ($_GET['current_page'] ?? '') === $label_key 
                            ? 'fw-bold text-white text-decoration-none bg-primary rounded px-3 py-2' 
                            : 'text-white text-decoration-none rounded px-3 py-2' ?>">
                        <?= $menu['label']; ?>
                    </a>
                </li>
            <?php endforeach; ?>

        </ul>
    </div>
    
   <a
    href="?logout=true"
    class='text-white text-decoration-none rounded px-3 py-2 w-100'
    style="cursor: pointer;"
   >
    Logout
   </a>
</div>
