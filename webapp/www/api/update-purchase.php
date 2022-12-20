<?php 
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

    function getRetailerID ($retailer_name) {
        // Get the informations to connect to database as viewer
        $db_settings = get_db_login("viewer");

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
    $dynamicRows = (count($_POST) - 5) / 4;

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
    if (!preg_match("/^[0-9,]*$/", $_POST["deleteContributions"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^[0-9]+$/", $_POST["purchaseId"])) {
        $all_regex_ok = false;
    }

    // check if data in dynamic rows match their regex patterns
    // Build Rest of Table
    $i = 0;
    $z = 0;
    while ($z < $dynamicRows) {
        // check if current id for dynamicRows is existend. They may not be existend
        // when the user deleted a new line within the gui
        if (isset($_POST["inputUser".$i])) {
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
                if (!preg_match("/^([0-9]+|new){1}$/", $_POST["contributionId".$i])) {
                    $all_regex_ok = false;
                }
            }
            $z++;
        }
        $i++;
    }
    unset($z);
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
    $db_settings = get_db_login("editor");

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

        // update the purchase
        $sql = $db->prepare("UPDATE purchase SET article = ?, date = ?, retailer_id = ? WHERE purchase_id = ?");
        $sql->bind_param('ssii', $_POST["inputArticle"], $_POST["inputDate"], $retailer_id, $_POST['purchaseId']);
        $sql->execute();
        $sql->free_result();

        // delete contributions (if any)
        $deleteContributions = explode(",", $_POST["deleteContributions"]);
        $sql = $db->prepare("DELETE FROM contribution WHERE purchase_id = ? AND contribution_id = ?");
        foreach ($deleteContributions as $ContributionIdToDelete) {
            if ($ContributionIdToDelete != "") {
                $sql->bind_param('ii', $_POST['purchaseId'], $ContributionIdToDelete);
                $sql->execute();
            }
        }
        $sql->free_result();

        // Get the next free contribution_id for this purchase_id from contributions
        $next_contribution_id = 0;
        $sql = $db->prepare("SELECT MAX(contribution_id)+1 AS next_id FROM contribution WHERE purchase_id = ?");
        $sql->bind_param('i', $_POST['purchaseId']);
        $sql->execute();
        $sql->bind_result($next_contribution_id);
        $sql->fetch();
        $sql->free_result();
        if (!$next_contribution_id) {
            $next_contribution_id = 1;
        }

        // update or add the contributions (dynamic rows) which have not been marked to be deleted
        $i = 0;
        $z = 0;
        while ($z < $dynamicRows) {
            // check if current id for dynamicRows is existend. They may not be existend
            // when the user deleted a new line within the gui
            if (isset($_POST["inputUser".$i])) {

                // Only rows that have inputUser and inputAmount set are being processed
                if ($_POST["inputUser".$i] != "" and $_POST["inputAmount".$i] != "") {

                    // check if this is a completely new contribution or an update for an existing contribution
                    if ($_POST["contributionId".$i] == "new") {
                        // completely new contribution
                        $sql = $db->prepare("INSERT INTO contribution(purchase_id, contribution_id, user_id, amount, comment) VALUES (?, ?, ?, ?, ?)");
                        $sql->bind_param('iiids', $_POST['purchaseId'], $next_contribution_id, $_POST["inputUser".$i], $_POST["inputAmount".$i], $_POST["inputRemark".$i]);
                        $sql->execute();
                        $sql->free_result();
                        $next_contribution_id++;

                    } else {
                        // might be an update, check if deleted first
                        if (!in_array($_POST["contributionId".$i], $deleteContributions)) {
                            // contribution id is not marked to be deleted, update can be applied
                            $sql = $db->prepare("UPDATE contribution SET user_id = ?, amount = ?, comment = ? WHERE purchase_id = ? AND contribution_id = ?");
                            $sql->bind_param('idsii', $_POST["inputUser".$i], $_POST["inputAmount".$i], $_POST["inputRemark".$i], $_POST['purchaseId'], $_POST["contributionId".$i]);
                            $sql->execute();
                            $sql->free_result();
                        }
                    }
                }
                $z++;
            }
            $i++;
        }
        unset($i);
        unset($z);

        // commit the changes to db
        $db->commit();

    } catch (Exception $ex) {
        $db->rollback();
        echo "Error during SQL Execution.\n".$ex;
        http_response_code(400);
    }
    
    $db->close();

?>