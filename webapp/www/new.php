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

    try {
        // Connect to database and select the article list
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);     
        
        // Select the article list
        $sql = "SELECT article FROM article_list";
        $article_list = $db->query($sql);

        // Select all retailers
        $sql = "SELECT * FROM retailer ORDER BY retailer";
        $retailer_list = $db->query($sql);

        // Select active users
        $sql = "SELECT user_id, username, pretty_name FROM user_contribution WHERE account_status != 'DEACTIVATED' ORDER BY username ASC";
        $user_list = $db->query($sql);

        $db->close();
    } catch (Exception $ex) {
        $article_list = false;
        $retailer_list = false;
        $user_list = false;
    }
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Neuer Eintrag - Shared Household Expenses</title>
        <link rel="icon" type="image/x-icon" href="/content/favicon/favicon-228.png">
        <link type="text/css" rel="stylesheet" href="/style/css/general.css">
        <link type="text/css" rel="stylesheet" href="/style/css/navigation.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form-structure.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form-detailed.css">
        <meta charset="UTF-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Pragma" content="no-cache"> <!-- TODO: Entfernen -->
        <script src="/script/checkForm.js" type="text/javascript" defer></script>
        <script src="/script/addRemoveRowInNew.js" type="text/javascript" defer></script>
        <script src="/script/resetToAutofocus.js" type="text/javascript" defer></script>
        <script src="/script/submitForm.js" type="text/javascript" defer></script>
    </head>
    <body onload="startCheckForm('newForm')">
        <div class="left-wrapper">
            <?php include '../src/navigation.php'; ?>
        </div>
        <div class="right-wrapper">
            <div id="content">
                <h1>Neuen Eintrag erfassen</h1>
                <hr class="sep">
                <form action="/api/insert-purchase.php" target="/dashboard" id="newForm" method="post" autocomplete="on" class="detailed-form" novalidate>
                    <div class="form-row">
                        <h2>Allgemein</h2>
                    </div>
                    <div class="form-row">
                        <div class="input-100 input-l">
                            <datalist id="article-list">
                                <?php
                                    if (!($article_list === false)) {
                                        // Fill in the autocomplete data
                                        while ($row = $article_list->fetch_assoc()) {
                                            echo "<option value=\"".$row['article']."\" />\n";
                                        }
                                    }
                                ?>
                            </datalist>
                            <input type="text" list="article-list" id="inputArticle" name="inputArticle" pattern="^[\w äöüÄÖÜß&,\._-]+$" required autofocus>
                            <span class="bar"></span>
                            <label for="inputArticle">Artikel</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-70 input-l">
                            <datalist id="retailer-list">
                                <?php
                                    if (!($retailer_list === false)) {
                                        // Fill in the autocomplete data
                                        while ($row = $retailer_list->fetch_assoc()) {
                                            echo "<option value=\"".$row['retailer']."\" dbID=\"".$row['retailer_id']."\" />\n";
                                        }
                                    }
                                ?>
                            </datalist>
                            <input type="text" list="retailer-list" id="inputDealer" name="inputDealer" pattern="^[\w äöüÄÖÜß&,\._-]+$" required>
                            <span class="bar"></span>
                            <label for="inputDealer">Händler</label>
                        </div>
                        <div class="input-30 input-l">
                            <input type="date" id="inputDate" name="inputDate" pattern="^[0-9]{4}-[0-9]{2}-[0-9]{2}$" value="<?php echo date("Y-m-d"); ?>" required>
                            <span class="bar"></span>
                            <label for="inputDate">Datum</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <h2>Kostenverteilung</h2>
                    </div>
                    <div class="form-row" id="row0">
                        <div class="input-50 input-l">                          
                            <select id="inputUser0" name="inputUser0" required>
                                <option disabled="" selected="" value="" hidden=""></option>
                                <?php
                                    // Store valid display_names for regex later on
                                    $display_names = array();
                                    $i = 0;

                                    if (!($user_list === false)) {
                                        // Fill in the autocomplete data
                                        while ($row = $user_list->fetch_assoc()) {
                                            // Show pretty_name if availabile, otherwise username
                                            $display_name = "";
                                            if ($row['pretty_name'] != "") {
                                                $display_name = $row['pretty_name'];
                                            } else {
                                                $display_name = $row['username'];
                                            }
                                            $display_names[$i] = $display_name;
                                            $i++;
                                            echo "<option value=\"".$row['user_id']."\">".$display_name."</option>\n";
                                        }
                                    }

                                    unset($i);
                                ?>
                            </select>
                            <span class="bar"></span>
                            <label for="inputUser0">Name</label>
                        </div>
                        <div class="input-20 input-l">
                            <input type="text" id="inputAmount0" name="inputAmount0" pattern="^[-]{0,1}[0-9]+[,\.]{0,1}[0-9]{0,2}$" required>
                            <span class="bar"></span>
                            <label for="inputAmount0">Betrag (<?php echo $currency["currencyCode"]; ?>)</label>
                        </div>
                        <div class="input-30 input-l">
                            <input type="text" id="inputRemark0" name="inputRemark0" pattern="^[\w äöüÄÖÜß&,\._-]*$" required>
                            <span class="bar"></span>
                            <label for="inputRemark0">Bemerkung</label>
                        </div>
                    </div>
                    <span id="new-row-above"></span>
                    <div class="form-row">
                        <br />
                        <div class="input-20 input-l">
                            <input class="btn" id="new-row" type="button" value="+">
                        </div>
                        <div class="input-80 input-r">
                            <input id="submitBtn" class="submit btn btn-positive submitBtn" type="button" value="Senden" onclick="submitForm(newForm)" disabled>
                            <input class="btn" type="reset" value="Reset">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
