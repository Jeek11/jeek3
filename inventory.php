<?php 
session_start(); // Start the session for user authentication and session variables

// Check if the user is logged in
if (!isset($_SESSION['authenticated'])) {
    // If not authenticated, redirect to login page with a session message
    $_SESSION['status'] = "Please log in first to access this page.";
    header('location: login.php'); // Redirect to login page
    exit(0);
}

include('includes/header.php');
include('includes/navbar.php'); 

include 'config.php'; 

// Initialize the search query
$search = isset($_POST['search']) ? $_POST['search'] : '';  // Fix typo in isset()

// Use prepared statements for SQL query to avoid SQL injection
$sql = "SELECT * FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = '%' . $search . '%'; // Prepare the search term for LIKE
$stmt->bind_param("s", $search_param); // Bind the search parameter
$stmt->execute();
$result = $stmt->get_result(); // Get the result set
?>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJx3+0pW3kJ3edLgZ2o9a3b8c3F5g3T/jVnJYyHfFmr8YdZK6gKmH9/lnf4J" crossorigin="anonymous">

<!-- Link to external CSS file -->
<link rel="stylesheet" href="styles3.css">

<div class="container mt-5 table-container glowing-table-container">
    <table class="table table-bordered">
        <tr>
            <td colspan="2">
                <h1 class="mb-4 text-center">Inventory Management System</h1>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <!-- Search Form with adjustments -->
                <div class="d-flex justify-content-between">
                    <div>
                        <!-- Add Product Button -->
                        <a href="add_product.php" class="btn btn-primary">Add New Product</a>
                    </div>
                    <div class="col-md-6">
                        <form method="POST" action="search_product.php" class="d-flex">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" style="width: 100%; max-width: 300px;">
                            <button type="submit" class="btn btn-primary ms-2"><i class="fas fa-search"></i> Search</button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <!-- Product Table -->
                <div class="table-responsive">
                    <table class="table glowing-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th> <!-- Directly display ID -->
                                <th>Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="glowing-row">
                                <td><?php echo $row['id']; ?></td> <!-- Display the product ID -->
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                <td>
                                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" title="Edit" class="text-warning">
                                        <i class="fas fa-edit"></i>
                                    </a> 
                                    | 
                                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" title="Delete" class="text-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

<?php 
$conn->close();  // Close the database connection
?>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybMw7Y6P5lHhwLclZrHF7VjI8fWwW5GoL0Lw48pBqW7mHk25F" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0I88i9gbLxvK0vZj4WnQL3UP5eFNlMwVjmtV1z47Cjb7XtEr" crossorigin="anonymous"></script>

</body>
</html>
