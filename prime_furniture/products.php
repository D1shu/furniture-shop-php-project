<?php
require 'db.php';
if (empty($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$msg = ''; $msg_type = 'success';

// --- DELETE ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $r = $mysqli->query("SELECT image FROM products WHERE id={$id}");
    if ($r && $row = $r->fetch_assoc()) {
        if (!empty($row['image']) && file_exists($row['image'])) @unlink($row['image']);
    }
    $mysqli->query("DELETE FROM products WHERE id={$id}");
    $msg = "Product deleted."; $msg_type = 'success';
}

// --- UPDATE (inline) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int)$_POST['update_id'];
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $category = isset($_POST['category']) ? (int)$_POST['category'] : null;

    if ($name === '') { $msg="Name required."; $msg_type='error'; }
    elseif (is_numeric($name)) { $msg="Product name cannot be only numbers!"; $msg_type='error'; }
    elseif (!is_numeric($price)) { $msg="Price must be numeric."; $msg_type='error'; }
    elseif ($price < 0) { $msg="Price cannot be negative."; $msg_type='error'; }
    else {
        // file validation if provided
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];
            if (!in_array($ext, $allowed)) {
                $msg = "Only JPG, JPEG, PNG allowed."; $msg_type='error';
            } elseif ($_FILES['image']['size'] > 20*1024*1024) {
                $msg = "File size must be below 20MB."; $msg_type='error';
            } else {
                $target = 'uploads/' . time() . '_' . basename($_FILES['image']['name']);
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $msg = "Failed to upload image."; $msg_type='error';
                } else {
                    // remove old image
                    $r = $mysqli->query("SELECT image FROM products WHERE id={$id}");
                    if ($r && $row = $r->fetch_assoc()) { if (!empty($row['image']) && file_exists($row['image'])) @unlink($row['image']); }

                    $stmt = $mysqli->prepare("UPDATE products SET name=?, price=?, category_id=?, image=? WHERE id=?");
                    $stmt->bind_param('sdisi', $name, $price, $category, $target, $id);
                    $stmt->execute(); $stmt->close();

                    $msg = "Product updated."; $msg_type='success';
                }
            }
        } else {
            $stmt = $mysqli->prepare("UPDATE products SET name=?, price=?, category_id=? WHERE id=?");
            $stmt->bind_param('sdii', $name, $price, $category, $id);
            $stmt->execute(); $stmt->close();
            $msg = "Product updated."; $msg_type='success';
        }
    }
}

// --- ADD ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $category = isset($_POST['category']) ? (int)$_POST['category'] : null;

    if ($name === '') { $msg="Name required."; $msg_type='error'; }
    elseif (is_numeric($name)) { $msg="Product name cannot be only numbers!"; $msg_type='error'; }
    elseif (!is_numeric($price)) { $msg="Price must be numeric."; $msg_type='error'; }
    elseif ($price < 0) { $msg="Price cannot be negative."; $msg_type='error'; }
    else {
        $imagePath = '';
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];
            if (!in_array($ext, $allowed)) {
                $msg = "Only JPG, JPEG, PNG allowed."; $msg_type='error';
            } elseif ($_FILES['image']['size'] > 20*1024*1024) {
                $msg = "File size must be below 20MB."; $msg_type='error';
            } else {
                $imagePath = 'uploads/' . time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            }
        }
        if ($msg_type !== 'error') {
            $stmt = $mysqli->prepare("INSERT INTO products (name, price, category_id, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('sdis', $name, $price, $category, $imagePath);
            $stmt->execute(); $stmt->close();
            $msg = "Product added."; $msg_type='success';
        }
    }
}

// fetch categories into array (for forms)
$cats_res = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = [];
while ($c = $cats_res->fetch_assoc()) { $categories[$c['id']] = $c['name']; }

// fetch categories again for search dropdown
$allCats = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");

// build where clause for search
$where = "1";
if (!empty($_GET['search_name'])) {
    $name = '%' . $mysqli->real_escape_string($_GET['search_name']) . '%';
    $where .= " AND p.name LIKE '{$name}'";
}
if (!empty($_GET['search_category'])) {
    $cat = (int)$_GET['search_category'];
    $where .= " AND p.category_id = {$cat}";
}

// fetch products (for listing)
$res = $mysqli->query("SELECT p.*, c.name as cname FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE {$where} ORDER BY p.id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Products</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #e9f7ef, #d4edda);
      color: #333;
    }
