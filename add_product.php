<?php
// Start the session (if necessary)
session_start();

// Include database connection
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input values
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Check if the product already exists
    $check_sql = "SELECT id FROM products WHERE name = '$name' LIMIT 1";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Product already exists, store the error message in session
        $_SESSION['error_message'] = "Error: A product with the name '$name' already exists in the inventory.";
        header("Location: add_product.php");  // Redirect back to the add product page
        exit;
    } else {
        // If the product doesn't exist, insert the new product
        $sql = "INSERT INTO products (name, description, quantity, price)
                VALUES ('$name', '$description', '$quantity', '$price')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "New product added successfully.";
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
    <title>Add New Product</title>

    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="styles2.css">
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <h1>Add New Product</h1>

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

    <form action="add_product.php" method="POST">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" ></textarea>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>

        <button type="submit">Add Product</button>
    </form>

    <!-- Back to Inventory Button with icon -->
    <a href="inventory.php" class="back-btn">
        <i class="fas fa-reply"></i> Back to Inventory
    </a>
</div>

</body>
</html>

<?php $conn->close(); ?>
