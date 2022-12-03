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

    $php_fromdate = date("Y-m-d", strtotime("-30 day", time()));
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
        <link type="text/css" rel="stylesheet" href="/style/css/form-structure.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form-simple.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <meta charset="UTF-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Pragma" content="no-cache"> <!-- TODO: Entfernen -->
        <script src="/script/tableDetails.js" type="text/javascript" defer></script>
        <script src="/script/spanFullscreen.js" type="text/javascript" defer></script>
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
                        // get table html
                        require("../script/getTable.php");
                    ?>
                <br />
            </div>
        </div>
        <span id="fullscreen-message"> 
            <div id="block-background" onclick="removeSpanFullscreen()"></div>
            <div id="message-body">
                <div id="close-fullscreen-message"><i class="material-icons disable-select" onclick="removeSpanFullscreen()">close</i></div>
                <span id="message-content"></span> <!-- innerHTML is used to show fullscreen content using javascript -->
            </div>
        </span>
    </body>
</html>