.table td input[type="text"],
.table td input[type="number"],
.table td select,
.table td input[type="file"] {
  width: 95%;
  box-sizing: border-box;
  padding: 8px 10px;
  margin: 2px 0;
  border: 1px solid #c9dfd0;
  border-radius: 6px;
  font-size: 14px;
  vertical-align: middle;
}
.table td img.thumb {
  display: inline-block;
  margin-right: 8px;
  vertical-align: middle;
}
.table td input[type="file"] {
  display: inline-block;
  vertical-align: middle;
  width: auto;
}
.table { width:100%; border-collapse:collapse; }
.table th { background:#114b40; color:#fff; padding:10px; text-align:left; }
.table td { padding:12px; vertical-align:middle; border-top:1px solid #e8f4ee; }
input[type="text"], input[type="number"] {
  padding:7px 10px; border:1px solid #c9dfd0; border-radius:6px; font-size:14px;
}
input[type="text"] { width:320px; }
input[type="number"] { width:140px; }
select { padding:7px 10px; border:1px solid #c9dfd0; border-radius:6px; width:180px; }
input[type="file"] { display:inline-block; margin-left:10px; }
.thumb { max-width:70px; max-height:70px; display:block; margin-bottom:6px; border-radius:6px; border:1px solid #eee; padding:4px; background:#fff; }
td.actions { text-align:center; white-space:nowrap; }
.btn-edit { background:none; color:#0b6b53; text-decoration:none; font-weight:700; padding:6px 10px; border-radius:6px; display:inline-block; }
.btn-delete { color:#b91c1c; text-decoration:none; font-weight:700; margin-left:8px; }
.btn-save { background:#0b6b53; color:#fff; padding:8px 14px; border-radius:7px; border:none; cursor:pointer; display:inline-block; }
.btn-cancel { background:#fde7ea; color:#b91c1c; padding:8px 12px; border-radius:7px; text-decoration:none; display:inline-block; margin-left:8px; }
@media (max-width:800px) {
  input[type="text"] { width: 180px; }
  select { width:140px; }
}
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>Products</h2>

  <?php if ($msg): ?>
    <div class="msg <?php echo $msg_type==='success'?'success':'error'; ?>"><?php echo htmlspecialchars($msg); ?></div>
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
    <button class="btn-save" type="submit">Search</button>
    <a href="products.php" class="btn-cancel" style="align-self:center;">Clear</a>
  </form>

  <!-- Add form -->
  <form method="post" enctype="multipart/form-data" class="block" style="margin-bottom:18px;">
    <input type="hidden" name="add" value="1">
    <input type="text" name="name" placeholder="Product name" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <select name="category" required>
      <option value="">-- Select Category --</option>
      <?php foreach ($categories as $id=>$n): ?>
        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($n); ?></option>
      <?php endforeach; ?>
    </select>
    <input type="file" name="image" accept="image/*">
    <div style="text-align:left;"><button class="btn-save" type="submit">Add Product</button></div>
  </form>

  <!-- List -->
  <table class="table">
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Image</th><th style="text-align:center;">Actions</th></tr>

    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']): ?>
          <!-- EDIT MODE -->
          <form method="post" enctype="multipart/form-data">
            <td>
              <?php echo $row['id']; ?>
              <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
            </td>
            <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required></td>
            <td><input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required></td>
            <td>
              <select name="category" required>
                <option value="">-- Select --</option>
                <?php foreach ($categories as $cid=>$cname): ?>
                  <option value="<?php echo $cid; ?>" <?php if ($row['category_id']==$cid) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cname); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </td>
            <td>
              <?php if (!empty($row['image'])): ?>
                <img class="thumb" src="<?php echo htmlspecialchars($row['image']); ?>" alt="img">
              <?php endif; ?>
              <input type="file" name="image" accept="image/*">
            </td>
            <td class="actions">
              <button class="btn-save" type="submit">Save</button>
              <a class="btn-cancel" href="products.php">Cancel</a>
            </td>
          </form>

        <?php else: ?>
          <!-- VIEW MODE -->
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['price']); ?></td>
          <td><?php echo htmlspecialchars($row['cname'] ?? ''); ?></td>
          <td>
            <?php if (!empty($row['image'])): ?>
              <img class="thumb" src="<?php echo htmlspecialchars($row['image']); ?>" alt="img">
            <?php endif; ?>
          </td>
          <td class="actions">
            <a class="btn-edit" href="products.php?edit=<?php echo $row['id']; ?>">Edit</a>
            <a class="btn-delete" href="products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?');">Delete</a>
          </td>
        <?php endif; ?>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
