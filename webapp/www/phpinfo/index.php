<!DOCTYPE html>
<html lang="de">
    <head>
        <title>PHP Info - Shared Household Expenses</title>
        <link type="text/css" rel="stylesheet" href="/css/general.css">
        <link type="text/css" rel="stylesheet" href="/css/navigation.css">
        <meta charset="UTF-8">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="Marco Weingart">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Pragma" content="no-cache"> <!-- TODO: Entfernen -->
    </head>
    <body>
        <div class="left-wrapper">
            <?php include '../../src/navigation.php'; ?>
        </div>
        <div class="right-wrapper">
            <div id="content">
                <?php phpinfo(); ?>
            </div>
        </div>
    </body>
</html>