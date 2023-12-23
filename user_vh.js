// main.js

document.addEventListener('DOMContentLoaded', function () {
    // Sample travel history data (replace this with actual data from your server/database)
    const travelHistoryData = [
        { busNumber: 'Bus 101', date: '2023-01-01', details: 'Trip to City A' },
        { busNumber: 'Bus 202', date: '2023-02-15', details: 'Trip to City B' },
        { busNumber: 'Bus 303', date: '2023-03-30', details: 'Trip to City C' },
    ];

    // Render travel history
    renderTravelHistory(travelHistoryData);
});

function renderTravelHistory(travelHistoryData) {
    const travelHistoryContainer = document.getElementById('travelHistory');

    if (travelHistoryData.length === 0) {
        travelHistoryContainer.innerHTML = '<p>No travel history available.</p>';
        return;
    }

    // Loop through the travel history data and create HTML entries
    travelHistoryData.forEach(entry => {
        const entryDiv = document.createElement('div');
        entryDiv.classList.add('travel-entry');

        // Create HTML content for each entry
        const entryContent = `
            <p><strong>Bus Number:</strong> ${entry.busNumber}</p>
            <p><strong>Date:</strong> ${entry.date}</p>
            <p><strong>Details:</strong> ${entry.details}</p>
        `;

        entryDiv.innerHTML = entryContent;

        // Append the entry to the travel history container
        //travelHistoryContainer.appendChild(entryDiv);
    });
}
