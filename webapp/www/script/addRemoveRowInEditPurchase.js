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

function addEditPurchase() {
    // This function reads the base html for a new purchase line with the
    // html document, makes it optional and changes the ids accordingly.
    // After that the newly generated html is placed inside the html
    // document and shown to the user.

    // Copy parent Row to new Variable
    let newRow = document.getElementById('new-row-base-html').innerHTML;

    // get the next row id and store the object
    let rowIndexObj = document.getElementById('next-row-id');
    let rowIndex = Number(rowIndexObj.innerHTML);

    // make new rows optional by replacing regex '+' with '*'
    newRow = newRow.replaceAll("+", "*");

    // Enable additional Dropdowns to remove value and make them not required
    newRow = newRow.replaceAll("disabled=\"\" selected=\"\" value=\"\" hidden=\"\"", "selected=\"\" value=\"\"");
    newRow = newRow.replaceAll("<select ", "<select optional ");

    // uncomment new html
    newRow = newRow.replaceAll("<!-- ", "");
    newRow = newRow.replaceAll(" -->", "");

    // remove placeholders and set timestamp to new
    newRow = newRow.replaceAll("_timestamp_", "new");
    newRow = newRow.replaceAll("_contribution_id_", "new");
    newRow = newRow.replaceAll("_amount_", "");
    newRow = newRow.replaceAll("_comment_", "");

    // Add dependencies to new rows - new rows are not requiered by default in application
    // logic since pattern '+' is replaced by '*' but they become required as soon as one or more
    // of the 'depends' attribute form inputs have a value
    newRow = newRow.replace('id="inputUser__"', 'id="inputUser__" depends="inputAmount' + String(rowIndex) + '"');
    newRow = newRow.replace('id="inputAmount__"', 'id="inputAmount__" depends="inputUser' + String(rowIndex) + '"');
    newRow = newRow.replace('id="inputRemark__"', 'id="inputRemark__" depends="inputUser' + String(rowIndex) + ' inputAmount' + String(rowIndex) + '"');

    // Replace row specific numbers names and ids with current numRows
    newRow = newRow.replaceAll("__", String(rowIndex));

    // insert the new row into the document
    document.getElementById("editPurchaseTable").insertAdjacentHTML("beforeend", newRow);

    // set focus to new row
    document.getElementById("inputUser" + String(rowIndex)).focus();

    // count up
    rowIndex = rowIndex + 1;
    rowIndexObj.innerHTML = rowIndex;
}


function toggleDeletionOrDeleteContribution(rowIndex) {
    // Function to Remove new Rows from Form or mark already existing contributions
    // for deletion when form is saved.
    //
    // Args:
    //     rowIndex (int): index of the row in question

    // get the row where the button has been clicked
    let currentRow = document.getElementById("row" + String(rowIndex));

    // get the contribution id for the current row
    let contribID = currentRow.querySelector("input[id=contributionId" + String(rowIndex)).value;

    // get the button which toggles between remove and keep
    let toggleButton = currentRow.querySelector('i.material-icons');

    // get the string that contains all contributions that shall be removed
    // when form is being saved
    let removeContribs = document.getElementById("deleteContributions");
    
    // get the current mode of the button to determine what to do next
    let currentMode = toggleButton.innerHTML;

    if (currentMode == "remove") {
        // delete the current line completely if it's a new line or mark it to be deleted
        // when line comes from the database
        if (document.getElementById("contributionId"+String(rowIndex)).value == "new") {
            // new line, delete
            currentRow.remove();
        } else {
            // line from database, mark ui for deletion
            let domElements = currentRow.querySelectorAll('input[type=text], select, td');
            for (let i = 0; i < domElements.length; i++) {
                domElements[i].classList.add("strikethrough");
                domElements[i].setAttribute('disabled', "");
            }
            toggleButton.innerHTML = "undo";

            // add contribution id to deleteContributions input in form
            if (removeContribs.value == "") {
                // no other IDs are present, simply insert
                removeContribs.value = contribID;
            } else {
                // other IDs are already present, append at the end of the string
                // with a leading comma
                removeContribs.value = removeContribs.value + "," + contribID;
            }
        }
    } else if (currentMode == "undo") {
        // line from database, delete mark for deletion in gui
        let domElements = currentRow.querySelectorAll('input[type=text], select, td');
        for (let i = 0; i < domElements.length; i++) {
            domElements[i].classList.remove("strikethrough");
            domElements[i].removeAttribute('disabled');
        }
        toggleButton.innerHTML = "remove";

        // remove contribution id from deleteContributions input in form
        // To do this the string is split into an array using "," as delimeter
        // and adding all ids to be removed back except for the id that should
        // be excluded from the list
        let alreadyMarkedIds = removeContribs.value.split(",");
        removeContribs.value = "";
        for (let i = 0; i < alreadyMarkedIds.length; i++) {
            if (alreadyMarkedIds[i] != contribID) {
                // add contribution id to deleteContributions input in form
                if (removeContribs.value == "") {
                    // no other IDs are present, simply insert
                    removeContribs.value = alreadyMarkedIds[i];
                } else {
                    // other IDs are already present, append at the end of the string
                    // with a leading comma
                    removeContribs.value = removeContribs.value + "," + alreadyMarkedIds[i];
                }
            }
        }
    }
}
