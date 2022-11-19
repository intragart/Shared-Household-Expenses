<?php
    // Get the informations to connect to database
    require("../../src/get_db_login.php");
    $db_settings = get_db_login("viewer");

    try {
        // Connect to database and select the contribution details data
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);                  
        $sql = "SELECT username, amount, currency, comment
        FROM dashboard_detail
        WHERE purchase_id = ".$_GET['index'];
        $res = $db->query($sql);

        // Display the received data in table
        while ($row = $res->fetch_assoc()) {
            echo $row['username'].': '.$row['amount'].' '.$row['currency'];
            if ($row['comment'] != "") {
                echo " - ".$row['comment'];
            }
            echo '<br />';
        }
        $db->close();
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
?>