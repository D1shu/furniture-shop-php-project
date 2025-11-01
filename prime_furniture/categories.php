<?php
require 'db.php';
if (empty($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$msg = ''; $msg_type = 'success';

// handle delete - CASCADE DELETE PRODUCTS
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // First, get and delete category image
    $r = $mysqli->query("SELECT image FROM categories WHERE id={$id}");
    if ($r && $row = $r->fetch_assoc()) {
        if (!empty($row['image']) && file_exists($row['image'])) @unlink($row['image']);
    }
    
    // Get all products in this category and delete their images
    $products = $mysqli->query("SELECT image FROM products WHERE category_id={$id}");
    if ($products) {
        while ($prod = $products->fetch_assoc()) {
            if (!empty($prod['image']) && file_exists($prod['image'])) {
                @unlink($prod['image']);
            }
        }
    }
    
    // Delete all products in this category
    $mysqli->query("DELETE FROM products WHERE category_id={$id}");
    
    // Delete the category
    $mysqli->query("DELETE FROM categories WHERE id={$id}");
    
    $msg = "Category and all its products deleted successfully."; 
    $msg_type = 'success';
}

// handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int)$_POST['update_id'];
    $name = trim($_POST['name']);

    if ($name === '') { 
        $msg="Name required."; $msg_type='error'; 
    } elseif (is_numeric($name)) { 
        $msg="Category name cannot be only numbers!"; $msg_type='error'; 
    } else {
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];
            if (!in_array($ext, $allowed)) {
                $msg = "Only JPG, JPEG, PNG files allowed."; $msg_type='error';
            } elseif ($_FILES['image']['size'] > 20*1024*1024) {
                $msg = "File too large. Max 20MB allowed."; $msg_type='error';
            } else {
                $target = 'uploads/' . time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
                $r = $mysqli->query("SELECT image FROM categories WHERE id={$id}");
                if ($r && $row = $r->fetch_assoc()) { 
                    if (!empty($row['image']) && file_exists($row['image'])) @unlink($row['image']); 
                }
                $stmt = $mysqli->prepare("UPDATE categories SET name=?, image=? WHERE id=?");
                $stmt->bind_param('ssi', $name, $target, $id);
                $stmt->execute(); $stmt->close();
                $msg = "Category updated."; $msg_type='success';
            }
        } else {
            $stmt = $mysqli->prepare("UPDATE categories SET name=? WHERE id=?");
            $stmt->bind_param('si', $name, $id);
            $stmt->execute(); $stmt->close();
            $msg = "Category updated."; $msg_type='success';
        }
    }
}

// handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    if ($name === '') { $msg="Name required."; $msg_type='error'; }
    elseif (is_numeric($name)) { $msg="Category name cannot be only numbers!"; $msg_type='error'; }
    else {
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];
            if (!in_array($ext, $allowed)) {
                $msg = "Only JPG, JPEG, PNG files allowed."; $msg_type='error';
            } elseif ($_FILES['image']['size'] > 20*1024*1024) {
                $msg = "File too large. Max 20MB allowed."; $msg_type='error';
            } else {
                $imagePath = 'uploads/' . time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
                $stmt = $mysqli->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
                $stmt->bind_param('ss', $name, $imagePath);
                $stmt->execute(); $stmt->close();
                $msg = "Category added."; $msg_type='success';
            }
        } else {
            $stmt = $mysqli->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
            $stmt->bind_param('ss', $name, $imagePath);
            $stmt->execute(); $stmt->close();
            $msg = "Category added."; $msg_type='success';
        }
    }
}

// search filters
$where = "1";
if (!empty($_GET['search_name'])) {
    $s = $mysqli->real_escape_string($_GET['search_name']);
    $where .= " AND name LIKE '%$s%'";
}
if (!empty($_GET['search_category'])) {
    $s2 = (int)$_GET['search_category'];
    $where .= " AND id=$s2";
}

$res = $mysqli->query("SELECT * FROM categories WHERE $where ORDER BY id DESC");
$allCats = $mysqli->query("SELECT id,name FROM categories ORDER BY name ASC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Categories</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Background */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #e9f7ef, #d4edda);
      color: #333;
    }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>Categories</h2>

  <?php if ($msg): ?>
    <div class="msg <?php echo $msg_type==='success'?'success':'error'; ?>">
      <?php echo htmlspecialchars($msg); ?>
    </div>
  <?php endif; ?>

  <!-- Search form -->
  <form method="get" style="margin-bottom:20px; display:flex; gap:10px;">
    <input type="text" name="search_name" placeholder="Search by name" 
           value="<?php echo htmlspecialchars($_GET['search_name'] ?? ''); ?>" 
           style="flex:2; padding:8px;">
    <select name="search_category" style="flex:1; padding:8px;">
      <option value="">--Select Category--</option>
      <?php while($c = $allCats->fetch_assoc()): ?>
        <option value="<?php echo $c['id']; ?>" 
          <?php if(!empty($_GET['search_category']) && $_GET['search_category']==$c['id']) echo 'selected'; ?>>
          <?php echo htmlspecialchars($c['name']); ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button class="btn" type="submit">Search</button>
    <a href="categories.php" style="align-self:center;">Reset</a>
  </form>

  <!-- Add form -->
  <form method="post" enctype="multipart/form-data" class="block">
    <input type="hidden" name="add" value="1">
    <input type="text" name="name" placeholder="Category name" required>
    <input type="file" name="image" accept="image/*">
    <div style="text-align:left;"><button class="btn" type="submit">Add Category</button></div>
  </form>

  <!-- List -->
  <table class="table">
    <tr><th>ID</th><th>Name</th><th>Image</th><th>Actions</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']): ?>
<!-- Edit mode -->
<form method="post" enctype="multipart/form-data">
  <td><?php echo $row['id']; ?>
    <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
  </td>
  <td>
    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required
           style="padding:6px; width:90%; border:1px solid #ccc; border-radius:4px;">
  </td>
  <td>
    <?php if (!empty($row['image'])): ?>
      <img class="thumb" src="<?php echo htmlspecialchars($row['image']); ?>" alt="img"
           style="max-width:50px; max-height:50px; margin-right:8px; vertical-align:middle; border:1px solid #ddd; border-radius:6px; padding:3px; background:#fff;">
    <?php endif; ?>
    <input type="file" name="image" accept="image/*" 
           style="padding:4px; border:1px solid #ccc; border-radius:4px;">
  </td>
  <td class="actions" style="display:flex; gap:10px; align-items:center;">
    <button class="btn" type="submit" 
            style="background:#00695c; color:#fff; padding:6px 14px; border:none; border-radius:6px; cursor:pointer;">
      Save
    </button>
    <a href="categories.php" 
       style="color:#b71c1c; font-weight:bold; text-decoration:none; padding:6px 12px; border-radius:6px; background:#fce4ec;">
      Cancel
    </a>
  </td>
</form>
        <?php else: ?>
          <!-- View mode -->
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td>
            <?php if (!empty($row['image'])): ?>
              <img class="thumb" src="<?php echo htmlspecialchars($row['image']); ?>" alt="img">
            <?php endif; ?>
          </td>
          <td class="actions">
            <a class="btn" href="categories.php?edit=<?php echo $row['id']; ?>">Edit</a>
            <a class="delete" href="categories.php?delete=<?php echo $row['id']; ?>" 
               onclick="return confirm('Delete this category? All products in this category will also be deleted!');">Delete</a>
          </td>
        <?php endif; ?>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>