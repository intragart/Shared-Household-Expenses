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

function submitForm(formObject) {
    // Parses all input data from the given form and sends it as POST request to the url
    // defined in form action. Redirects to Dashboard on success or shows an error message

    // check that the pressed submit button is not disabled
    if (formObject.querySelector(".submitBtn").classList.contains("invalid")) {
        return;
    }

    // get the target url
    let url = formObject.getAttribute("action");

    // get all input objects
    let inputObjects = formObject.querySelectorAll('input[type=text], input[type=date], input[type=hidden], select');

    // build the post parameters
    let params = "";
    for (let i = 0; i < inputObjects.length; i++) {
        if (params != "") {
            params = params + "&";
        }
        params = params + inputObjects[i].name + "=" + inputObjects[i].value;
    }

    // Build a new HTTPRequestElement and send the post request
    let request = new XMLHttpRequest();
    request.open('POST', url, true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    // Response received
    request.onload = function() {
        if (request.status == 200) {
            // Successful request
            // forward to site if specified in form tag or reload the current page
            if (formObject.hasAttribute("target")) {
                location.href = formObject.getAttribute("target");
            } else {
                window.location.reload(true);
            }
            
        } else {
            // Request hasn't benn successful, inform user
            alert(request.status+": "+request.statusText+"\n"+request.response);
        }
    };

    // Request failed
    request.onerror = function() {
        alert("Request failed.");
    };
}

function deleteData(formObject, identifier) {
    // Sends a post request to an url which is responsible to delete data by using the identifier

    // Ask the user before deleting
    let answer = confirm("Sollen dieser Einkauf und dessen Beteiligungen wirklich gelöscht werden?");

    if (answer) {
        // get the target url
        let url = formObject.getAttribute("delete-action");

        // Build the parameter
        let params = "identifier=" + String(identifier);

        // Build a new HTTPRequestElement and send the post request
        let request = new XMLHttpRequest();
        request.open('POST', url, true);
        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        request.send(params);

        // Response received
        request.onload = function() {
            if (request.status == 200) {
                // Successful request. Alert the user and reload the page
                alert("Löschen erfolgreich.");
                window.location.reload(true);
            } else {
                // Request hasn't benn successful, inform user
                alert(request.status+": "+request.statusText+"\n"+request.response);
            }
        };

        // Request failed
        request.onerror = function() {
            alert("Request failed.");
        };
    }
}