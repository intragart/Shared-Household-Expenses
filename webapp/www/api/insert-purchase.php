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

    function getRetailerID ($retailer_name) {
        // gets the retailer_id for the given retailer
        // 
        // Args:
        //     retailer_name (string): Name of the retailer in question
        // 
        // Returns:
        //     int: retailer_id
        
        // Get the informations to connect to database as viewer
        $db_settings = get_db_login("admin");

        // Get the retailer_id from inputDealer if existend, defaults to 0
        $retailer_id = 0;
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]); 
        $sql = $db->prepare("SELECT retailer_id FROM retailer WHERE retailer = ? LIMIT 1");
        $sql->bind_param('s', $retailer_name);
        $sql->execute();
        $sql->bind_result($retailer_id); // only changes with valid result
        $sql->fetch();
        $sql->free_result();

        // close db connection as user viewer
        $db->close();

        return $retailer_id;
    }

    require("../../src/get_db_login.php");

    // Get the number of dynamic rows
    $dynamicRows = (count($_POST) - 4) / 3;

    // check if the maindata fields match their regpatterns
    $all_regex_ok = true;
    if (!preg_match("/^[\w äöüÄÖÜß&,\._-]+$/", $_POST["inputArticle"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^[\w äöüÄÖÜß&,\._-]+$/", $_POST["inputDealer"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $_POST["inputDate"])) {
        $all_regex_ok = false;
    }

    // check if data in dynamic rows match their regex patterns
    // Build Rest of Table
    $i = 0;
    while ($i < $dynamicRows) {
        // Only rows that have inputUser and inputAmount set are being processed
        if ($_POST["inputUser".$i] != "" and $_POST["inputAmount".$i] != "") {
            if (!preg_match("/^[0-9]+$/", $_POST["inputUser".$i])) {
                $all_regex_ok = false;
            }
            if (!preg_match("/^[-]{0,1}[0-9]+[,\.]{0,1}[0-9]{0,2}$/", $_POST["inputAmount".$i])) {
                $all_regex_ok = false;
            }
            if (!preg_match("/^[\w äöüÄÖÜß&,\._-]*$/", $_POST["inputRemark".$i])) {
                $all_regex_ok = false;
            }
        }
        $i++;
    }
    unset($i);

    // End execution if regex not ok
    if (!$all_regex_ok) {
        echo "Please validate your inputs.";
        http_response_code(400);
        exit();
    }

    // Get retailer_id, defaults to 0 if retailer unknown
    $retailer_id = getRetailerID($_POST["inputDealer"]);

    // Get the informations to connect to database as editor
    $db_settings = get_db_login("admin");

    // connect to database and start a transaction
    $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]); 
    $db->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

    try {
        // create the retailer if it doesn't exist and query the new retailer_id
        if ($retailer_id == 0) {
            // Insert new retailer
            $sql = $db->prepare("INSERT INTO retailer(retailer) VALUES (?)");
            $sql->bind_param('s', $_POST["inputDealer"]);
            $sql->execute();
            $sql->free_result();

            // Get new retailer_id
            $sql = $db->prepare("SELECT retailer_id FROM retailer WHERE retailer = ? LIMIT 1");
            $sql->bind_param('s', $_POST["inputDealer"]);
            $sql->execute();
            $sql->bind_result($retailer_id);
            $sql->fetch();
            $sql->free_result();
        }

        // Get the next purchase_id from purchase
        $sql = $db->prepare("SELECT MAX(purchase_id)+1 AS next_id FROM dashboard");
        $sql->execute();
        $sql->bind_result($next_purchase_id);
        $sql->fetch();
        $sql->free_result();

        // insert the purchase
        $sql = $db->prepare("INSERT INTO purchase(purchase_id, article, date, retailer_id) VALUES (?, ?, ?, ?)");
        $sql->bind_param('issi', $next_purchase_id, $_POST["inputArticle"], $_POST["inputDate"], $retailer_id);
        $sql->execute();
        $sql->free_result();

        // add the contributions (dynamic rows)
        $i = 0;
        $next_contribution_id = 1;
        while ($i < $dynamicRows) {
            // Only rows that have inputUser and inputAmount set are being processed
            if ($_POST["inputUser".$i] != "" and $_POST["inputAmount".$i] != "") {

                // replace comma with point
                $clean_inputAmount = str_replace(",", ".", $_POST["inputAmount".$i]);

                $sql = $db->prepare("INSERT INTO contribution(purchase_id, contribution_id, user_id, amount, comment) VALUES (?, ?, ?, ?, ?)");
                $sql->bind_param('iiids', $next_purchase_id, $next_contribution_id, $_POST["inputUser".$i], $clean_inputAmount, $_POST["inputRemark".$i]);
                $sql->execute();
                $sql->free_result();
                $next_contribution_id++;
            }
            $i++;
        }

        // commit the changes to db
        $db->commit();

    } catch (Exception $ex) {
        $db->rollback();
        echo "Error during SQL Execution.";
        http_response_code(400);
    }
    
    $db->close();

?>
