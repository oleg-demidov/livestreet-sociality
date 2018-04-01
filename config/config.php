<?php

Config::Set('router.page.sociality', 'PluginSociality_ActionSociality');

$config['order'] = 'Vkontakte,Odnoklassniki,Facebook,Twitter,Instagram,Mailru,Google,Yandex,GitHub,Steam,Yahoo,LinkedIn';

$config['size'] = 'large';

$config['register_scenario'] = 'register_hard'; // return_form, hard_register

$config['register_action'] = 'auth/register';

return $config;

