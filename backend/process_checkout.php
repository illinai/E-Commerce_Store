<?php
session_start();
include 'config.php';

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    header("Location: ../main/index.html");
    exit();
}

// Add error logging for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];
$fullname = $_POST['fullname'];
$address = $_POST['address'];
$payment_method = $_POST['payment_method'];
$total = floatval(preg_replace('/[^0-9.]/', '', $_POST['total']));

// Get cart data from the form submission
$cart = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : [];

// Start transaction
$conn->begin_transaction();

try {
    // For each unique seller in the cart, create a separate order
    $sellerOrders = [];
    
    // Group cart items by seller
    foreach ($cart as $item) {
        $product_id = $item['id'];
        
        // Get seller ID for this product
        $stmt = $conn->prepare("SELECT seller_id FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $seller_id = $row['seller_id'];
            
            // Initialize seller's order if not exists
            if (!isset($sellerOrders[$seller_id])) {
                $sellerOrders[$seller_id] = [
                    'items' => [],
                    'total' => 0
                ];
            }
            
            // Add item to seller's order
            $sellerOrders[$seller_id]['items'][] = $item;
            $sellerOrders[$seller_id]['total'] += $item['price'] * $item['quantity'];
        }
        $stmt->close();
    }
    
    // Create an order for each seller
    foreach ($sellerOrders as $seller_id => $order) {
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, seller_id, total) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $user_id, $seller_id, $order['total']);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to create order: " . $stmt->error);
        }
        
        $order_id = $stmt->insert_id;
        $stmt->close();
        
        // Insert order items
        foreach ($order['items'] as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to add order item: " . $stmt->error);
            }
            $stmt->close();
        }
    }
    
    // Commit the transaction
    $conn->commit();
    
    // Clear session data if needed
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
    
    // Redirect to confirmation page
    header("Location: ../main/confirmation.html");
    exit();
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    
    // Log error
    error_log("Checkout error: " . $e->getMessage());
    
    // For debugging purposes, you can uncomment this to see the error
    // echo "Error: " . $e->getMessage();
    
    // Redirect to error page
    header("Location: ../main/checkout.php?error=1");
    exit();
}
?>