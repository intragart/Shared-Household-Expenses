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
<!DOCTYPE html>
<html lang="de">
    <head>
        <title>Neuer Eintrag - Shared Household Expenses</title>
        <link rel="icon" type="image/x-icon" href="/content/favicon/favicon-228.png">
        <link type="text/css" rel="stylesheet" href="/style/css/general.css">
        <link type="text/css" rel="stylesheet" href="/style/css/navigation.css">
        <link type="text/css" rel="stylesheet" href="/style/css/form.css">
        <meta charset="UTF-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Pragma" content="no-cache"> <!-- TODO: Entfernen -->
        <script src="/script/checkForm.js" type="text/javascript" defer></script>
        <script src="/script/addRemoveRowInNew.js" type="text/javascript" defer></script>
        <script src="/script/resetToAutofocus.js" type="text/javascript" defer></script>
    </head>
    <body>
        <div class="left-wrapper">
            <?php include '../../src/navigation.php'; ?>
        </div>
        <div class="right-wrapper">
            <div id="content">
                <h1 id="test123">Neuen Eintrag erfassen</h1> <!-- TODO: ID entfernen -->
                <hr class="sep">
                <form action="/new/insert.php" target="_self" method="post" autocomplete="off" novalidate>
                    <div class="form-row">
                        <h2>Allgemein</h2>
                    </div>
                    <div class="form-row">
                        <div class="input-100 input-l">
                            <input type="text" id="inputArticle" name="inputArticle" pattern="^[\w äöüÄÖÜß&,\._-]+$" required autofocus>
                            <span class="bar"></span>
                            <label for="inputArticle">Artikel</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="input-70 input-l">
                            <input type="text" id="inputDealer" name="inputDealer" pattern="^[\w äöüÄÖÜß&,\._-]+$" required>
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
                            <input type="text" id="inputUser0" name="inputUser0" pattern="^[\w äöüÄÖÜß&,\._-]+$" required>
                            <span class="bar"></span>
                            <label for="inputUser0">Name</label>
                        </div>
                        <div class="input-20 input-l">
                            <input type="text" id="inputAmount0" name="inputAmount0" pattern="^[-]{0,1}[0-9]+[,]{0,1}[0-9]{0,2}$" required>
                            <span class="bar"></span>
                            <label for="inputAmount0">Betrag (EUR)</label>
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
                            <input id="num-rows" type="hidden" value="1">
                        </div>
                        <div class="input-80 input-r">
                            <input class="btn btn-positive" type="submit" value="Senden" disabled>
                            <input class="btn" type="reset" value="Reset">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
