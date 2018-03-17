<?php


class PluginSociality_ActionOauth extends ActionPlugin
{

    public $returnUrl = '';
    
    public $returnUrlError = '';
    

    public function Init()
    {        
       //$this->SetDefaultEvent('EventAuth');
       $this->returnUrl = Config::Get('path.root.web');
       $this->returnUrlError = Router::GetPath('auth/login');
       
       $this->Hook_Run('sociality_oauth_start', [
           'returnUrl' => &$this->returnUrl, 
           'returnUrlError' => &$this->returnUrlError]);
       
       //$this->Logger_Notice($this->returnUrl);
       //$this->Logger_Notice($this->returnUrlError);
    }


    protected function RegisterEvent()
    {
        
        //$this->AddEvent('index', 'EventAuth');
        $this->AddEventPreg('/^(.*)?/i','/^(reg)/i','EventRegistration');
        $this->AddEventPreg('/^(.*)?/i','/^(start|bind)?$/i','EventOauthStart');
        $this->AddEventPreg('/^(.*)?/i','/^end$/i','EventOauthEnd');
        
        $this->AddEventPreg('/^(.*)?/i','/^remove$/i','EventRemoveBind');
        
    }

    // Устанавливаем эвэнт с обработкой пути авторизации через соц сеть
    
    public function EventOauthStart()
    {
        //echo $this->sCurrentEvent,'<br>';
        
        
        $this->SetTemplate(false);
        /*
         * Получение конфига для hybridauth
         */
        $config = Config::Get('plugin.sociality');
        Config::Set('module.user.captcha_use_registration', false);
            Config::Set('general.reg.activation', false);
        /*
         * Проверка на существование провайдера
         */
           
        if( !isset($config['ha']['providers'][$this->sCurrentEvent]) ){
            echo 'no provider';
            return false;
        }
        
        $this->PluginSociality_Oauth_SetProvider( $this->sCurrentEvent );
        
        /*
         *  Формируем redirect uri 
         */
        $config['ha']['base_url'] = Config::Get('path.root.web') . '/oauth/' . $this->sCurrentEvent. '/end';
        
        //$this->Logger_Notice($config['ha']['base_url']);
        /*
         *  Запуск авторизации через соц сеть
         */
        $oUserProfile = false;
        try{
            
            $hybridauth = new Hybrid_Auth( $config['ha'] );

            $оProvider = $hybridauth->authenticate( $this->sCurrentEvent );

            $oUserProfile = $оProvider->getUserProfile();

        }
        catch( Exception $e ){
            $this->Message_AddErrorSingle('Ошибка входа', null, true);
            Router::Location($this->returnUrlError);
        }
        //print_r($oUserProfile);
        if(!is_object($oUserProfile)){            
            Router::Location($this->returnUrlError);
        }       
        
        $this->Hook_Run('sociality_get_data', ['data' => &$oUserProfile, 'provider' => $this->sCurrentEvent]);
            
        
        $this->PluginSociality_Oauth_SetProvider( $this->sCurrentEvent );
        
        /*
         * Проверяем нет ли пользователя по social_id
         */
        if($oUser = $this->PluginSociality_Oauth_GetUserBySocialID( $oUserProfile->identifier )){
            /*
             * Обновляем данные из соц сети
             */
            $oSocial = $this->PluginSociality_Oauth_GetSocialBySocialID( $oUserProfile->identifier);
            
            $oSocial->SetDateReceived(date('Y-m-d H:i:s'));
            
            $this->PluginSociality_Oauth_UpdateSocial($oSocial);
            
            /*
             *  Авторизируем
             */
            //$this->Logger_Notice('auth_id');
            
            $this->User_Authorization($oUser);         
            
            Router::Location($oUser->getUserWebPath());
            
            $this->Message_AddNoticeSingle('dd');
            
        }
        
        /*
         * Проверяем нет ли пользователя по email
         */
        if($oUserProfile->email && $oUser = $this->User_GetUserByMail($oUserProfile->email)){
           
            $oSocial = $this->PluginSociality_Oauth_CreateSocialEntity($oUser->getId(),  $oUserProfile->identifier);
            
            $this->PluginSociality_Oauth_AddSocial($oSocial);
            
            $this->User_Authorization($oUser);
            //$this->Logger_Notice('auth_email');
            
            //this->Message_AddNoticeSingle($this->Lang_Get('plugin.sociality.email_dublicate'), $oUserProfile->email);
            
            Router::Location($oUser->getUserWebPath());
       }
        
        
        /*
         * Нет, значит направляем на регистрацию
         */
        $this->Session_Set('oUserProfile', $oUserProfile);        
        
        if($oUserProfile){
            Router::Location(Router::GetPath('oauth/'.$this->sCurrentEvent.'/reg'));
        }
       
    }
    
