<?php

session_start();
include '../backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../main/index.html");
    exit();
}

try{
    $userCount = 0;
    $orderCount = 0;
    //$reportCount = 0;

    // Get user count
    $stmt1 = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt1->execute();
    $userResult = $stmt1->get_result();

    if (!$userResult) {
        throw new Exception("Error getting user count: " . $conn->error);
    }
    $userCount = $userResult->fetch_assoc()['total'];

    // Get order count
    $stmt2 = $conn->prepare("SELECT COUNT(*) as total_o FROM orders");
    $stmt2->execute();
    $orderResult = $stmt2->get_result();

    if (!$orderResult) {
        throw new Exception("Error getting order count: " . $conn->error);
    }
    $orderCount = $orderResult->fetch_assoc()['total_o'];
}catch(Exception $e){
    error_log("Database error: " . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="css/adminDash.css">
    </head>
    <body>

        <!--Title Header Section-->
        <header class="header1">
            <div id="container">
                <div id="shopName">
                    <h1>The Maker's Market</h1>
                </div>
            </div>
        </header>
        <!---->

        <!--Navigation Header Section-->
        <header class="header2">
            <!--Menu-->
            <div class="menuTab">
                <button class="menu-button" onclick="toggleMenu">
                    <img src="../main/icons/menu.png" alt="Menu">
                </button>
                <div class="menu-content">
                    <a href="adminDash.html">Dashboard</a>
                    <a href="#">Logout</a>
                </div>
            </div>
            <!--Search Bar-->
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search...">
                <button id="searchButton">Search</button>
                <!--<div class="search-results"></div>-->
            </div>
            <div class="right-buttons">
                <!--Profile-->
                <button class="profile-button">
                   <img src="../main/icons/profile.png" alt="Profile">
                </button>
            </div> 
        </header>

        <div class="main">
            <div class="dashCards">
                <div class="numUsers">   
                    <h3>Total Users:</h3>   
                    <p><?php echo $userCount; ?></p>
                </div>
                <div class="Orders">
                    <h3>Total Orders:</h3>
                    <p><?php echo $orderCount; ?></p> 
                </div>
                <div class="alerts">
                    <h3>Reports:</h3>
                    <p>0</p> <!--number should be actual number after database creation-->
                </div>
            </div>
            <div class="repList"> 
                <p>Report Number: Username: Description</p> <!--format of reports-->
                <!--Reports will show up as a list once database is created-->
            </div>
        </div>

        <!-- Results Section -->
        <div class="mainR">
            <div id="resultsContainer">

            </div>
        </div>

        <!--<script src="scripts/adminDash.js"></script>-->
    </body>
</html>