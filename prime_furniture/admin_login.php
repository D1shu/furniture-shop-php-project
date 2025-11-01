<?php
session_start(); 
require 'db.php';

// If already logged in -> dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = "Email and password required.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, username, email, password FROM admins WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            // NOTE: plain text password in this sample. Use password_hash in production.
            if ($row['password'] === $password) {
                // Save into session
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_username'] = $row['username']; 

                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Admin not found.";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="style.css">
  
</head>
<body class="login-page">
  <header>
    <div class="top">
      <h1 >Admin Panel</h1>
      <nav><!-- empty for login page --></nav>
    </div>
  </header>

  <div class="container" style="max-width:420px; margin-top:38px;">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
      <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" class="block">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <div style="text-align:center">
        <button class="btn" type="submit">Login</button>
      </div>
    </form>
   
  </div>

  <footer style="margin-top:350px; text-align:center; padding:14px 0; color:#fff;">
    &copy; <?php echo date('Y'); ?> Prime Furniture
  </footer>
</body>
</html>
