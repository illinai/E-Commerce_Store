<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // redirect to login
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link rel="stylesheet" href="css/homePage.css">
</head>
<body>
  <h2>Secure Checkout</h2>
  <form action="../backend/process_checkout.php" method="POST">
    <label>Full Name</label>
    <input type="text" name="fullname" required>

    <label>Shipping Address</label>
    <textarea name="address" required></textarea>

    <label>Payment Method</label>
    <select name="payment_method" required>
      <option value="credit_card">Credit Card</option>
      <option value="cod">Cash on Delivery</option>
    </select>

    <label>Total Amount</label>
    <input type="text" id="checkout-total" name="total" readonly>

    <button type="submit">Place Order</button>
  </form>

  <script>
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    document.getElementById("checkout-total").value = `$${total.toFixed(2)}`;
  </script>
</body>
</html>