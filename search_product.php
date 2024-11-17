<?php
session_start(); // Start the session for user authentication and session variables

include('includes/header.php');  // Include the header section (optional, if you have a separate header file)
include('includes/navbar.php');  // Include the navbar code

include 'config.php'; // Database connection

// Initialize the search query
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Modify the SQL query to filter based on the search input
$sql = "SELECT * FROM products";
if (empty($search)) {
    header('location: inventory.php'); // Redirect to inventory page
    exit(0);
}else{
    $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($search) . "%'";  // Search by product name
}

$result = $conn->query($sql);
?>

<title>Search Results - Inventory Management System</title>

<!-- Link to custom CSS file -->
<link rel="stylesheet" href="styles4.css">


<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJx3+0pW3kJ3edLgZ2o9a3b8c3F5g3T/jVnJYyHfFmr8YdZK6gKmH9/lnf4J" crossorigin="anonymous">

<div class="container mt-5">
    <table class="table table-bordered">
        <tr>
            <td colspan="2">
                <h1 class="mb-4 text-center">Search Result</h1>


                <!-- Back to Inventory Button -->
                <div class="row mb-3">
                    <div class="col-md-12 text-end">
                        <a href="inventory.php" class="btn btn-secondary"><i class="fas fa-reply"></i> Back to Inventory</a>
                    </div>
                </div>
            </td>
        </tr>

        <!-- Table for search results -->
        <tr>
            <td colspan="2">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Corrected the PHP echo statements inside the <a> tags
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['description']}</td>
                                            <td>{$row['quantity']}</td>
                                            <td>\${$row['price']}</td>
                                            <td class='action-buttons'>
                                                <a href='edit_product.php?id={$row['id']}' title='Edit' class='text-warning'>
                                                    <i class='fas fa-edit'></i>
                                                </a> 
                                                | 
                                                <a href='delete_product.php?id={$row['id']}' title='Delete' class='text-danger' onclick='return confirm(\"Are you sure you want to delete this product?\");'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </a>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No products found matching your search.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- Optional: Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybMw7Y6P5lHhwLclZrHF7VjI8fWwW5GoL0Lw48pBqW7mHk25F" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0I88i9gbLxvK0vZj4WnQL3UP5eFNlMwVjmtV1z47Cjb7XtEr" crossorigin="anonymous"></script>

<?php $conn->close(); ?>

</body>
</html>
