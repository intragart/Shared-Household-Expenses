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

function reloadTable(tableHolder) {

    // Build the url
    let fromdate = document.getElementById("inputDateFrom").value;
    let todate = document.getElementById("inputDateTo").value;
    let url="/script/getTable.php?fromdate="+fromdate+"&todate="+todate;

    // Build a new HTTPRequestElement and send the get request
    let request = new XMLHttpRequest();
    request.open('GET', url, true);
    request.send();

    // Response received
    request.onload = function() {
        if (request.status == 200) {
            // Successful request. Replace innerHTML for tableHolder
            document.getElementById(tableHolder).innerHTML = request.response;

            // execute search if present on site
            if (typeof searchTable === "function") { 
                searchTable();
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

