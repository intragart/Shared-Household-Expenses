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

    // check if the maindata fields match their regpatterns
    $all_regex_ok = true;
    if (!preg_match("/^[A-Z]{3}$/", $_POST["currencyCode"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^.{1}$/u", $_POST["currencySymbol"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^[,\.]{1}$/", $_POST["currencyDecimal"])) {
        $all_regex_ok = false;
    }
    if (!preg_match("/^(before|after){1}$/", $_POST["currencyPosition"])) {
        $all_regex_ok = false;
    }

    // End execution if regex not ok
    if (!$all_regex_ok) {
        echo "Please validate your inputs.";
        http_response_code(400);
        exit();
    }

    // create an array from the inputs
    $json = array(
        "currencyCode" => $_POST["currencyCode"],
        "currencySymbol" => $_POST["currencySymbol"],
        "currencyDecimal" => $_POST["currencyDecimal"],
        "currencyPosition" => $_POST["currencyPosition"]
    );

    // write contents to file
    $settings_file = __DIR__.'/../../../config/currency_settings.json';
    file_put_contents($settings_file, json_encode($json));
    
?>
