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

function editPurchase(index) {
    // Load the UI for the given purchase, place the contents in the fullscreen popup
    // and display it.
    // 
    // Args:
    //     index (int): id of the purchase in question.
 
    // Stop any previous running EventListeners on edit Purchase
    stopCheckForm('editPurchaseForm');

    // gets the details-content element where the result will be shown and hide the Details
    let targetSpan = document.getElementById("message-content");
    targetSpan.innerHTML = "";

    // Get the HTML and Database data to edit the current purchase
    let request = new XMLHttpRequest();
    request.open('GET', '/api/edit-purchase.php?purchase_id='+index);
    request.send();

    // Response received
    request.onload = function() {
        if (request.status == 200) {
            // Successful request. Add body to innerHTML or current details
            targetSpan.innerHTML = request.response;

            // Add checkForm
            startCheckForm('editPurchaseForm');
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

function showPurchaseDetails(index) {
    // Loads the details of an specific purchase, places them into the fullscreen
    // popup and makes the popup visible.
    // 
    // Args:
    //     index (int): id of the purchase in question

    // Request Details
    let request = new XMLHttpRequest();
    request.open('GET', '/api/get-purchase-details.php?purchase_id='+index);
    request.send();

    // Response received
    request.onload = function() {
        if (request.status == 200) {
            // Successful request. Add body to innerHTML or current details
            document.getElementById("message-content").innerHTML = request.response;
            document.getElementById("fullscreen-message").setAttribute("style","display: flex");
        } else {
            // Request hasn't benn successful, inform user
            document.getElementById("details-content-"+index).innerHTML = "Received: "+request.status+": "+request.statusText;
        }
    };

    // Request failed
    request.onerror = function() {
        document.getElementById("details-content-"+index).innerHTML = "Request failed.";
    };
}
