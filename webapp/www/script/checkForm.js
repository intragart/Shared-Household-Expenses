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

// listen for changes inside the form
function startCheckForm(formID) {
    const formOnPage = document.getElementById(formID);
    if (formOnPage != null) {
        stopCheckForm(formID);
        formOnPage.addEventListener("keyup", function(dummy) { checkForm(formOnPage); });
        formOnPage.addEventListener("click", function(dummy) { checkForm(formOnPage); });
        checkForm(formOnPage);
    }
}

function stopCheckForm(formID) {
    const formOnPage = document.getElementById(formID);
    if (formOnPage != null) {
        formOnPage.removeEventListener("keyup", function(dummy) { checkForm(formOnPage); });
        formOnPage.removeEventListener("click", function(dummy) { checkForm(formOnPage); });
    }
}

function checkForm(form) {
    // Checks the form using multiple functions. If all functions return true
    // the submit button becomes active. Otherwise the submit button becomes inactive.
    // Returns nothing

    let checkP = checkPatterns();
    let checkD = checkDependencies();

    let submitBtn = form.querySelector(".submitBtn");
    if (checkP && checkD) {
        submitBtn.removeAttribute("disabled");
        submitBtn.className = submitBtn.className.replace(" invalid", "");
        
    } else {
        submitBtn.setAttribute("disabled", "");
        if (!submitBtn.className.includes("invalid")) {
            submitBtn.className = submitBtn.className + " invalid";
        }
    }
}

function checkDependencies() {
    // Gets all input elements with depends attribute set. If the current element has more
    // than zero chars the element ids mentioned in the depends attribute seperated by space 
    // are being checked for contents. If the IDs mentioned in the depends attribute don't have
    // more than zero chars they receive a special invalid class to highlight them using css. If
    // the IDs have more than zero chars the special invalid class gets removed. Returns true if
    // all dependencies of input elements with values have values themselfes. Otherwise returns
    // false

    // get all input elements that have the attribute pattern
    const dependsInputs = document.querySelectorAll('input[depends], select[depends]');

    // store elements that are not valid with their dependencies
    let invalidElements = [];

    for (i = 0; i < dependsInputs.length; i++) {
        // get current element
        element = dependsInputs[i];

        // get dependent ids
        let dependsIds = element.getAttribute('depends').split(' ');

        if (dependsIds.length > 0) {
            // one or more dependencies are set
            if (element.value.length > 0) {

                // Element has chars, check if dependencies have chars as well and give them the
                // invalid class if they're empty. Otherwise remove invalid class.
                for (z = 0; z < dependsIds.length; z++) {

                    let extElement = document.getElementById(dependsIds[z]);
                    if (extElement.value.length > 0) {
                        // Dependency Element has chars, remove invalid class if set
                        extElement.className = extElement.className.replace(" invalid", "");

                    }
                    else {

                        // Dependency Element has no chars, set invalid class if not already set
                        if (!extElement.className.includes("invalid")) {
                            extElement.className = element.className + " invalid";
                        }

                        if(!invalidElements.includes(dependsIds[z])) {
                            invalidElements.push(dependsIds[z]);
                        }
                    }
                }
            } else {
                // Element doesn't have any chars. Remove invalid class from dependencies if set
                // Only remove if element hasn't been invalid in the current iteration

                for (z = 0; z < dependsIds.length; z++) {
                    if(!invalidElements.includes(dependsIds[z])) {
                        let extElement = document.getElementById(dependsIds[z]);
                        extElement.className = extElement.className.replace(" invalid", "");
                    }
                }
            }
        }
    }

    if(invalidElements.length == 0) {
        return true;
    }
    return false;
}

function checkPatterns() {
    // Checks all input elements with the pattern attribute set against their
    // defined pattern. Input elements with more than zero chars that don't match
    // their pattern get an special invalid class assigned to highlight them via css.
    // Input elements with zero or more chars that match their pattern lose the
    // special invalid class if set. The function returns true if all inputs match their
    // Patterns regardless of their char count. Otherwise returns false.

    // get all input elements that have the attribute pattern
    const patternInputs = document.querySelectorAll('input[pattern]');

    // get all select elements that are required
    const requieredSelect = document.querySelectorAll('select');

    // set true by default. Becomes false as soon as one element doesn't match their
    // pattern regardless of char count
    let formCompleteNoErrors = true;

    patternInputs.forEach(
        function(element) {
            // try to match the pattern attribute of the current element
            // against its value
            const re = new RegExp(element.pattern);
            if (re.test(element.value)) {
                // Pattern matches, remove invalid class if set
                element.className = element.className.replace(" invalid", "");

            } else if (element.value.length == 0) {
                // Pattern doesn't match but input doesn't contain chars
                // Remove invalid class if set but make function return false
                element.className = element.className.replace(" invalid", "");
                formCompleteNoErrors = false;

            } else {
                // Pattern doesen't match input, set invalid class if not already set
                // Make function return false
                if (!element.className.includes("invalid")) {
                    element.className = element.className + " invalid";
                }
                formCompleteNoErrors = false;
            }
        }
    );

    requieredSelect.forEach(
        function(element) {
            if (!element.hasAttribute("optional")) {
                if (element.value.length == 0) {
                    formCompleteNoErrors = false;
                }
            }
        }
    );

    return formCompleteNoErrors;
}
