<?php
session_start();
require 'db.php';
if (empty($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$res = $mysqli->query("SELECT p.*, c.name as cname FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Product Details</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Background */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #e9f7ef, #d4edda);
      color: #333;
    }

    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 20px;
    }

    h2 {
      color: #145A32;
      margin-bottom: 25px;
      font-size: 28px;
    }

    /* Product grid */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr); /* 2 columns */
      gap: 20px;
    }

    /* Product card */
    .product-card {
      background: #fff;
      border: 1px solid #e8f2ea;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
    }

    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 12px;
    }

    .product-card h3 {
      color: #114b40;
      margin: 8px 0;
      font-size: 20px;
    }

    .product-card .category {
      color: #666;
      font-size: 14px;
      margin-bottom: 6px;
    }

    .product-card .price {
      font-weight: bold;
      color: #1D8348;
      font-size: 16px;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
  <h2>All Products</h2>
  <div class="product-grid">
    <?php while($row = $res->fetch_assoc()): ?>
      <div class="product-card">
        <?php if(!empty($row['image'])): ?>
          <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <div class="category">Category: <?php echo htmlspecialchars($row['cname']); ?></div>
        <div class="price">Price: ₹<?php echo number_format($row['price'],2); ?></div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
