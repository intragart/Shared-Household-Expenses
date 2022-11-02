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
                <h2>Aktueller Dispo</h2>
                <div class="card-holder">
                <div class="saldo">
                        <div class="saldo-sum">
                            15.000,52 €
                        </div>
                        <div class="saldo-name">
                            Alice
                        </div>
                    </div>
                    <div class="saldo">
                        <div class="saldo-sum">
                            - - -
                        </div>
                        <div class="saldo-name">
                            Bob
                        </div>
                    </div>
        	    </div>
                <h2>Ausgaben der letzten 30 Tage</h2>
                <table class="maintable">
                    <tr class="table-head">
                        <th>Artikel</th>
                        <th>Händler</th>
                        <th>Datum</th>
                        <th>Beteiligte</th>
                        <th>Gesamt</th>
                    </tr>
                    <?php 
                        for ($i = 0; $i < 50; $i++) {
                            echo "<tr class='table-row' id='row-".$i."' onclick='showHideTableDetails(".$i.");'>";
                            echo "<td>Einkauf lorem ipsum</td>";
                            echo "<td>E-Center</td>";
                            echo "<td>02.11.2022</td>";
                            echo "<td>Alice & Bob</td>";
                            echo "<td>10,00 €</td>";
                            echo "</tr>";
                            echo '<tr class="table-details hidden" id="details-'.$i.'">';
                            echo ' <td colspan="6">';
                            echo 'Alice: 7,50 €<br />Bob: 2,50 €<br /><br />Bemerkung';
                            echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </table>
                <br />
            </div>
        </div>
    </body>
</html>