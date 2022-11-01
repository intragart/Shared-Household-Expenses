<!DOCTYPE html>
<html lang="de">
    <head>
        <title>DEBUG: Neuer Eintrag - Shared Household Expenses</title>
        <link rel="icon" type="image/x-icon" href="/content/favicon/favicon-228.png">
        <meta charset="UTF-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Pragma" content="no-cache"> <!-- TODO: Entfernen -->
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>
        <h1>Allgemein</h1>
        <?php
            echo "Artikel: ";
            echo $_POST["inputArticle"];
            echo "<br />";

            echo "Händler: ";
            echo $_POST["inputDealer"];
            echo "<br />";

            echo "Datum: ";
            echo $_POST["inputDate"];
        ?>
        <h1>Kostenverteilung</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Betrag</th>
                <th>Bemerkung</th>
            </tr>
            <?php
                // Get rows
                $dynamicRows = (count($_POST) - 3) / 3;
                
                // Build Rest of Table
                $i = 0;
                while ($i < $dynamicRows) {
                    if ($_POST["inputUser".$i] != "" and $_POST["inputAmount".$i] != "") {
                        echo "<tr>";
                        echo "<td>".$_POST["inputUser".$i]."</td>";
                        echo "<td>".$_POST["inputAmount".$i]."</td>";
                        echo "<td>".$_POST["inputRemark".$i]."</td>";
                        echo "</tr>";
                    }
                    $i++;
                }
            ?>
        </table>  
    </body>
</html>

