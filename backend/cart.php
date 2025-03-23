<?php
session_start();

$sampleProducts = [
    1 => ['name' => 'Handmade Earrings', 'price' => 25],
    2 => ['name' => 'Wooden Home Decor', 'price' => 40],
    3 => ['name' => 'Custom Art Print', 'price' => 30],
];

$cartItems = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/buyers.css">
</head>
<body>
    <h1>Your Cart</h1>
    <?php if (!empty($cartItems)): ?>
        <ul>
            <?php foreach ($cartItems as $id): ?>
                <?php if (isset($sampleProducts[$id])): ?>
                    <li><?= htmlspecialchars($sampleProducts[$id]['name']) ?> - $<?= $sampleProducts[$id]['price'] ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>