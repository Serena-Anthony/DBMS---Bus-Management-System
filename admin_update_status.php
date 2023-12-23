<!DOCTYPE html>
<html>
<head>
    <title>Update Bus Status</title>
    <link rel="stylesheet" type="text/css" href="admin_update.css">
</head>
<body>
    <h1>Update Bus Status</h1>
    <!-- Add content specific to updating bus status here -->
    <div class="form-container">
        <form id="statusForm">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Enter location" required><br>

            <label for="busNumber">Bus Number:</label>
            <input type="text" id="busNumber" name="busNumber" placeholder="Enter bus number" required><br>

            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>

    <a href="admin_landing.php"> Back to Dashboard </a>

    <script src="admin_update.js"></script>
</body>
</html>
