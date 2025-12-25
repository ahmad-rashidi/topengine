<?php
require 'auth.php';
require '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads
    $images = [];
    for ($i = 1; $i <= 3; $i++) {
        $inputName = 'image'.$i;
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
            $filename = time() . "_{$i}_" . basename($_FILES[$inputName]['name']);
            $targetDir = '../images/products/';
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
                $images[$i] = $filename;
            } else {
                $images[$i] = '';
            }
        } else {
            $images[$i] = '';
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO products
        (title, part_no, description, price, category, image1, image2, image3)
        VALUES (?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['title'],
        $_POST['part_no'],
        $_POST['description'],
        $_POST['price'],
        $_POST['category'],
        $images[1],
        $images[2],
        $images[3]
    ]);

    $message = "Product added successfully ✅";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-header">
    <strong>TopEngine Admin</strong>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</div>

<div class="admin-container card">
    <h2>Add Product</h2>
    <?php if($message): ?>
        <p style="color:green"><?= $message ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Product Title:</label><br>
        <input type="text" name="title" placeholder="Toyota Camry Brake Pad" required><br><br>

        <label>Part Number:</label><br>
        <input type="text" name="part_no" placeholder="04465-33470" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" placeholder="Details about origin, condition, etc." required></textarea><br><br>

        <label>Price (AED):</label><br>
        <input type="number" name="price" placeholder="0.00" step="0.01" required><br><br>

        <label>Category:</label><br>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="engine">Engine</option>
            <option value="transmission">Transmission</option>
            <option value="injector">Injector</option>
            <option value="engine-control">Engine Control</option>
            <option value="turbo-charger">Turbo Charger</option>
            <option value="turbo-cartridge">Turbo Cartridge</option>
            <option value="alternator">Alternator</option>
            <option value="starter">Starter</option>
            <option value="crankshaft">Crankshaft</option>
            <option value="diesel-pump">Diesel Pump</option>
            <option value="power-pump">Power Pump</option>
            <option value="ac-compressor">AC Compressor</option>
            <option value="general-parts">General Parts</option>
            <option value="body-parts">Body Parts</option>
            <option value="cylinder-head">Cylinder Head</option>
            <option value="shop">Shop</option>
        </select><br><br>

        <label>Product Images (3 max):</label><br>
        <input type="file" name="image1" accept="image/*" required>
        <input type="file" name="image2" accept="image/*">
        <input type="file" name="image3" accept="image/*"><br><br>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>
----------------------------

