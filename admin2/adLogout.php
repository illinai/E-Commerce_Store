<?php
session_start();

// If user confirmed logout, destroy session and redirect
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../main/index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logout</title>
  <link rel="stylesheet" href="css/adminDash.css" />
  <style>
    .logout-section {
      max-width: 500px;
      margin: 100px auto;
      padding: 30px;
      background: white;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    }

    .logout-section h2 {
      font-size: 1.8em;
      margin-bottom: 10px;
    }

    .logout-section p {
      font-size: 1em;
      margin-bottom: 20px;
      color: #555;
    }

    .logout-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .confirm-logout,
    .cancel-logout {
      padding: 10px 25px;
      font-size: 1em;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .confirm-logout {
      background-color: #ff6b6b;
      color: white;
    }

    .confirm-logout:hover {
      background-color: #ff4f4f;
    }

    .cancel-logout {
      background-color: #ccc;
      color: #333;
      text-decoration: none;
      display: inline-block;
      line-height: 2.5em;
    }

    .cancel-logout:hover {
      background-color: #bbb;
    }
  </style>
</head>
<body>
  <!-- Title Header Section -->
  <header class="header1">
    <h1>The Maker's Market</h1>
  </header>

  <!--Navigation Header Section-->
  <header class="header2">
            <!--Menu-->
            <div class="menuTab">
                <button class="menu-button" onclick="toggleMenu">
                    <img src="../main/icons/menu.png" alt="Menu">
                </button>
                <div class="menu-content">
                    <a href="adminDash.php">Dashboard</a>
                    <a href="adLogout.php">Logout</a>
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
                   <a href="adProfile.html"><img src="../main/icons/profile.png" alt="Profile"></a>
                </button>
            </div> 
        </header>

  <!-- Logout Confirmation Section -->
  <section class="logout-section">
    <h2>Are you sure you want to log out?</h2>
    <p>You will be redirected to the login page after logging out.</p>
    <form method="POST">
      <div class="logout-buttons">
        <button type="submit" name="confirm_logout" class="confirm-logout">Yes, Logout</button>
        <a href="adProfile.html" class="cancel-logout">Cancel</a>
      </div>
    </form>
  </section>

<!--  <script>
    function toggleMenu() {
      const sidebar = document.getElementById("sidebar");
      sidebar.style.left = (sidebar.style.left === "0px") ? "-250px" : "0px";
    }
  </script> -->
</body>
</html>