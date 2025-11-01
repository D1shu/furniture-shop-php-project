<?php
// index.php - Landing page with products
require 'db.php';

// Fetch all products
$res = $mysqli->query("SELECT p.*, c.name as cname FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrimeFurniture - Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e9f7ef, #d4edda);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            background: #114b40;
            color: #fff;
            padding: 18px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo h1 {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
        }

        .header-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-header {
            padding: 10px 24px;
            border: 2px solid #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-admin {
            background: #fff;
            color: #114b40;
        }

        .btn-admin:hover {
            background: #0d3b31;
            color: #fff;
            border-color: #0d3b31;
        }

        .btn-user {
            background: transparent;
            color: #fff;
        }

        .btn-user:hover {
            background: #fff;
            color: #114b40;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-title h2 {
            font-size: 36px;
            color: #114b40;
            margin-bottom: 10px;
        }

        .page-title p {
            font-size: 18px;
            color: #666;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .product-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #f5f5f5;
        }

        .product-info {
            padding: 18px;
        }

        .product-name {
            font-size: 20px;
            font-weight: 600;
            color: #114b40;
            margin-bottom: 8px;
        }

        .product-category {
            font-size: 14px;
            color: #888;
            margin-bottom: 12px;
        }

        .product-price {
            font-size: 22px;
            font-weight: 700;
            color: #1D8348;
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            font-size: 18px;
        }

        /* Footer */
        footer {
            background: #0d4f45;
            text-align: center;
            padding: 20px 0;
            color: #fff;
            margin-top: auto;
        }

        footer p {
            margin: 6px 0;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .logo h1 {
                font-size: 26px;
            }

            .page-title h2 {
                font-size: 28px;
            }

            .page-title p {
                font-size: 16px;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }

            .product-image {
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 15px 20px;
            }

            .product-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .product-info {
                padding: 15px;
            }

            .product-name {
                font-size: 18px;
            }

            .product-price {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo">
                <h1>PrimeFurniture</h1>
            </div>
            <div class="header-buttons">
                <a href="admin_login.php" class="btn-header btn-admin">Admin Login</a>
                <a href="user_home.php" class="btn-header btn-user">User Login</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="page-title">
                <h2>Our Products</h2>
                <p>Explore our collection of premium furniture</p>
            </div>

            <?php if ($res && $res->num_rows > 0): ?>
                <div class="product-grid">
                    <?php while($row = $res->fetch_assoc()): ?>
                        <div class="product-card">
                            <?php if(!empty($row['image'])): ?>
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image">
                            <?php else: ?>
                                <div class="product-image" style="display:flex; align-items:center; justify-content:center; color:#999;">No Image</div>
                            <?php endif; ?>
                            
                            <div class="product-info">
                                <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="product-category">
                                    <?php echo !empty($row['cname']) ? htmlspecialchars($row['cname']) : 'Uncategorized'; ?>
                                </div>
                                <div class="product-price">₹<?php echo number_format($row['price'], 2); ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <p>No products available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Prime Furniture</p>
    </footer>
</body>
</html>