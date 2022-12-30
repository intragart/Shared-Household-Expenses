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

    if(!isset($_GET['user_id'])) {
        echo 'user_id not present';
        exit();
    }
    if (!preg_match("/^[0-9]+$/", $_GET['user_id'])) {
        echo 'user_id not valid';
        exit();
    }

    $user_id = $_GET['user_id'];

    // Get the informations to connect to database
    require("../../src/get_db_login.php");
    $db_settings = get_db_login("admin");

    // Get the settings for the used currency
    require_once("../../src/get_currency_settings.php");
    $currency = get_currency_settings();

    try {
        // Connect to database
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);                  
        
        // get user data
        $sql = "SELECT username, pretty_name, start_value, account_status FROM user WHERE user_id = ".$user_id." LIMIT 1";
        $res = $db->query($db->real_escape_string($sql));
        while ($row = $res->fetch_assoc()) {
            $username = $row['username'];
            $pretty_name = $row['pretty_name'];
            $start_value = str_replace(".", $currency["currencyDecimal"], $row['start_value']);
            $account_status = $row['account_status'];
        }

        // Disconnect from Database
        $db->close();

    } catch (Exception $ex) {
        echo $ex->getMessage();
        exit();
    }

?>
<form class="simple-form" id="editUserForm" action="/api/update-user.php" method="post">
    <div class="form-row">
        <h1>Bearbeiten, Nutzer-ID: <?php echo $user_id; ?></h1>
        <input type="hidden" id="userId" name="userId" value="<?php echo $user_id; ?>">
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputUsername">Nutzername:</label></div>
        <div class="input-80"><input type="text" id="inputUsername" name="inputUsername" pattern="^[a-z]+$" value="<?php echo $username; ?>" required></div>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputPrettyName">Anzeigename:</label></div>
        <div class="input-80"><input type="text" id="inputPrettyName" name="inputPrettyName" pattern="^[\w äöüÄÖÜß,-]*$" value="<?php echo $pretty_name; ?>" required></div>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputStartValue">Startwert (<?php echo $currency["currencyCode"]; ?>):</label></div>
        <div class="input-80"><input type="text" id="inputStartValue" name="inputStartValue" pattern="^[-]{0,1}[0-9]+[,\.]{0,1}[0-9]{0,2}$" value="<?php echo $start_value; ?>" required></div>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputAccountStatus">Status:</label></div>
        <div class="input-80">
            <select id="inputAccountStatus" name="inputAccountStatus">
                <option value="READ_WRITE" <?php if ($account_status == "READ_WRITE") { echo "selected"; } ?>>READ_WRITE</option>
                <option value="READ_ONLY" <?php if ($account_status == "READ_ONLY") { echo "selected"; } ?>>READ_ONLY</option>
                <option value="LOCKED" <?php if ($account_status == "LOCKED") { echo "selected"; } ?>>LOCKED</option>
                <option value="DEACTIVATED" <?php if ($account_status == "DEACTIVATED") { echo "selected"; } ?>>DEACTIVATED</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="edit-details margin-top-15 submitBtn" onclick="submitForm(editUserForm)"><i class="material-icons">save</i></div>
    </div>
</form>
