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

// Get the "Add new Row" Button and add an eventListener
const plusButton = document.getElementById("new-row");
plusButton.addEventListener("click", addRow);

let triggerNewRow = document.getElementById("inputRemark0");
triggerNewRow.addEventListener("blur", checkCurrentRow)

// amount of individual persons for a new entry
// starts at one since one row is hardcoded
let numRows = 1;

// index for new rows - like numRows but can't be decremented
// starts with 0 since it is incremented by one before the new
// line gets created
let rowIndex = 0;

// get the html for the parent row from id "row0"
const parentRow = document.getElementById("row0").outerHTML;

function addRow() {
    // Copy parent Row to new Variable
    let newRow = parentRow;

    // count up
    rowIndex = rowIndex + 1;

    // make new rows optional by replacing regex '+' with '*'
    newRow = newRow.replaceAll("+", "*");

    // Add dependencies to new rows - new rows are not requiered by default in application
    // logic since pattern '+' is replaced by '*' but they become required as soon as one or more
    // of the 'depends' attribute form inputs have a value
    newRow = newRow.replace('id="inputUser0"', 'id="inputUser0" depends="inputAmount' + String(rowIndex) + '"');
    newRow = newRow.replace('id="inputAmount0"', 'id="inputAmount0" depends="inputUser' + String(rowIndex) + '"');
    newRow = newRow.replace('id="inputRemark0"', 'id="inputRemark0" depends="inputUser' + String(rowIndex) + ' inputAmount' + String(rowIndex) + '"');

    // Replace row specific numbers names and ids with current numRows
    newRow = newRow.replaceAll("row0", "row" + String(rowIndex));
    newRow = newRow.replaceAll("inputUser0", "inputUser" + String(rowIndex));
    newRow = newRow.replaceAll("inputAmount0", "inputAmount" + String(rowIndex));
    newRow = newRow.replaceAll("inputRemark0", "inputRemark" + String(rowIndex));

    // count up
    numRows = numRows + 1;
    hiddenFormCounter = document.getElementById("num-rows"); // TODO: Wird der noch benötigt?
    hiddenFormCounter.value = numRows;

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
    if (document.getElementById("inputUser"+rowIndex).value != "" &&
    + document.getElementById("inputAmount"+rowIndex).value != "") {
        addRow();
    }
}

// TODO: Function to Remove Elements