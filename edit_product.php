<?php
// Start the session (if necessary)
session_start();

// Include database connection
include 'config.php'; 

// Get the product ID from the URL (assuming it's passed as a query parameter)
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input values
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Check if the product name already exists (except for the current product being edited)
    $check_sql = "SELECT id FROM products WHERE name = '$name' AND id != $id LIMIT 1";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Product already exists
        $_SESSION['error_message'] = "Error: A product with the name '$name' already exists in the inventory.";
        header("Location: edit_product.php?id=$id");  // Redirect back to the edit product page
        exit;
    } else {
        // If the product name doesn't exist, update the product
        $sql = "UPDATE products SET name = '$name', description = '$description', quantity = '$quantity', price = '$price' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Product updated successfully.";
            header("Location: inventory.php");  // Redirect to the inventory list
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>

    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="styles2.css">
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <h1>Edit Product</h1>

    <?php
    // Show success or error messages if available
    if (isset($_SESSION['error_message'])) {
        echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
        unset($_SESSION['error_message']);  // Clear the message after displaying it
    }

    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert alert-success'>{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']);  // Clear the message after displaying it
    }
    ?>

    <?php
    // Get product details to populate the form
    $product_sql = "SELECT * FROM products WHERE id = $id";
    $product_result = $conn->query($product_sql);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
    ?>
        <form action="edit_product.php?id=<?php echo $id; ?>" method="POST">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo $product['description']; ?></textarea>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['price']; ?>" required>

            <button type="submit">Update Product</button>
        </form>
    <?php
    } else {
        echo "<p>Product not found.</p>";
    }
    ?>

    <!-- Back to Inventory Button with icon -->
    <a href="inventory.php" class="back-btn">
        <i class="fas fa-reply"></i> Back to Inventory
    </a>
</div>

</body>
</html>

<?php $conn->close(); ?>
