<?php
include "./includes/header.php";
include "./auth/is-logged-in.php";

$user_id = $_COOKIE['STORE_KEEPER_USER'] ?? null;

# Handle POST request to delete store item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = trim($_POST['item_id']);

    if (!empty($item_id)) {
        $sql = "DELETE FROM items WHERE id = $1";
        $result = pg_query_params($conn, $sql, [$item_id]);

        if ($result) {
            echo "<script>alert('Item deleted successfully');</script>";
        } else {
            echo "<script>alert('Failed to delete item');</script>";
        }
    }

    # Redirect to refresh the list and prevent form resubmission
    header('Location: /store-keeper/dashboard.php?current_page=dashboard');
    exit();
}

# Fetch all items for the logged-in user
$sql = "SELECT * FROM items WHERE user_id = $1 ORDER BY id ASC";
$result = pg_query_params($conn, $sql, [$user_id]);

$items = [];
if ($result && pg_num_rows($result) > 0) {
    $items = pg_fetch_all($result);
}
?>

<div class="d-flex">
    <?php include './includes/sidebar.php'; ?>

    <div class="w-75 bg-body-secondary px-3 py-3">
        <div class="mx-auto mt-4 w-75">

            <?php if (empty($items)): ?>
                <p class="text-muted">No items found.</p>
            <?php else: ?>
                <p class="fw-bold fs-5">Your Stock</p>
                <ul class="list-group">
                    <?php foreach ($items as $index => $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <?= $index + 1 ?>. <strong><?= htmlspecialchars($item['item']) ?></strong>
                                <span class="ms-3">Qty: <?= htmlspecialchars($item['quantity']) ?> | Price:
                                    <?= htmlspecialchars($item['price']) ?></span>
                            </div>
                            <div class="d-flex gap-2 mt-2 mt-md-0">
                                <div>
                                    <button class="btn btn-primary mt-3">Edit</button>
                                </div>

                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger mt-3">Delete</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </div>
    </div>
</div>