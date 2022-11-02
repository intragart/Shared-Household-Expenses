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

// Get all Table rows that are in class 'table-row' and add an event listener to each one
// let normalTableRows = document.querySelectorAll("tr[class='table-row']");
// for (i = 0; i < normalTableRows.length; i++) {
//     document.addEventListener("click", showHideTableDetails);
// }

function showHideTableDetails(index) {
    // gets the table-details-row for the correct index and toggles the hidden class on or off
    // depending on current state
    let detailRow = document.getElementById("details-"+index);
    let detailRowClasses = detailRow.className.split(' ');

    if (detailRowClasses.includes("hidden")) {
        // Details are hidden at the moment. Make them visible.
        detailRow.className = detailRow.className.replace(" hidden", "");
    } else {
        // Details are visible. Hide them.
        detailRow.className = detailRow.className + " hidden";
    }
}