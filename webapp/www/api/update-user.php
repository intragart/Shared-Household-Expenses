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

    require("../../src/get_db_login.php");

    // check if the maindata fields match their regpatterns
    $all_regex_ok = true;
    if (!preg_match("/^[0-9]+$/", $_POST["userId"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^[a-z]+$/", $_POST["inputUsername"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^[\w äöüÄÖÜß,-]*$/", $_POST["inputPrettyName"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^[-]{0,1}[0-9]+[,\.]{0,1}[0-9]{0,2}$/", $_POST["inputStartValue"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/(^READ_ONLY$|^READ_WRITE$|^LOCKED$|^DEACTIVATED$){1}/", $_POST["inputAccountStatus"])) {
        $all_regex_ok = false;
    }

    // End execution if regex not ok
    if (!$all_regex_ok) {
        echo "Please validate your inputs.";
        http_response_code(400);
        exit();
    }

    // Get the informations to connect to database as editor
    $db_settings = get_db_login("admin");

    // connect to database and start a transaction
    $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);

    // disable autocommit and set isolation level to serialize
    $db->autocommit(false);
    $db->query("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");
    
    $db->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

    try {
        // set pretty_name to NULL if empty
        $pretty_name = $_POST["inputPrettyName"];
        if ($pretty_name == "") {
            $pretty_name = null;
        }

        // format the start value
        $start_value = str_replace(",", ".", $_POST["inputStartValue"]);

        // update the user
        $sql = $db->prepare("UPDATE user SET username = ?, pretty_name = ?, start_value = ?, account_status = ? WHERE user_id = ?");
        $sql->bind_param('ssdsi', $_POST["inputUsername"], $pretty_name, $start_value, $_POST['inputAccountStatus'], $_POST['userId']);
        $sql->execute();
        $sql->free_result();

        // commit the changes to db
        $db->commit();

    } catch (Exception $ex) {
        $db->rollback();
        echo "Error during SQL Execution.\n";
        http_response_code(400);
    }
    
    $db->close();

?>
