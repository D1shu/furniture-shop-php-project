<?php
// user_home.php - User side (Under Development)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrimeFurniture - User Panel</title>
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

        header {
            background: #114b40;
            color: #fff;
            padding: 18px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo h1 {
            font-size: 28px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
        }

        .back-btn {
            padding: 8px 20px;
            background: #fff;
            color: #114b40;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #0d3b31;
            color: #fff;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .dev-box {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 50px 35px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .dev-box h2 {
            font-size: 32px;
            color: #114b40;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .dev-box p {
            font-size: 16px;
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .features {
            text-align: left;
            margin: 25px 0;
        }

        .features ul {
            list-style: none;
            padding: 0;
        }

        .features li {
            padding: 8px 0;
            color: #555;
            font-size: 15px;
            padding-left: 25px;
            position: relative;
        }

        .features li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #114b40;
            font-size: 20px;
        }

        footer {
            background: #0d4f45;
            text-align: center;
            padding: 15px 0;
            color: #fff;
            margin-top: auto;
        }

        footer p {
            margin: 0;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 12px;
            }

            .logo h1 {
                font-size: 24px;
            }

            .dev-box {
                padding: 35px 25px;
            }

            .dev-box h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h1 onclick="window.location.href='index.php'">PrimeFurniture</h1>
            </div>
            <a href="index.php" class="back-btn">← Back</a>
        </div>
    </header>

    <div class="main-content">
        <div class="dev-box">
            <h2>Under Development</h2>
            <p>User panel is currently being developed. Please check back soon!</p>
            
            <div class="features">
                <ul>
                    <li>Product Browsing</li>
                    <li>Shopping Cart</li>
                    <li>Order Management</li>
                    <li>User Profile</li>
                </ul>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Prime Furniture</p>
    </footer>
</body>
</html>