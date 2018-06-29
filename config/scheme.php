<?php

$sPathRedirect = Config::Get('path.root.web').'/sociality';

$config['$config_scheme$'] = array(
    
    'order' =>  array(
        'type'        => 'string',
        'name'        => 'Order buttons',
        'description' => 'Example: Vkontakte,Odnoklassniki,Facebook,Twitter,Instagram,Mailru,Google,Yandex,GitHub,Steam,Yahoo,LinkedIn,Live',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    'register_scenario' =>  array(
        'type'        => 'string',
        'name'        => 'Register scenario',
        'description' => '',
        'validator'   => array(
            'type'   => 'Enum',
            'params' => array(
                'enum'       => array(
                    'return_to_form',
                    'register_hard',
                ),
                'allowEmpty' => false,
            ),
        ),
    ),
    
    
    'size' =>  array(
        'type'        => 'string',
        'name'        => 'Size buttons',
        'description' => '<span style="color:#333;"><br><h2>Redirect uri</h2>Redirect url: '.$sPathRedirect.'/{provider}/end <br>Eample: '.Config::Get('path.root.web').'/oauth/Vkontakte/end</span>',
        'validator'   => array(
            'type'   => 'Enum',
            'params' => array(
                'enum'       => array(
                    'large',
                    'small',
                ),
                'allowEmpty' => false,
            ),
        ),
    ),
    
    
    
    /*
     * Yandex
     */
    'ha.providers.Yandex.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Yandex.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'ID',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Yandex.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Пароль',
        'description' => 'Redirect url: '.$sPathRedirect.'Yandex/end?hauth.done=Yandex',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     *  Yahoo
     */
    'ha.providers.Yahoo.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Yahoo.keys.key'       => array(
        'type'        => 'string',
        'name'        => 'Id application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Yahoo.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => 'Redirect url: '.$sPathRedirect.'/Yahoo/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * 
     * Google
     */
     'ha.providers.Google.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Google.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'Id',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Google.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret',
        'description' => 'Redirect url: '.$sPathRedirect.'/Google/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * Facebook
     */
    
    'ha.providers.Facebook.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),    
    'ha.providers.Facebook.trustForwarded'     => array(
        'type'        => 'boolean',
        'name'        => 'trustForwarded включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Facebook.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'Key application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Facebook.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => 'Redirect url: '.$sPathRedirect.'/Facebook/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * Vkontakte
     */
    'ha.providers.Vkontakte.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Vkontakte.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'ID приложения',
        'description' => 'Redirect url: '.$sPathRedirect.'/Vkontakte/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Vkontakte.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Защищённый ключ',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * Twitter
     */
    'ha.providers.Twitter.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Twitter.includeEmail'     => array(
        'type'        => 'boolean',
        'name'        => 'includeEmail включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Twitter.keys.key'       => array(
        'type'        => 'string',
        'name'        => 'Key application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Twitter.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => 'Redirect url: '.$sPathRedirect.'/Twitter/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * LinkedIn
     */
    'ha.providers.LinkedIn.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.LinkedIn.keys.key'       => array(
        'type'        => 'string',
        'name'        => 'Key application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.LinkedIn.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => 'Redirect url: '.$sPathRedirect.'/LinkedIn/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * GitHub
     */
    'ha.providers.GitHub.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.GitHub.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'Key application',
        'description' => 'Redirect url: '.$sPathRedirect.'/GitHub/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.GitHub.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * Instagram
     */
    'ha.providers.Instagram.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Instagram.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'Id application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Instagram.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => 'Redirect url 1: '.$sPathRedirect.'/Instagram<br>Redirect url 2: '.Config::Get('path.root.web').'/oauth/Instagram/end?hauth.done=Instagram',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * Mailru
     */
    'ha.providers.Mailru.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Mailru.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'Key application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Mailru.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Приватный ключ',
        'description' => 'Redirect url: '.$sPathRedirect.'/Mailru/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * Odnoklassniki
     */
    'ha.providers.Odnoklassniki.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Odnoklassniki.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'Id application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Odnoklassniki.keys.key'       => array(
        'type'        => 'string',
        'name'        => 'Key application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),    
    'ha.providers.Odnoklassniki.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => 'Redirect url: '.$sPathRedirect.'/Odnoklassniki/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    
    /*
     * Steam
     */
    'ha.providers.Steam.enabled'     => array(
        'type'        => 'boolean',
        'name'        => 'включить',
        'description' => '',
        'validator'   => array(
            'type'   => 'Boolean',
            'params' => array(),
        ),
    ),
    'ha.providers.Steam.keys.id'       => array(
        'type'        => 'string',
        'name'        => 'Key application',
        'description' => '',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
    'ha.providers.Steam.keys.secret'       => array(
        'type'        => 'string',
        'name'        => 'Secret key application',
        'description' => 'Redirect url: '.$sPathRedirect.'/Steam/end',
        'validator'   => array(
            'type'   => 'String',
            'params' => array(
                'min'        => 2,
                'max'        => 500,
                'allowEmpty' => true,
            ),
        ),
    ),
);

$config['$config_sections$'] = array(
    
    array(
        'name' => 'Order',
        'allowed_keys' => array(
            'order',
            'register_scenario',
            'size'
        )
    ),
    
        
    array(
        /**
         * Название раздела
         */
        'name' => 'Yandex <a href="https://oauth.yandex.ru/client/new">Создать приложение</a>',
        /**
         * Список параметров для отображения в разделе
         */
        'allowed_keys' => array(
                'ha.providers.Yandex.enabled',
                'ha.providers.Yandex.keys.id',
                'ha.providers.Yandex.keys.secret'
        ),
        /**
         * Список параметров для исключения из раздела

        'excluded_keys' => array(
                'other.two',
        ),*/
    ),
    array(
        'name' => 'Yahoo <a href="https://developer.yahoo.com/oauth/">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Yahoo.enabled',
                'ha.providers.Yahoo.keys.key',
                'ha.providers.Yahoo.keys.secret'
        )
    ),
    array(
        'name' => 'Google <a href="https://console.developers.google.com">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Google.enabled',
                'ha.providers.Google.keys.id',
                'ha.providers.Google.keys.secret'
        )
    ),
    array(
        'name' => 'Facebook <a href="https://developers.facebook.com/apps/">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Facebook.enabled',
                'ha.providers.Facebook.keys.id',
                'ha.providers.Facebook.keys.secret'
        )
    ),
    array(
        'name' => 'Vkontakte <a href="https://vk.com/apps?act=manage">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Vkontakte.enabled',
                'ha.providers.Vkontakte.keys.id',
                'ha.providers.Vkontakte.keys.secret'
        )
    ),
    array(
        'name' => 'Twitter <a href="https://apps.twitter.com/">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Twitter.enabled',
                'ha.providers.Twitter.keys.key',
                'ha.providers.Twitter.keys.secret'
        )
    ),
    array(
        'name' => 'LinkedIn <a href="https://developer.linkedin.com/my-apps">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.LinkedIn.enabled',
                'ha.providers.LinkedIn.keys.key',
                'ha.providers.LinkedIn.keys.secret'
        )
    ),
    array(
        'name' => 'GitHub  <a href="https://github.com/settings/applications/new">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.GitHub.enabled',
                'ha.providers.GitHub.keys.id',
                'ha.providers.GitHub.keys.secret'
        )
    ),
    array(
        'name' => 'Instagram <a href="https://www.instagram.com/developer/register/">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Instagram.enabled',
                'ha.providers.Instagram.keys.id',
                'ha.providers.Instagram.keys.secret'
        )
    ),
    array(
        'name' => 'Mailru <a href="https://api.mail.ru/apps/">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Mailru.enabled',
                'ha.providers.Mailru.keys.id',
                'ha.providers.Mailru.keys.secret'
        )
    ),
    
    array(
        'name' => 'Odnoklassniki  <a href="https://apiok.ru/dev/app/create">Создать приложение</a>',
        'allowed_keys' => array(
            'ha.providers.Odnoklassniki.enabled',
            'ha.providers.Odnoklassniki.keys.id',
            'ha.providers.Odnoklassniki.keys.key',
            'ha.providers.Odnoklassniki.keys.secret'
        )
    ),
    array(
        'name' => 'Steam <a href="https://steamcommunity.com/dev/apikey">Создать приложение</a>',
        'allowed_keys' => array(
                'ha.providers.Steam.enabled',
                'ha.providers.Steam.keys.id',
                'ha.providers.Steam.keys.secret'
        )
    ),
);


return $config;

