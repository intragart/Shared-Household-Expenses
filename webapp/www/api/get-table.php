<?php 
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

    // check if the maindata fields match their regpatterns
    $all_regex_ok = true;
    if (isset($_GET["fromdate"]) && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_GET["fromdate"])) {
        $all_regex_ok = false;
    }
    if (isset($_GET["todate"]) && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_GET["todate"])) {
        $all_regex_ok = false;
    }

    // End execution if regex not ok
    if (!$all_regex_ok) {
        echo "Please validate your inputs.";
        http_response_code(400);
        exit();
    }

    // Build the SQL Statement (from right to left)
    $select_statement = "";
    if (isset($_GET['todate'])) {
        $select_statement = " date <= '".$_GET['todate']."'";
    } elseif (isset($php_todate)) {
        $select_statement = " date <= '".$php_todate."'";
    }
    if (isset($_GET['fromdate'])) {
        if ($select_statement != "") {
            $select_statement = " AND".$select_statement;
        }
        $select_statement = " date >= '".$_GET['fromdate']."'".$select_statement;
    } elseif (isset($php_fromdate)) {
        if ($select_statement != "") {
            $select_statement = " AND".$select_statement;
        }
        $select_statement = " date >= '".$php_fromdate."'".$select_statement;
    }
    if (isset($_GET['fromdate']) || isset($_GET['todate']) || isset($php_fromdate) || isset($php_todate)) {
            $select_statement = " WHERE".$select_statement;
    }
    $select_statement = "SELECT * FROM dashboard".$select_statement;

    // Get the informations to connect to database as editor
    if (! @include_once("../src/get_db_login.php")) {
        require_once("../../src/get_db_login.php");
    } else {
        require_once("../src/get_db_login.php");
    }
    
    $db_settings = get_db_login("admin");

    // Get the settings for the used currency
    if (! @include_once("../src/get_currency_settings.php")) {
        require_once("../../src/get_currency_settings.php");
    } else {
        require_once("../src/get_currency_settings.php");
    }
    $currency = get_currency_settings();

     

    try {
        // connect to database
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);

        // Get table data
        $res = $db->query($select_statement);

        // Create Table Head if data has been received
        if ($res->num_rows > 0) {
            echo "<table table=\"maintable\" class=\"maintable\">\n";
            echo "<tr class=\"maintable maintable-head\">\n";
            echo "<th class=\"maintable\">Artikel</th>\n";
            echo "<th class=\"maintable\">Händler</th>\n";
            echo "<th class=\"maintable\">Datum</th>\n";
            echo "<th class=\"maintable\">Beteiligte</th>\n";
            echo "<th class=\"maintable\">Gesamt</th>\n";
            echo "</tr>\n";
        } else {
            echo "<p>No Data.</p>";
        }

        // Display the received data in table
        while ($row = $res->fetch_assoc()) {
            echo "<tr class='maintable maintable-row' id='purchase-".$row['purchase_id']."' onclick='showPurchaseDetails(".$row['purchase_id'].");'>";
            echo "<td class=\"maintable\">".$row['article']."</td>";
            echo "<td class=\"maintable\">".$row['retailer']."</td>";
            echo "<td class=\"maintable\">".$row['date']."</td>";
            echo "<td class=\"maintable\">".$row['contributor']."</td>";
            echo "<td class=\"maintable\">";
            if ($currency["currencyPosition"] == "before") {
                echo $currency["currencySymbol"]." ";
                echo str_replace(".", $currency["currencyDecimal"], $row['amount']);
            } else {
                echo str_replace(".", $currency["currencyDecimal"], $row['amount']);
                echo " ".$currency["currencySymbol"];
            }
            echo "</td>";
            echo "</tr>";
        }

        // Close Table if data has been received
        if ($res->num_rows > 0) {
            echo "</table>";
        }

    } catch (Exception $ex) {
        echo "Error during SQL Execution.";
        http_response_code(400);
    }
    
    // close db connection
    if (isset($db)) {
        $db->close();
    }

?>
