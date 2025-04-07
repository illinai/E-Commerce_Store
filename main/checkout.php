<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Secure Checkout</title>
  <link rel="stylesheet" href="css/homePage.css" />
  <style>
    .checkout-container {
      max-width: 600px;
      margin: 80px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      font-family: "Segoe UI", sans-serif;
    }

    .checkout-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .checkout-container label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 600;
    }

    .checkout-container input[type="text"],
    .checkout-container textarea,
    .checkout-container select {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
      font-size: 1em;
    }

    .checkout-container button {
      width: 100%;
      padding: 12px;
      background-color: #1e88e5;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 1.1em;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .checkout-container button:hover {
      background-color: #1669bb;
    }

    .checkout-total {
      font-weight: bold;
      font-size: 1.2em;
      text-align: right;
      margin-bottom: 15px;
    }

    @media (max-width: 600px) {
      .checkout-container {
        margin: 40px 20px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Checkout Form -->
  <div class="checkout-container">
    <h2>Secure Checkout</h2>
    <form action="../backend/process_checkout.php" method="POST">
      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" required />

      <label for="address">Shipping Address</label>
      <textarea id="address" name="address" rows="4" required></textarea>

      <label for="payment_method">Payment Method</label>
      <select id="payment_method" name="payment_method" required>
        <option value="">Select a method</option>
        <option value="credit_card">Credit Card</option>
        <option value="cod">Cash on Delivery</option>
      </select>

      <div class="checkout-total">
        Total: <span id="checkout-total">$0.00</span>
        <input type="hidden" name="total" id="total-input" />
      </div>

      <button type="submit">Place Order</button>
    </form>
  </div>

  <!-- Script to calculate cart total -->
  <script>
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    document.getElementById("checkout-total").textContent = `$${total.toFixed(2)}`;
    document.getElementById("total-input").value = `$${total.toFixed(2)}`;
  </script>
</body>
</html>