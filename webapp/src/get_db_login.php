<?php
    function get_db_login($db_user) {
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