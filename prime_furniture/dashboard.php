<?php
require 'db.php';
if (empty($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>

body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #e9f7ef, #d4edda);
      color: #333;
    }

    /* centre dashboard and style cards */
    .dashboard-container {
      max-width: 1100px;
      margin: 50px auto;
      padding: 30px 20px;
      text-align: center;
    }

    .dashboard-container h2 {
      margin-bottom: 10px;
      font-size: 35px;
      font-weight: bold;
      color: #145A32;
    }

    .dashboard-container p {
      font-size: 22px;
      color: #2C3E50;
      margin-bottom: 30px;
    }

    .dashboard-stats {
      display: flex;
      justify-content: center;
      gap: 25px;
      flex-wrap: wrap;
      margin-top: 20px;
    }

    .dashboard-stats .stat {
      flex: 1;
      min-width: 200px;
      background: #fff;
      border-top: 6px solid #114b40;
      border-radius: 12px;
      padding: 50px 80px;
      text-align: center;
      box-shadow: 0 5px 12px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
    }
    
    .dashboard-stats .stat strong {
      display: block;
      font-size: 35px;
      color: #114b40;
    }
    .dashboard-stats .stat span {
      color: #666;
      font-size: px;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="dashboard-container">
  <h2>Dashboard</h2>
  <p>Welcome, Admin/<?php echo htmlspecialchars($_SESSION['admin_email']); ?>.</p>

  <?php
    // show basic counts
    $counts = [];
    foreach (['categories','products'] as $t) {
      $r = $mysqli->query("SELECT COUNT(*) c FROM {$t}");
      $counts[$t] = $r ? (int)$r->fetch_assoc()['c'] : 0;
    }
  ?>

  <div class="dashboard-stats">
    <div class="stat">
      <strong><?php echo $counts['categories']; ?></strong>
      <span>Categories</span>
    </div>
    <div class="stat">
      <strong><?php echo $counts['products']; ?></strong>
      <span>Products</span>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
