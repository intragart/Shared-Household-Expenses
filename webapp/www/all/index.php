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
    $php_fromdate = date("Y-m-d", strtotime("-1 year", time()));
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Alle Ausgaben - Shared Household Expenses</title>
        <link rel="icon" type="image/x-icon" href="/content/favicon/favicon-228.png">
        <link type="text/css" rel="stylesheet" href="/style/css/general.css">
        <link type="text/css" rel="stylesheet" href="/style/css/navigation.css">
        <link type="text/css" rel="stylesheet" href="/style/css/table.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form.css">
        <meta charset="UTF-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Pragma" content="no-cache"> <!-- TODO: Entfernen -->
        <script src="/script/tableDetails.js" type="text/javascript" defer></script>
        <script src="/script/reloadTable.js" type="text/javascript" defer></script>
        <script src="/script/searchTable.js" type="text/javascript" defer></script>
    </head>
    <body>
        <div class="left-wrapper">
            <?php include '../../src/navigation.php'; ?>
        </div>
        <div class="right-wrapper">
            <div id="content">
                <h1>Alle Ausgaben</h1>
                <hr class="sep">
                <br />
                <div class="tableControl">
                    <div class="input-60 input-l">
                        <input type="search" id="tableSearch" name="tableSearch" required>
                        <span class="bar"></span>
                        <label for="tableSearch">Suchbegriff eingeben</label>
                    </div>
                    <div class="input-20 input-l">
                            <input type="date" id="inputDateFrom" name="inputDateFrom" pattern="^[0-9]{4}-[0-9]{2}-[0-9]{2}$" value="<?php echo $php_fromdate; ?>" onchange="reloadTable('tableHolder')" required>
                            <span class="bar"></span>
                            <label for="inputDateFrom">Einträge von</label>
                    </div>
                    <div class="input-20 input-l">
                            <input type="date" id="inputDateTo" name="inputDateTo" pattern="^[0-9]{4}-[0-9]{2}-[0-9]{2}$" value="<?php echo date("Y-m-d"); ?>" onchange="reloadTable('tableHolder')" required>
                            <span class="bar"></span>
                            <label for="inputDateTo">Einträge bis</label>
                    </div>
                </div>
                <div id="tableHolder">
                    <?php
                        // get table html
                        require("../script/getTable.php");
                    ?>
                </div>
                <br />
            </div>
        </div>
    </body>
</html>
