// admin_manage_routes.js

function toggleStatus() {
    var statusButton = document.getElementById("statusButton");
    var statusSelect = document.getElementById("status");
  
    if (statusSelect.value === "active") {
      statusSelect.value = "deleted";
      statusButton.innerText = "Deleted";
      statusButton.style.backgroundColor = "#ff6347"; // Change to desired color for "Deleted"
    } else {
      statusSelect.value = "active";
      statusButton.innerText = "Active";
      statusButton.style.backgroundColor = "#0074D9"; // Change to desired color for "Active"
    }
  }
  