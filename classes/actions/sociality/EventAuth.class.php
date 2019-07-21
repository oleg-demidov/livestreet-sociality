<?php


/**
 * Description of EventTopic
 *
 * @author oleg
 */

class PluginSociality_ActionSociality_EventAuth extends Event {
    
    public function Init()
    {
        
    }

    public function EventRegister() {
        $sProvider =$this->GetParam(0);        
        
        if(Config::Get('plugin.sociality.register_scenario') == 'return_to_form'){
            
            $sUrlRedirect = Config::Get('plugin.sociality.register_action');
            
        }else{
            if (Config::Get('general.reg.invite')  and !$this->Session_Get('invite_code')) {
                Router::LocationAction('auth/invite');
            }
            $sUrlRedirect = 'sociality/register_hard';
        }
        
        $sUrl = 'sociality/'. $sProvider.'/start';
        
        $this->Hook_Run('sociality_register_begin', ['provider' => &$sProvider, 'sUrl' => &$sUrl, 'sUrlRedirect' => &$sUrlRedirect]);
        
        $this->Session_Set('authRedirect', $sUrlRedirect);
        
        Router::LocationAction($sUrl);
    }  
    
    
    public function EventRegisterHard() {  
                
        Config::Set('module.user.captcha_use_registration', false);
        
        $sProvider = $this->Session_Get('provider');
        
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            return Router::ActionError($this->Lang_Get('plugin.sociality.auth.error.ha_no_data', ['name' => $sProvider]));
        }       
        
        
        /*
         * Проверяем на существование email. Если есть, берем его
         */
        if( !$oUser = $this->User_GetUserByMail($oProfileData->email)) {
            /**
            * Создаем объект пользователя
            */
            $oUser = $this->AddUser($oProfileData);
            if(is_string($oUser)){
                return Router::ActionError($oUser);
            }
        }     
        
        /*
        * Установка фото
        */
        if (!$oUser->avatar->getMedia()) {
           $oUser->avatar->setByUrl($oProfileData->photoURL);
        }
        

        /*
         * Привязка социальной сети
         */
        $this->PluginSociality_Social_CreateRelation($oProfileData, $sProvider, $oUser->getId());
        
        $this->Hook_Run('sociality_create_relation', 
                ['oUser' => $oUser, 'oProfileData' => $oProfileData, 'provider' => $sProvider]);
        /**
        * Сразу авторизуем
        */
        $this->User_Authorization($oUser, false);
        $this->Session_Drop('invite_code');

        $this->Session_Drop('oUserProfile');
        $this->Session_Drop('provider');
        
        Router::Location($oUser->getUrl());
        
        
        
    }
    
    protected function AddUser($oProfileData) {
        $oUser = Engine::GetEntity(ModuleUser_EntityUser::class);
        $oUser->setRole('user');
        $oUser->setLogin( $this->PluginSociality_Social_GetLoginFromProfileData($oProfileData) );
        $oUser->setMail( $oProfileData->email );
        $oUser->setPassword($sPass = func_generator(10) );
        $oUser->setPasswordConfirm( $sPass );
        $oUser->setDateCreate(date("Y-m-d H:i:s"));
        $oUser->setIpCreate(func_getIp());
        $oUser->setName( $oProfileData->firstName . ' ' . $oProfileData->lastName );
        /**
         * Не используется активация
         */
        $oUser->setActivate(1);
        $oUser->setActivateKey(null);
        /**
         * Устанавливаем сценарий валидации
         */        
        $oUser->_setValidateScenario('add');
        
        /**
         * Запускаем валидацию
         */
        if (!$oUser->_Validate()) {
            return  $oUser->_getValidateError();
        }
            
        $oUser->setPassword($this->User_MakeHashPassword($oUser->getPassword()));
                
        $oUser->Add();
        
        /**
        * Если юзер зарегистрировался по приглашению то обновляем инвайт
        */
        if ($sCode = $this->Session_Get('invite_code')) {
            $this->Invite_UseCode($sCode, $oUser);
        }
        
        return $this->User_GetUserByLogin($oUser->getLogin());
    }


    public function EventLogin()
    {
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            $this->Session_Set('authRedirect', 'sociality/login/');
        
            $this->Session_Set('provider', $this->GetParam(0));
        
            Router::LocationAction('sociality/'. $this->GetParam(0).'/start');
        }
        
        $sProvider = $this->Session_Get('provider');
        
        if($oSocial = $this->PluginSociality_Social_GetSocialByFilter(['social_id' => $oProfileData->identifier, 
                        'social_type' => $sProvider])){
            if($oUser = $oSocial->getUser()){
                
                $this->Session_Drop('oUserProfile');
                $this->Session_Drop('provider');
                
                $this->User_Authorization($oUser, false);  
                Router::Location($oUser->getUrl());
            }
        }
        
        if(Config::Get('plugin.sociality.register_scenario') == 'return_to_form'){
            
            $sUrlRedirect = Config::Get('plugin.sociality.register_action');
            
        }else{
            if (Config::Get('general.reg.invite')  and !$this->Session_Get('invite_code')) {
                Router::LocationAction('auth/invite');
            }
            $sUrlRedirect = 'sociality/register_hard';
        }
        Router::LocationAction($sUrlRedirect);
    } 
    
    public function EventResetProfile() {
        $this->Session_Drop('oUserProfile');
        
        $sProvider = $this->Session_Get('provider');
        $this->Session_Drop('provider');
        
        $this->Message_AddNoticeSingle(
                $this->Lang_Get('plugin.sociality.auth.profile.reset_profile_notice', ['provider' => $sProvider]),'',true);
        
        $sUrlRedirect = $this->Session_Get('authRedirect')?$this->Session_Get('authRedirect'):Config::Get('plugin.sociality.register_action');
        
        Router::LocationAction($sUrlRedirect);
    }
    
    public function EventAttachWithEnter() {
        $sProvider = $this->Session_Get('provider');
        
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            return Router::ActionError($this->Lang_Get('plugin.sociality.auth.error.ha_no_data', ['name' => $sProvider]));
        } 
        if(!$oUser = $this->User_GetUserByMail($oProfileData->email)){
            return Router::ActionError($this->Lang_Get('plugin.sociality.auth.error.ha_no_data', ['name' => $sProvider]));
        }
        /*
        * Привязка социальной сети
        */
        $this->PluginSociality_Social_CreateRelation($oProfileData, $sProvider, $oUser->getId());
        
        $this->User_Authorization($oUser, false); 
        
        if(!$sUrl = Config::Get('module.user.redirect_after_registration')){
            $sUrl =$oUser->getUserWebPath();
        }
        Router::Location($sUrl);
    }
}