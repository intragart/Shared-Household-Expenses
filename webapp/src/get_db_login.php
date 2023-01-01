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

    function get_db_login($db_user) {
        // Reads the information stored in the settings json for the database
        // connections and returns it.
        //
        // Args:
        //     db_user (string): database user whose information should be returned
        //
        // Returns:
        //     array: contains informations to connect to the database

        // Read and decode settings.json
        $settings_file = file_get_contents(__DIR__.'/../../config/db_settings.json');
        $settings = json_decode($settings_file,true);

        // Extract information
        $fqdn = $settings["general"]["fqdn"];
        $user = $settings["users"][$db_user]["username"];
        $password = $settings["users"][$db_user]["password"];
        $database = $settings["general"]["database"];
        $port = $settings["general"]["port"];
        $socket = $settings["general"]["socket"];

        // Return as array
        return array($fqdn, $user, $password, $database, $port, $socket);
    }
?>
