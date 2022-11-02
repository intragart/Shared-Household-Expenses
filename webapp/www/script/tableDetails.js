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