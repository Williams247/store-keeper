<?php
$menus = [
  ["label" => "Dashboard", "url" => "/store-keeper/dashboard.php?current_page=Dashboard"],
  ["label" => "Create Record", "url" => "/store-keeper/create-record.php?current_page=Create Record"]
];
?>

<div class="w-25 bg-secondary px-3 py-1" style="height: 100vh">
    <p class="text-left mt-3 fw-bold text-white">PHP Store Keeper</p>

    <ul class="list-group">
        <?php foreach ($menus as $menu): ?>
            <li class="list-group py-2">
                <a
                    href="<?= $menu['url']; ?>"
                    class="<?= $_GET['current_page'] == $menu['label'] ? ' fw-bold text-white text-decoration-none bg-primary rounded px-3 py-2' : 'text-white text-decoration-none rounded px-3 py-2' ?>"
                >
                    <?php echo $menu['label'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
