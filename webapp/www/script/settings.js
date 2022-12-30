// Shared Household Expenses
// Copyright (C) 2023  Marco Weingart

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <https://www.gnu.org/licenses/>.

function newUser() {
    // Stop any previous running EventListeners on edit Purchase
    stopCheckForm('newUserForm');

    // gets the details-content element where the result will be shown and hide the Details
    let targetSpan = document.getElementById("message-content");
    targetSpan.innerHTML = "";

    // Get the HTML and Database data to edit the current purchase
    let request = new XMLHttpRequest();
    request.open('GET', '/api/new-user.php');
    request.send();

    // Response received
    request.onload = function() {
        if (request.status == 200) {
            // Successful request. Add body to innerHTML or current details
            targetSpan.innerHTML = request.response;
            document.getElementById("fullscreen-message").setAttribute("style","display: flex");

            // Add checkForm
            startCheckForm('newUserForm');
        } else {
            // Request hasn't benn successful, inform user
            targetSpan.innerHTML = "Received: "+request.status+": "+request.statusText;
        }
    };

    // Request failed
    request.onerror = function() {
        targetSpan.innerHTML = "Request failed.";
    };

}

function editUser(index) {
    // Stop any previous running EventListeners on edit Purchase
    stopCheckForm('editUserForm');

    // gets the details-content element where the result will be shown and hide the Details
    let targetSpan = document.getElementById("message-content");
    targetSpan.innerHTML = "";

    // Get the HTML and Database data to edit the current purchase
    let request = new XMLHttpRequest();
    request.open('GET', '/api/edit-user.php?user_id='+index);
    request.send();

    // Response received
    request.onload = function() {
        if (request.status == 200) {
            // Successful request. Add body to innerHTML or current details
            targetSpan.innerHTML = request.response;
            document.getElementById("fullscreen-message").setAttribute("style","display: flex");

            // Add checkForm
            startCheckForm('editUserForm');
        } else {
            // Request hasn't benn successful, inform user
            targetSpan.innerHTML = "Received: "+request.status+": "+request.statusText;
        }
    };

    // Request failed
    request.onerror = function() {
        targetSpan.innerHTML = "Request failed.";
    };

}
