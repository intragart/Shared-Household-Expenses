<!--
    Shared Household Expenses
    Copyright (C) 2023  Marco Weingart

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
    require("../src/get_db_login.php");
    $db_settings = get_db_login("admin");

    // Get the settings for the used currency
    require_once("../src/get_currency_settings.php");
    $currency = get_currency_settings();

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Einstellungen - Shared Household Expenses</title>
        <link rel="icon" type="image/x-icon" href="/content/favicon/favicon-228.png">
        <link type="text/css" rel="stylesheet" href="/style/css/general.css">
        <link type="text/css" rel="stylesheet" href="/style/css/navigation.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form-structure.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form-detailed.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form-simple.css">
        <link type="text/css" rel="stylesheet" href="/style/css/table.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <meta charset="UTF-8">
        <meta name="description" content="Settings for application and users.">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="/script/checkForm.js" type="text/javascript" defer></script>
        <script src="/script/submitForm.js" type="text/javascript" defer></script>
        <script src="/script/spanFullscreen.js" type="text/javascript" defer></script>
        <script src="/script/settings.js" type="text/javascript" defer></script>
    </head>
    <body onload="startCheckForm('globalSettings')">
        <div class="left-wrapper">
            <?php include '../src/navigation.php'; ?>
        </div>
        <div class="right-wrapper">
            <div id="content">
                <h1>Einstellungen</h1>
                <hr class="sep">
                <div>
                    <form action="/api/set-global-settings.php" method="post" autocomplete="on" id="globalSettings" class="detailed-form" novalidate>
                        <div class="form-row">
                            <h2>Allgemeine Einstellungen</h2>
                            <p>
                                Gültig für die gesamte Anwendung.
                            </p>
                        </div>
                        <div class="form-row">
                            <div class="input-25 input-l">
                                <input type="text" id="currencyCode" name="currencyCode" pattern="^[A-Z]{3}$" value="<?php echo $currency["currencyCode"]; ?>" required>
                                <span class="bar"></span>
                                <label for="currencyCode">Währungskürzel</label>
                            </div>
                            <div class="input-25 input-l">
                                <input type="text" id="currencySymbol" name="currencySymbol" pattern="^.{1}$" value="<?php echo $currency["currencySymbol"]; ?>" required>
                                <span class="bar"></span>
                                <label for="currencySymbol">Währungszeichen</label>
                            </div>
                            <div class="input-25 input-l">
                                <input type="text" id="currencyDecimal" name="currencyDecimal" pattern="^[,\.]{1}$" value="<?php echo $currency["currencyDecimal"]; ?>" required>
                                <span class="bar"></span>
                                <label for="currencyDecimal">Dezimaltrennung</label>
                            </div>
                            <div class="input-25 input-l">
                                <select id="currencyPosition" name="currencyPosition" required>
                                    <option <?php if ($currency["currencyPosition"] == "after") { echo "selected"; } ?> value="after">Dahinter</option>
                                    <option <?php if ($currency["currencyPosition"] == "before") { echo "selected"; } ?> value="before">Davor</option>
                                </select>
                                <span class="bar"></span>
                                <label for="currencyPosition">Währungsposition</label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="input-100 input-r">
                                <input id="submitBtn" class="submit btn btn-positive submitBtn" type="button" value="Übernehmen" onclick="submitForm(globalSettings)" disabled>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <form autocomplete="off" id="updateUsers" class="detailed-form" novalidate>
                        <div class="form-row">
                            <h2>Nutzereinstellungen</h2>
                        </div>
                        <div class="form-row">
                            <?php
                                try {
                                    // connect to database
                                    $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);

                                    // Get table data
                                    $select_statement = "SELECT user_id, username, pretty_name, start_value, account_status FROM user WHERE account_status <> 'DEACTIVATED'";
                                    $res = $db->query($select_statement);
                            
                                    // Create Table Head if data has been received
                                    if ($res->num_rows > 0) {
                                        echo "<table class=\"simpletable borderless\">\n";
                                        echo "<tr class=\"simpletable\">\n";
                                        echo "<th class=\"simpletable borderless\">Nutzername</th>\n";
                                        echo "<th class=\"simpletable borderless\">Anzeigename</th>\n";
                                        echo "<th class=\"simpletable borderless\">Startwert</th>\n";
                                        echo "<th class=\"simpletable borderless\">Status</th>\n";
                                        echo "<th class=\"simpletable borderless\"></th>\n";
                                        echo "</tr>\n";
                                    } else {
                                        echo "<p>No Data.</p>";
                                    }
                            
                                    // Display the received data in table
                                    while ($row = $res->fetch_assoc()) {
                                        echo "<tr class='simpletable' id='user-".$row['user_id']."'>";
                                        echo "<td class=\"simpletable borderless\">".$row['username']."</td>";
                                        echo "<td class=\"simpletable borderless\">".$row['pretty_name']."</td>";
                                        echo "<td class=\"simpletable borderless\">";
                                        if ($currency["currencyPosition"] == "before") { echo $currency["currencySymbol"]." "; }
                                        echo str_replace(".", $currency["currencyDecimal"], $row['start_value']);
                                        if ($currency["currencyPosition"] == "after") { echo " ".$currency["currencySymbol"]; }
                                        echo "</td>";
                                        echo "<td class=\"simpletable borderless\">".$row['account_status']."</td>";
                                        echo "<td class=\"simpletable borderless\"><div class=\"edit-details\" onclick=\"editUser(".$row['user_id'].")\"><i class=\"material-icons submitBtn\">edit</i></div></td>";
                                        echo "</tr>";
                                    }
                            
                                    // Close Table if data has been received
                                    if ($res->num_rows > 0) {
                                        echo "</table>";
                                    }

                                    // close db connection
                                    if (isset($db)) {
                                        $db->close();
                                    }
                            
                                } catch (Exception $ex) {
                                    echo "Error during SQL Execution.";
                                }
                            ?>
                        </div>
                        <div class="form-row">
                            <div class="input-100 input-r">
                                <input id="BtnNewUser" class="submit btn" type="button" value="Neuer Nutzer" onclick="newUser()">
                            </div>
                        </div>
                    </form>
                </div>
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
