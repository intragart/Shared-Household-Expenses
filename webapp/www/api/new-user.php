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
    
    // Get the informations to connect to database
    require("../../src/get_db_login.php");
    $db_settings = get_db_login("admin");

    // Get the settings for the used currency
    require_once("../../src/get_currency_settings.php");
    $currency = get_currency_settings();

    // suggested start value is shown in form and should be filled via database to that
    // the new user starts with no negative
    $suggested_start_value = "0.00";

    try {
        // Connect to database
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);                  
        
        // get the max user sum so that the new user can start with a difference of 0 to the other users
        $sql = $db->prepare("SELECT MAX(sum_user) FROM user_contribution");
        $sql->execute();
        $sql->bind_result($suggested_start_value);
        $sql->fetch();
        $sql->free_result();

        // Disconnect from Database
        $db->close();

    } catch (Exception $ex) {
        echo "Error while receiving suggested start value.";
        exit();
    }

    // set standard value if response from database was empty
    if (!isset($suggested_start_value)) {
        $suggested_start_value = "0.00";
    }

    // format start value
    $suggested_start_value = str_replace(".", $currency["currencyDecimal"], $suggested_start_value);

?>
<form class="simple-form" id="newUserForm" action="/api/insert-user.php" method="post">
    <div class="form-row">
        <h1>Neuen Nutzer erstellen</h1>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputUsername">Nutzername:</label></div>
        <div class="input-80"><input type="text" id="inputUsername" name="inputUsername" pattern="^[a-z]+$" value="" required></div>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputPrettyName">Anzeigename:</label></div>
        <div class="input-80"><input type="text" id="inputPrettyName" name="inputPrettyName" pattern="^[\w äöüÄÖÜß,-]*$" value="" required></div>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputStartValue">Startwert (<?php echo $currency["currencyCode"]; ?>):</label></div>
        <div class="input-80"><input type="text" id="inputStartValue" name="inputStartValue" pattern="^[-]{0,1}[0-9]+[,\.]{0,1}[0-9]{0,2}$" value="<?php echo $suggested_start_value; ?>" required></div>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputAccountStatus">Status:</label></div>
        <div class="input-80">
            <select id="inputAccountStatus" name="inputAccountStatus">
                <option value="READ_WRITE" selected>READ_WRITE</option>
                <option value="READ_ONLY">READ_ONLY</option>
                <option value="LOCKED">LOCKED</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="edit-details margin-top-15 submitBtn" onclick="submitForm(newUserForm)"><i class="material-icons">save</i></div>
    </div>
</form>
