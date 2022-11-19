// Shared Household Expenses
// Copyright (C) 2022  Marco Weingart

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

function showHideTableDetails(index) {
    // gets the table-details-row for the correct index and toggles the hidden class on or off
    // depending on current state
    let detailRow = document.getElementById("details-"+index);
    let detailRowClasses = detailRow.className.split(' ');

    if (detailRowClasses.includes("hidden")) {
        // Details are hidden at the moment.

        // Request Details
        let request = new XMLHttpRequest();
        request.open('GET', '/script/getDashboardDetails.php?index='+index);
        request.send();

        // Response received
        request.onload = function() {
            if (request.status == 200) {
                // Successful request. Add body to innerHTML or current details
                document.getElementById("details-content-"+index).innerHTML = request.response;
            } else {
                // Request hasn't benn successful, inform user
                document.getElementById("details-content-"+index).innerHTML = "Received: "+request.status+": "+request.statusText;
            }
        };

        // Request failed
        request.onerror = function() {
            document.getElementById("details-content-"+index).innerHTML = "Request failed.";
        };

        // Make Details visible
        detailRow.className = detailRow.className.replace(" hidden", "");
    } else {
        // Details are visible. Hide them.
        detailRow.className = detailRow.className + " hidden";
    }
}