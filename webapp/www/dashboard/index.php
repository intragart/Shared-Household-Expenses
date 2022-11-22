<!--
    Shared Household Expenses
    Copyright (C) 2022  Marco Weingart

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
-->
<?php
    // Get the informations to connect to database
    require("../../src/get_db_login.php");
    $db_settings = get_db_login("viewer");
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Dashboard - Shared Household Expenses</title>
        <link rel="icon" type="image/x-icon" href="/content/favicon/favicon-228.png">
        <link type="text/css" rel="stylesheet" href="/style/css/general.css">
        <link type="text/css" rel="stylesheet" href="/style/css/navigation.css">
        <link type="text/css" rel="stylesheet" href="/style/css/cards.css">
        <link type="text/css" rel="stylesheet" href="/style/css/table.css">
        <meta charset="UTF-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Pragma" content="no-cache"> <!-- TODO: Entfernen -->
        <script src="/script/tableDetails.js" type="text/javascript" defer></script>
    </head>
    <body>
        <div class="left-wrapper">
            <?php include '../../src/navigation.php'; ?>
        </div>
        <div class="right-wrapper">
            <div id="content">
                <h1>Dashboard</h1>
                <hr class="sep">
                <h2>Differenz zum größten Anteil</h2>
                <?php
                    try {
                        // Connect to database and select the dashboard data
                        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);     
                        
                        // get the sum of all user contributions from active users
                        $sql = "SELECT SUM(sum_contributions) AS sum_all FROM shared_household_expenses.user_contribution WHERE account_active != 'DEACTIVATED'";
                        $res = $db->query($sql);
                        $contribution_sum = 0;
                        if ($res->num_rows > 0) {
                            $contribution_sum = $res->fetch_row()[0];
                        }

                        // get the maximum sum of all active users
                        $sql = "SELECT MAX(sum_user) AS max_user_sum FROM shared_household_expenses.user_contribution WHERE account_active != 'DEACTIVATED'";
                        $res = $db->query($sql);
                        $contribution_max = 0;
                        if ($res->num_rows > 0) {
                            $contribution_max = $res->fetch_row()[0];
                        }

                        // get each active users contribution
                        $sql = "SELECT * FROM shared_household_expenses.user_contribution WHERE account_active != 'DEACTIVATED'";
                        $res = $db->query($sql);

                        // Create Cards if data has been received
                        if ($res->num_rows > 0) {
                            echo "<div class=\"card-holder\">\n";
                            while ($row = $res->fetch_assoc()) {
                                $difference = $contribution_max - $row['sum_user'];
                                if ($difference == 0) {
                                    echo "<div class=\"saldo saldo-max\">\n";
                                } else {
                                    echo "<div class=\"saldo\">\n";
                                }
                                echo "<div class=\"saldo-sum\">\n";
                                echo number_format($difference, 2)." €\n";
                                echo "</div>\n";
                                echo "<div class=\"saldo-name\">\n";
                                echo $row['username']."\n";
                                echo "</div>\n";
                                echo "</div>\n";
                            }
                            echo "</div>\n";
                        } else {
                            echo "<p>No Data.</p>";
                        }

                        $db->close();
                    } catch (Exception $ex) {
                        echo "<p>Error while querying data from database.</p>";
                    }
                ?>
                <h2>Ausgaben der letzten 30 Tage</h2>
                    <?php
                        try {
                            // Connect to database and select the dashboard data
                            $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);                  
                            $sql = "SELECT * FROM shared_household_expenses.dashboard WHERE date >= DATE(NOW()) - INTERVAL 30 DAY";
                            $res = $db->query($sql);

                            // Create Table Head if data has been received
                            if ($res->num_rows > 0) {
                                echo "<table class=\"maintable\">\n";
                                echo "<tr class=\"table-head\">\n";
                                echo "<th>Artikel</th>\n";
                                echo "<th>Händler</th>\n";
                                echo "<th>Datum</th>\n";
                                echo "<th>Beteiligte</th>\n";
                                echo "<th>Gesamt</th>\n";
                                echo "</tr>\n";
                            } else {
                                echo "<p>No Data.</p>";
                            }

                            // Display the received data in table
                            while ($row = $res->fetch_assoc()) {
                                echo "<tr class='table-row' id='purchase-".$row['purchase_id']."' onclick='showHideTableDetails(".$row['purchase_id'].");'>";
                                echo "<td>".$row['article']."</td>";
                                echo "<td>".$row['retailer']."</td>";
                                echo "<td>".$row['date']."</td>";
                                echo "<td>".$row['contributor']."</td>";
                                echo "<td>".$row['amount']." €</td>";
                                echo "</tr>";
                                echo '<tr class="table-details hidden" id="details-'.$row['purchase_id'].'">';
                                echo ' <td colspan="6" id="details-content-'.$row['purchase_id'].'">';
                                echo '</td>';
                                echo '</tr>';
                            }

                            // Close Table
                            $db->close();

                            // Close Table if data has been received
                            if ($res->num_rows > 0) {
                                echo "</table>";
                            }

                        } catch (Exception $ex) {
                            echo "<p>Daten konnten nicht abgerufen werden</p>";
                        }
                    ?>
                <br />
            </div>
        </div>
    </body>
</html>