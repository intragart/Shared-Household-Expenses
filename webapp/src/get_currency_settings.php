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

    function get_currency_settings() {

        // set the default values
        $currency_code = "EUR";
        $currency_symbol = "€";
        $currency_decimal = ",";
        $currency_position = "after";

        // Read and decode currency_settings.json if file exists
        $file_path = __DIR__."/../../config/currency_settings.json";
        if (file_exists($file_path)) {

            $settings_file = file_get_contents($file_path);
            $json = json_decode($settings_file,true);

            // check for each setting if the array key exists and update the default values
            if (array_key_exists("currencyCode", $json)) {
                $currency_code = $json["currencyCode"];
            }
            if (array_key_exists("currencySymbol", $json)) {
                $currency_symbol = $json["currencySymbol"];
            }
            if (array_key_exists("currencyDecimal", $json)) {
                $currency_decimal = $json["currencyDecimal"];
            }
            if (array_key_exists("currencyPosition", $json)) {
                $currency_position = $json["currencyPosition"];
            }
        }
        
        // Return as array
        return array(
            "currencyCode" => $currency_code,
            "currencySymbol" => $currency_symbol,
            "currencyDecimal" => $currency_decimal,
            "currencyPosition" => $currency_position);
    }
?>