    protected function EventRegistration()
    {
        if(!$oUserProfile = $this->Session_Get('oUserProfile')){
            $this->Message_AddErrorSingle('Ошибка входа', null, true);
            Router::Location($this->returnUrlError);
        }        
        
        $this->PluginSociality_Oauth_SetProvider( $this->sCurrentEvent );
        /*
        * Создаем пользователя
        */
        if($oUser = $this->CreateUser($oUserProfile)){
            /*
             *  Авторизируем
             */
            
            $this->Hook_Run('sociality_oauth_reg_end', ['oUser' => &$oUser]);
            
            $this->User_Authorization($oUser);
            
            Router::Location($this->returnUrl);
        }
        
    }
    
    protected function EventOauthEnd()
    {
        /*
         * Необходимый редирект для hybridauth
         */
        $_REQUEST['hauth.done'] = $this->sCurrentEvent;
        Hybrid_Endpoint::process();
    } 
    
    protected function CreateUser(&$oUserProfile) {
        
        /*
         * Отключаем все не нужное
         */
        Config::Set('general.reg.activation', false);
        Config::Set('module.user.captcha_use_registration', false);
        
        
        
        /*
         * Генерим логин 
         */
        $sLogin = $this->PluginSociality_Oauth_GenerateLogin(array(
            $oUserProfile->displayName,
            $oUserProfile->firstName,
            $oUserProfile->lastName,
            substr($oUserProfile->birthYear, -2, 2)
         ));
        
        /*
         * Заполняем поля
         */
        $aFields = array(
            'login' => $sLogin,
            'email' => $oUserProfile->email?$oUserProfile->email:$sLogin,
            'name' => $oUserProfile->firstName.' '.$oUserProfile->lastName ,
            'pass' => func_generator(12),
            'country' => $oUserProfile->country,
            'city' => $oUserProfile->city,
        );
        /*
         * Пол
         */
        $aGender = array( 'male' => 'man', 'female' =>'woman'   );
        
        if(trim($oUserProfile->gender) != ''){
            $sGender = isset($aGender[$oUserProfile->gender])?$aGender[$oUserProfile->gender]:'other';
            $aFields['gender'] = $sGender;
        }
        
        if($oDate = date_create_from_format('j.n.Y', $oUserProfile->birthDay.'.'.$oUserProfile->birthMonth.'.'.$oUserProfile->birthYear)){
            $tTimestamp  = $oDate->getTimestamp();
            $sDate = date("Y-m-d H:i:s", $tTimestamp); 
            $aFields['bdate'] = $sDate;
        }        
         
        /*
         *  Создаем обьект пользователя
         */
        $oUser = $this->PluginSociality_Oauth_CreateUserEntity( $aFields );
        
        $this->Hook_Run('sociality_user_create_before', ['oUser' => &$oUser]);
        
        if ($this->User_Add($oUser)) {
            /*
             * Добавляем остальные данные пользователю
             */
            $this->User_Update($oUser);

            /*
             * Устанавливаем пользователю нахождение GEO
             */
            $this->PluginSociality_Oauth_SetUserGeo([
                'country' => $oUserProfile->country,
                'region' => $oUserProfile->region,
                'city' => $oUserProfile->city
            ], $oUser);

            /*
             * Установка фото
             */
            if($sPathPhoto = $this->PluginSociality_Oauth_GetPhotoByUrl($oUserProfile->photoURL)){

                if($this->User_CreateProfilePhoto($sPathPhoto, $oUser)){

                    $this->User_CreateProfileAvatar($oUser->getProfileFoto(), $oUser);
                }

                $this->Fs_RemoveFileLocal($sPathPhoto);

            }

            /**
             * Подписываем пользователя на дефолтные события в ленте активности
             */
            $this->Stream_switchUserEventDefaultTypes($oUser->getId());
            /**
             *  Создаем Social сущность 
             */
            $oSocial = $this->PluginSociality_Oauth_CreateSocialEntity($oUser->getId(),  $oUserProfile->identifier);

            /*
             * Вносим данные в бд
             */
            $this->PluginSociality_Oauth_AddSocial($oSocial);

            return $oUser;        

        }
        return false;

    }
    
    public function EventRemoveBind() {
        $this->Security_ValidateSendForm();
        $oUserCurrent = $this->User_GetUserCurrent();
        if(is_array($aSocial = $this->PluginSociality_Oauth_GetSocialItemsByUserId($oUserCurrent->getId()))){
            
            $oSocial = array_shift($aSocial);
            $oSocial->setUserId(0);           
            $this->PluginSociality_Oauth_UpdateSocial($oSocial);
            if($iFieldId = $this->User_userFieldExistsByName(strtolower($oSocial->getSocialType()))){
                $this->User_setUserFieldsValues($oUserCurrent->getId(),array($iFieldId[0]['id'] => null));
            }
        }
        Router::LocationAction('settings');
    }

}