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

// Add event listener for search field
// document.getElementById("tableSearch").addEventListener("keyup", searchTable);
document.getElementById("tableSearch").addEventListener("input", searchTable);

function searchTable() {

    // get search string
    let searchString = document.getElementById("tableSearch").value.toLocaleLowerCase();

    // get all visible rows that are not hidden via css and not table head
    let selectedRows = document.querySelectorAll('tr:not(.hidden, .maintable-head)');
    
    // show all selected Rows if there's no (more) search string. otherwise loop though
    // each table cell of each row and search for the search string in the values. If
    // the search string is not found set the table row to hidden, otherwise show it.
    if (searchString == "") {
        for (let i = 0; i < selectedRows.length; i++) {
            selectedRows[i].hidden = false;
        }
    } else {
        for (let i = 0; i < selectedRows.length; i++) {

            let matchFound = false;

            // get all table cells of current row
            let selectedCells = selectedRows[i].querySelectorAll('td');

            for (let z = 0; z < selectedCells.length; z++) {
                if (selectedCells[z].innerText.toLocaleLowerCase().includes(searchString)) {
                    matchFound = true
                }
            }

            if (matchFound) {
                selectedRows[i].hidden = false;
            } else {
                selectedRows[i].hidden = true;
            }

        }
    }
    
}

