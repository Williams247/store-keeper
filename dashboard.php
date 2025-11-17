<?php
include "./includes/header.php";
include "./auth/is-logged-in.php";

$user_id = $_COOKIE['STORE_KEEPER_USER'] ?? null;

# Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # Delete item
    if (isset($_POST['item_id'])) {
        $item_id = trim($_POST['item_id']);
        if (!empty($item_id)) {
            $sql = "DELETE FROM items WHERE id = $1 AND user_id = $2";
            $result = pg_query_params($conn, $sql, [$item_id, $user_id]);
        }
    }

    # Update item
    if (isset($_POST['update_id_input'])) {
        $update_id = trim($_POST['update_id_input']);
        $item_name = trim($_POST['item']);
        $quantity = trim($_POST['quantity']);
        $price = trim($_POST['price']);

        if (!empty($update_id)) {
            $sql = "UPDATE items SET item = $1, quantity = $2, price = $3 WHERE id = $4 AND user_id = $5";
            $result = pg_query_params($conn, $sql, [$item_name, $quantity, $price, $update_id, $user_id]);
            echo "<script>alert('" . ($result ? 'Item updated successfully' : 'Failed to update item') . "');</script>";
        }
    }

    # Redirect to prevent form resubmission
    header('Location: /store-keeper/dashboard.php?current_page=dashboard');
    exit();
}

# Fetch all items
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
                                    <button type="button" class="btn btn-primary mt-3 edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop" data-id="<?= $item['id'] ?>"
                                        data-item="<?= htmlspecialchars($item['item'], ENT_QUOTES) ?>"
                                        data-quantity="<?= $item['quantity'] ?>" data-price="<?= $item['price'] ?>">
                                        Edit
                                    </button>
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

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <label>Enter your item</label>
                    <input type="text" name="item" class="form-control mb-3" id="item" required />

                    <label>Enter your quantity</label>
                    <input type="text" name="quantity" class="form-control mt-1 mb-3" id="quantity" required />

                    <label>Enter your price</label>
                    <input type="text" name="price" class="form-control mt-1" id="price" required />

                    <input type="hidden" name="update_id_input" id="update_id_input" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('update_id_input').value = this.dataset.id;
            document.getElementById('item').value = this.dataset.item;
            document.getElementById('quantity').value = this.dataset.quantity;
            document.getElementById('price').value = this.dataset.price;
        });
    });
</script>
