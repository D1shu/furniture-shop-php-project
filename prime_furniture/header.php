<?php
// header.php - UI only (no redirects). Include after require 'db.php' in pages.
?>
<header>
  <div class="top">
    <h1>Prime Furniture - Admin</h1>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="categories.php">Categories</a>
      <a href="products.php">Products</a>
      <a href="product_details.php">Product Details</a>
<?php if (!empty($_SESSION['admin_id'])): ?>
<span style="color:#ffeb3b; margin-left:12px; font-weight:bold;">
  Logged in as: <?php echo htmlspecialchars($_SESSION['admin_username'] ?? ''); ?>
</span>

  <a href="logout.php" style="margin-left:12px; color:#FF0000;">Logout</a>
<?php endif; ?>

    </nav>
  </div>
</header>
