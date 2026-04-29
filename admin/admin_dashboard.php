<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="admin_style.css">
</head>

<body>

<!-- TOP BAR -->
<div class="topbar">
    <h2>Admin Panel</h2>
    <a href="admin_logout.php" class="logout-link"
       onclick="return confirm('Are you sure you want to logout?')">
        Logout
    </a>
</div>

<!-- MAIN DASHBOARD -->
<div class="dashboard">

    <h1>Dashboard</h1>
    <p class="subtitle">Control and manage your platform resources</p>

    <!-- SUCCESS MESSAGE -->
    <?php if(isset($_GET['msg'])){ ?>
        <p class="success-msg"><?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php } ?>

    <div class="admin-menu">

        <!-- Upload -->
        <div class="card">
            <h3>📤 Upload Resources</h3>
            <p>Add Roadmaps, Notes, Source Codes</p>
            <a href="upload_resources.php">
                <button type="button">Upload File</button>
            </a>
        </div>

        <!-- Manage -->
        <div class="card">
            <h3>📂 Manage Resources</h3>
            <p>Edit, delete, and organize files</p>
            <a href="manage_resources.php">
                <button type="button">Manage Files</button>
            </a>
        </div>

        <!-- Users -->
        <div class="card">
            <h3>👥 Manage Users</h3>
            <p>View registered users</p>
            <a href="manage_users.php">
                <button type="button">View Users</button>
            </a>
        </div>

        <!-- Future Feature -->
        <div class="card">
            <h3>💳 Payments</h3>
            <p>Track user purchases (coming soon)</p>
            <button disabled>Coming Soon</button>
        </div>

    </div>

</div>

</body>
</html>