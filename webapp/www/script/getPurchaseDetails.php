<?php
    if(!isset($_GET['purchase_id'])) {
        echo 'purchase_id not present';
        exit();
    }
    if (!preg_match("/^[0-9]+$/", $_GET['purchase_id'])) {
        echo 'purchase_id not valid';
        exit();
    }

    $purchase_id = $_GET['purchase_id'];
    $purchase_timestamp = "";
    $purchase_article = "";
    $purchase_date = "";
    $retailer_id = 0;
    $retailer = "";
    $contribution = [];

    // Get the informations to connect to database
    require("../../src/get_db_login.php");
    $db_settings = get_db_login("editor");

    try {
        // Connect to database
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);                  
        
        // get purchase main data
        $sql = "SELECT * FROM purchase WHERE purchase_id = ".$purchase_id." LIMIT 1";
        $res = $db->query($db->real_escape_string($sql));
        while ($row = $res->fetch_assoc()) {
            $purchase_timestamp = $row['timestamp'];
            $purchase_article = $row['article'];
            $purchase_date = $row['date'];
            $retailer_id = $row['retailer_id'];
        }

        // get purchase retailer
        $sql = "SELECT * FROM retailer WHERE retailer_id = ".$retailer_id." LIMIT 1";
        $res = $db->query($db->real_escape_string($sql));
        while ($row = $res->fetch_assoc()) {
            $retailer = $row['retailer'];
        }

        // get contribution
        $i = 0;
        $sql = "SELECT * FROM contribution LEFT JOIN user ON contribution.user_id = user.user_id WHERE purchase_id = ".$purchase_id." ORDER BY contribution_id ASC";
        $res = $db->query($db->real_escape_string($sql));
        while ($row = $res->fetch_assoc()) {
            $contribution[$i] = array(
                'timestamp' => $row['timestamp'],
                'username' => $row['username'],
                'amount' => $row['amount'],
                'comment' => $row['comment']
            );
            $i++;
        }
        unset($i);

        // Disconnect from Database
        $db->close();

    } catch (Exception $ex) {
        echo $ex->getMessage();
        exit();
    }

?>

<h1>Details zu Einkauf-ID: <?php echo $purchase_id; ?></h1>
<h2>Allgemein</h2>
<p>
    Erfasst: <?php echo $purchase_timestamp; ?><br />
    Artikel: <?php echo $purchase_article; ?><br />
    Datum: <?php echo $purchase_date; ?><br />
    Händler: <?php echo $retailer; ?>
</p>
<h2>Kostenverteilung</h2>
<table class="simpletable">
    <tr class="simpletable">
        <th class="simpletable">Erstellung</th>
        <th class="simpletable">Name</th>
        <th class="simpletable">Betrag</th>
        <th class="simpletable">Bemerkung</th>
    </tr>
    <?php
        foreach ($contribution as $contri) {
            echo "<tr class=\"simpletable\">\n";
            echo "<td class=\"simpletable\">".$contri["timestamp"]."</td>\n";
            echo "<td class=\"simpletable\">".$contri["username"]."</td>\n";
            echo "<td class=\"simpletable\">".$contri["amount"]." €</td>\n";
            echo "<td class=\"simpletable\">".$contri["comment"]."</td>\n";
            echo "</tr>\n";
        }
    ?>
</table>
<div class="edit-details" onclick="editPurchaseDetails(<?php echo $purchase_id; ?>)"><i class="material-icons">edit</i></div>
