<?php

$config['ha'] = [
    
    //"base_url" => Config::Get('path.root.web') . '/oauth/end',
    "providers" => array(
        // openid providers
        "Yahoo" => array(
                "enabled" => true,
                "keys" => array("key" => "", "secret" => ""),
        ),
        "Google" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => ""),
        ),
        "Facebook" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => ""),
                "trustForwarded" => false
        ),
        "Vkontakte" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => "")
        ),
        "Twitter" => array(
                "enabled" => true,
                "keys" => array("key" => "", "secret" => ""),
                "includeEmail" => false
        ),
        "LinkedIn" => array(
                "enabled" => true,
                "keys" => array("key" => "", "secret" => ""),
                "fields" => array()
        ),
        "GitHub" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => "")
        ),
        "Instagram" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => "")
        ),
        "Mailru" => array(
                    "enabled" => true,
                    "keys" => array("id" => "", "secret" => "")
            ),
        "Odnoklassniki" => array(
                    "enabled" => true,
                    "keys" => array("key" => "", "secret" => "", 'id' => '')
            ),
        "Steam" => array(
                    "enabled" => true,
                    "keys" => array("id" => "", "secret" => "")
            ),
        "Yandex" => array(
                    "enabled" => true,
                    "keys" => array("id" => "", "secret" => "")
            ),
    ),
    // If you want to enable logging, set 'debug_mode' to true.
    // You can also set it to
    // - "error" To log only error messages. Useful in production
    // - "info" To log info and error messages (ignore debug messages)
    "debug_mode" => false,
    // Path to file writable by the web server. Required if 'debug_mode' is not false
    "debug_file" => Config::Get('path.root.server')."/application/logs/log_sociality.txt",

];

return $config;

