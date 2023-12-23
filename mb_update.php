<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking</title>
    <link rel="stylesheet" href="mb_update.css">
</head>
<body>
    <div class="container">
        <h1>Update Booking</h1>
        <form id="updateForm">
            <label for="travelSelect">Select Upcoming Travel:</label>
            <select id="travelSelect" name="travelSelect">
                <!-- Dynamically populate this dropdown with upcoming travels using JavaScript -->
                <!-- Example: <option value="travel1">Travel 1 - Date</option> -->
            </select>

            <label for="newDate">New Travel Date:</label>
            <input type="date" id="newDate" name="newDate" required>

            <button id="btn" type="submit">Update Ticket</button>
        </form>
        <button id="btn" onclick="goBack()">Go Back</button>
    </div>
  
    <script>
        // You can use JavaScript to dynamically populate the dropdown with upcoming travels
        // Example:
        const travelSelect = document.getElementById('travelSelect');
        const upcomingTravels = ['Travel 1 - Date', 'Travel 2 - Date', 'Travel 3 - Date'];

        upcomingTravels.forEach((travel, index) => {
            const option = document.createElement('option');
            option.value = `travel${index + 1}`;
            option.text = travel;
            travelSelect.appendChild(option);
        });

        function goBack() {
            window.location.href = "new_booking_2.php"; // Replace with the actual path or URL for manage_booking2.php
        }
    </script>
</body>
</html>
