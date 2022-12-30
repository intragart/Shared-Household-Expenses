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

// Get the "Add new Row" Button and add an eventListener
const plusButton = document.getElementById("new-row");
plusButton.addEventListener("click", addRow);

// This event listener is set on the last comment field in the form and
// triggers the creation of a new row when updated by the user
let triggerNewRow = document.getElementById("inputRemark0");
triggerNewRow.addEventListener("blur", checkCurrentRow)

// index for new rows - can't be decremented
// starts with 0 since it is incremented by one before the new
// line gets created
let rowIndex = 0;

// get the html for the parent row from id "row0"
const parentRow = document.getElementById("row0").outerHTML;

function addRow() {
    // This function gets the base html for a new contribution line line
    // from the parentRow constant, makes it optional and changes the ids accordingly.
    // After that the newly generated html is placed inside the html
    // document and shown to the user.

    // Copy parent Row to new Variable
    let newRow = parentRow;

    // count up
    rowIndex = rowIndex + 1;

    // make new rows optional by replacing regex '+' with '*'
    newRow = newRow.replaceAll("+", "*");

    // Enable additional Dropdowns to remove value and make them not required
    newRow = newRow.replaceAll("disabled=\"\" selected=\"\" value=\"\" hidden=\"\"", "selected=\"\" value=\"\"");
    newRow = newRow.replaceAll("<select ", "<select optional ");

    // Add dependencies to new rows - new rows are not requiered by default in application
    // logic since pattern '+' is replaced by '*' but they become required as soon as one or more
    // of the 'depends' attribute form inputs have a value
    newRow = newRow.replace('id="inputUser0"', 'id="inputUser0" depends="inputAmount' + String(rowIndex) + '"');
    newRow = newRow.replace('id="inputAmount0"', 'id="inputAmount0" depends="inputUser' + String(rowIndex) + '"');
    newRow = newRow.replace('id="inputRemark0"', 'id="inputRemark0" depends="inputUser' + String(rowIndex) + ' inputAmount' + String(rowIndex) + '"');

    // Replace row specific numbers names and ids with current rowIndex
    newRow = newRow.replaceAll("row0", "row" + String(rowIndex));
    newRow = newRow.replaceAll("inputUser0", "inputUser" + String(rowIndex));
    newRow = newRow.replaceAll("inputAmount0", "inputAmount" + String(rowIndex));
    newRow = newRow.replaceAll("inputRemark0", "inputRemark" + String(rowIndex));

    // insert the new row into the document
    document.getElementById("new-row-above").insertAdjacentHTML("beforebegin", newRow);

    // update the eventListener
    triggerNewRow.removeEventListener("blur", checkCurrentRow);
    triggerNewRow = document.getElementById("inputRemark" + String(rowIndex));
    triggerNewRow.addEventListener("blur", checkCurrentRow);

    // set focus to new row
    document.getElementById("inputUser" + String(rowIndex)).focus();
}

function checkCurrentRow() {
    // Checks if inputUser and inputAmount of current row are not empty.
    // If they're not empty a new row will be appended to the form.

    if (document.getElementById("inputUser"+rowIndex).value != "" &&
    + document.getElementById("inputAmount"+rowIndex).value != "") {
        addRow();
    }
}
