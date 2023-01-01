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
    $user_list = [];

    // Get the informations to connect to database
    require("../../src/get_db_login.php");
    $db_settings = get_db_login("admin");

    // Get the settings for the used currency
    require_once("../../src/get_currency_settings.php");
    $currency = get_currency_settings();

    try {
        // Connect to database
        $db = new MySQLi($db_settings[0], $db_settings[1], $db_settings[2], $db_settings[3], $db_settings[4], $db_settings[5]);                  
        
        // get purchase main data
        $sql = "SELECT * FROM purchase WHERE purchase_id = ".$purchase_id." LIMIT 1";
        $res = $db->query($db->real_escape_string($sql));
        while ($row = $res->fetch_assoc()) {
            $purchase_timestamp_created = $row['timestamp_created'];
            $purchase_timestamp_updated = $row['timestamp_updated'];
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
        $sql = "SELECT * FROM contribution WHERE purchase_id = ".$purchase_id." ORDER BY contribution_id ASC";
        $res = $db->query($db->real_escape_string($sql));
        while ($row = $res->fetch_assoc()) {
            $contribution[$i] = array(
                'contribution_id' => $row['contribution_id'],
                'timestamp_created' => $row['timestamp_created'],
                'timestamp_updated' => $row['timestamp_updated'],
                'user_id' => $row['user_id'],
                'amount' => $row['amount'],
                'comment' => $row['comment']
            );
            $i++;
        }
        unset($i);

        // Select the article list
        $sql = "SELECT article FROM article_list";
        $article_list = $db->query($sql);

        // Select all retailers
        $sql = "SELECT * FROM retailer ORDER BY retailer";
        $retailer_list = $db->query($sql);

        // Select active users
        $sql = "SELECT user_id, username, pretty_name FROM user_contribution WHERE account_status != 'DEACTIVATED' ORDER BY username ASC";
        $res = $db->query($sql);
        while ($row = $res->fetch_assoc()) {
            $user_list[$row['user_id']] = array(
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'pretty_name' => $row['pretty_name']
            );
        }

        // Disconnect from Database
        $db->close();

    } catch (Exception $ex) {
        echo "Error while receiving Purchase Data.";
        exit();
    }

?>
<form class="simple-form" id="editPurchaseForm" action="/api/update-purchase.php" delete-action="/api/delete-purchase.php" method="post">
    <div class="form-row">
        <h1>Bearbeiten, Einkauf-ID: <?php echo $purchase_id; ?></h1>
        <h2>Allgemein</h2>
        <input type="hidden" id="purchaseId" name="purchaseId" value="<?php echo $purchase_id; ?>">
        <input type="hidden" id="deleteContributions" name="deleteContributions" value="">
    </div>
    <div class="form-row">
        <div class="input-20">Erstellung:</div>
        <div class="input-80"><?php echo $purchase_timestamp_created; ?></div>
    </div>
    <div class="form-row">
        <div class="input-20">Aktualisiert:</div>
        <div class="input-80"><?php echo $purchase_timestamp_updated; ?></div>
    </div>
    <div class="form-row">
        <datalist id="article-list">
            <?php
                // Fill in the autocomplete data
                while ($row = $article_list->fetch_assoc()) {
                    echo "<option value=\"".$row['article']."\" />\n";
                }
            ?>
        </datalist>
        <div class="input-20"><label for="inputArticle">Artikel:</label></div>
        <div class="input-80"><input type="text" list="article-list" id="inputArticle" name="inputArticle" pattern="^[\w äöüÄÖÜß&,\._-]+$" value="<?php echo $purchase_article; ?>" required></div>
    </div>
    <div class="form-row">
        <div class="input-20"><label for="inputDate">Datum:</label></div>
        <div class="input-80"><input type="date" id="inputDate" name="inputDate" pattern="^[0-9]{4}-[0-9]{2}-[0-9]{2}$" value="<?php echo $purchase_date; ?>" required></div>
    </div>
    <div class="form-row">
        <datalist id="retailer-list">
            <?php
                // Fill in the autocomplete data
                while ($row = $retailer_list->fetch_assoc()) {
                    echo "<option value=\"".$row['retailer']."\" dbID=\"".$row['retailer_id']."\" />\n";
                }
            ?>
        </datalist>
        <div class="input-20"><label for="inputDealer">Händler:</label></div>
        <div class="input-80"><input type="text" list="retailer-list" id="inputDealer" name="inputDealer" pattern="^[\w äöüÄÖÜß&,\._-]+$" value="<?php echo $retailer; ?>" required></div>
    </div>
    <div class="form-row">
        <h2>Kostenverteilung</h2>
    </div>
    <div class="form-row">
        <table class="simpletable borderless">
            <tbody id="editPurchaseTable">
                <tr class="simpletable">
                    <th class="simpletable borderless">Erstellung</th>
                    <th class="simpletable borderless">Name</th>
                    <th class="simpletable borderless">Betrag (<?php echo $currency["currencyCode"]; ?>)</th>
                    <th class="simpletable borderless">Bemerkung</th>
                </tr>
                <?php

                    // Building the base HTML for each contribution data row
                    $contrib_html =  "<!-- <tr class=\"simpletable\" id=\"row__\">\n";
                    $contrib_html = $contrib_html."<td class=\"simpletable borderless\">_timestamp_</td>\n";
                    $contrib_html = $contrib_html."<input type=\"hidden\" id=\"contributionId__\" name=\"contributionId__\" value=\"_contribution_id_\">\n";
                    $contrib_html = $contrib_html."<td class=\"simpletable borderless\">\n";
                    $contrib_html = $contrib_html."<select id=\"inputUser__\" name=\"inputUser__\" required>\n";
                    $contrib_html = $contrib_html."<option selected value></option>\n";
                    // add select options
                    foreach ($user_list as $account) {
                        // Show pretty_name if availabile, otherwise username
                        $display_name = "";
                        if ($account['pretty_name'] != "") {
                            $display_name = $account['pretty_name'];
                        } else {
                            $display_name = $account['username'];
                        }
                        $contrib_html = $contrib_html."<option value=\"".$account['user_id']."\"";
                        $contrib_html = $contrib_html.">".$display_name."</option>\n";
                    }
                    $contrib_html = $contrib_html."</select>\n";
                    $contrib_html = $contrib_html."</td>\n";
                    $contrib_html = $contrib_html."<td class=\"simpletable borderless\">\n";
                    $contrib_html = $contrib_html."<input style=\"width: 90px;\" type=\"text\" id=\"inputAmount__\" name=\"inputAmount__\" pattern=\"^[-]{0,1}[0-9]+[,\.]{0,1}[0-9]{0,2}$\" value=\"_amount_\" required>\n";
                    $contrib_html = $contrib_html."</td>\n";
                    $contrib_html = $contrib_html."<td class=\"simpletable borderless\">\n";
                    $contrib_html = $contrib_html."<input style=\"width: 100%;\" type=\"text\" id=\"inputRemark__\" name=\"inputRemark__\" pattern=\"^[\w äöüÄÖÜß&,\._-]*$\" value=\"_comment_\" required>\n";
                    $contrib_html = $contrib_html."</td>\n";
                    $contrib_html = $contrib_html."<td class=\"simpletable borderless\">\n";
                    $contrib_html = $contrib_html."<div onclick=\"toggleDeletionOrDeleteContribution(__)\"><i class=\"material-icons edit-details\">remove</i></div>";
                    $contrib_html = $contrib_html."</td>\n";
                    $contrib_html = $contrib_html."</tr>\n -->";

                    // Insert each contribution to this purchase from the database
                    // and prefill the form
                    $z = 0;
                    foreach ($contribution as $contri) {

                        // cch = current_contrib_html
                        $cch = $contrib_html;
                        
                        // replace placeholder with actual data
                        $cch = str_replace("<!-- ", "", $cch);
                        $cch = str_replace(" -->", "", $cch);
                        $cch = str_replace("__", $z, $cch);
                        $cch = str_replace("_timestamp_", $contri["timestamp_created"], $cch);
                        $cch = str_replace("_contribution_id_", $contri["contribution_id"], $cch);
                        $cch = str_replace("_amount_", str_replace(".", $currency["currencyDecimal"], $contri["amount"]), $cch);
                        $cch = str_replace("_comment_", $contri["comment"], $cch);
                        $cch = str_replace("<option selected value></option>", "", $cch);
                        $cch = str_replace("<option value=\"".$contri["user_id"]."\">", "<option value=\"".$contri["user_id"]."\" selected>", $cch);

                        echo $cch;

                        $z++;
                    }

                    // If there're no contibutions for the current purchase add an empty row
                    if ($z == 0) {
                        // cch = current_contrib_html
                        $cch = $contrib_html;
                        
                        // replace placeholder with actual data
                        $cch = str_replace("<!-- ", "", $cch);
                        $cch = str_replace(" -->", "", $cch);
                        $cch = str_replace("__", $z, $cch);
                        $cch = str_replace("_timestamp_", "new", $cch);
                        $cch = str_replace("_contribution_id_", "new", $cch);
                        $cch = str_replace("_amount_", "", $cch);
                        $cch = str_replace("_comment_", "", $cch);

                        echo $cch;

                        $z++;
                    }
                ?>
            </tbody>
        </table>
        <span id="new-row-base-html" class="hidden"><?php echo $contrib_html; ?></span>
        <span id="next-row-id" class="hidden"><?php echo $z; ?></span>
        <div class="edit-details" onclick="addEditPurchase()"><i class="material-icons">add</i></div>
    </div>
    <div class="form-row">
        <div class="edit-details margin-top-15 submitBtn" onclick="submitForm(editPurchaseForm)"><i id="submitBtn" class="material-icons">save</i></div>
        <div class="edit-details margin-top-15 neg-icon" onclick="deleteData(editPurchaseForm, <?php echo $purchase_id; ?>)"><i class="material-icons">delete</i></div>
    </div>
</form>
