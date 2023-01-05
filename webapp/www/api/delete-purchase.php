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
    if (!preg_match("/^[0-9]+$/", $_POST["identifier"])) {
        // End execution if regex not ok
        echo "Please validate your inputs.";
        http_response_code(400);
        exit();
    }

    require("../../src/get_db_login.php");
    $db_settings = get_db_login("admin");

    // connect to database and start a transaction
    $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);
    
    // disable autocommit and set isolation level to serialize
    $db->autocommit(false);
    $db->query("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");
    
    $db->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

    try {
        // delete contributions (if any)
        $sql = $db->prepare("DELETE FROM contribution WHERE purchase_id = ?");
        $sql->bind_param('i', $_POST['identifier']);
        $sql->execute();
        $sql->free_result();

        // delete purchase
        $sql = $db->prepare("DELETE FROM purchase WHERE purchase_id = ?");
        $sql->bind_param('i', $_POST['identifier']);
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
