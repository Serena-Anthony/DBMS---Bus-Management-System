// Add this JavaScript code to script.js
document.getElementById("statusForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Get the input values
    const location = document.getElementById("location").value;
    const busNumber = document.getElementById("busNumber").value;

    // You can perform further actions here, such as sending the data to a server
    // Example: Send data using fetch API
    // fetch('your-server-endpoint', {
    //     method: 'POST',
    //     body: JSON.stringify({ location, busNumber }),
    //     headers: {
    //         'Content-Type': 'application/json'
    //     }
    // })
    // .then(response => response.json())
    // .then(data => {
    //     // Handle the server response here
    // });

    // Reset the form
    document.getElementById("statusForm").reset();
});
