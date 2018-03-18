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
        
        Config::Set('general.reg.activation', false);
        
        if(Config::Get('plugin.sociality.register_scenario') == 'return_to_form'){
            $this->Session_Set('authRedirect', 'auth/register');
        }else{
            if (Config::Get('general.reg.invite')  and !$this->Session_Get('invite_code')) {
                Router::LocationAction('auth/invite');
            }
            $this->Session_Set('authRedirect', 'sociality/register_hard');
        }
        
        Router::LocationAction('sociality/'. $this->GetParam(0).'/start');
    }  
    
    
    public function EventRegisterHard() {  
        
        Config::Set('module.user.captcha_use_registration', false);
        
        $sProvider = $this->Session_Get('provider');
        
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            return Router::ActionError($this->Lang_Get('plugin.sociality.auth.error.ha_no_data', ['name' => $sProvider]));
        }       
        
        /*
         * TODO Не работает выход если вход выполнен через регистрацию
         */
        if($oSocial = $this->PluginSociality_Social_GetSocialByFilter(['social_id' => $oProfileData->identifier, 
                        'social_type' => $sProvider])){
            if($oUser = $oSocial->getUser()){
                $this->Session_Drop('oUserProfile');
                $this->Session_Drop('provider');
            
                $this->User_Authorization($oUser, false);
                Router::Location($oUser->getUserWebPath());
            }
            $oSocial->Delete();
        }
        /**
         * Создаем объект пользователя
         */
        $oUser = Engine::GetEntity('ModuleUser_EntityUser');
        /*
         * Проверяем на существование email. Если есть, выполняем вход
         */
        if( $oUser->ValidateMailExists($oProfileData->email, []) !== true ) {
            $oUser = $this->User_GetUserByMail($oProfileData->email);
            
            if(!$oSocial = $this->PluginSociality_Social_GetSocialByFilter(['user_id' => $oUser->getId(), 'social_type' => $sProvider])){
                return Router::ActionError($this->Lang_Get('plugin.sociality.auth.error.email_exists_desc', 
                        ['provider' => $sProvider, 'email' => $oProfileData->email]), 
                    $this->Lang_Get('plugin.sociality.auth.error.email_exists', ['email' => $oProfileData->email]));
            }
            
            $this->Session_Drop('oUserProfile');
            $this->Session_Drop('provider');
            
            $this->User_Authorization($oUser, false);
            Router::Location($oUser->getUserWebPath());
        }     
        
        /**
         * Устанавливаем сценарий валидации
         */        
        $oUser->_setValidateScenario('registration');
        /**
         * Заполняем поля (данные)
         */        
        $sPass = rand(100000,10000000);
        $oUser->setLogin( $this->PluginSociality_Social_GetLoginFromProfileData($oProfileData) );
        $oUser->setMail( $oProfileData->email );
        $oUser->setPassword( $sPass );
        $oUser->setPasswordConfirm( $sPass );
        $oUser->setDateRegister(date("Y-m-d H:i:s"));
        $oUser->setIpRegister(func_getIp());
        
        $oUser->setProfileSex($this->PluginSociality_Social_GetGender($oProfileData));
        $oUser->setProfileBirthday($this->PluginSociality_Social_GetBirthdayDatetime($oProfileData));
        $oUser->setProfileName( $oProfileData->firstName . ' ' . $oProfileData->lastName );
        $oUser->setProfileCountry( $oProfileData->country );
        $oUser->setProfileRegion( $oProfileData->region );
        $oUser->setProfileCity( $oProfileData->city?$oProfileData->city:$oProfileData->home_town );
        /**
         * Не используется активация
         */
        $oUser->setActivate(1);
        $oUser->setActivateKey(null);
            
        /**
         * Запускаем валидацию
         */
        if ($oUser->_Validate()) {
            $oUser->setPassword($this->User_MakeHashPassword($oUser->getPassword()));
            if ($this->User_Add($oUser)) {
                
                /**
                 * Подписываем пользователя на дефолтные события в ленте активности
                 */
                $this->Stream_switchUserEventDefaultTypes($oUser->getId());
                /**
                 * Если юзер зарегистрировался по приглашению то обновляем инвайт
                 */
                if ($sCode = $this->Session_Get('invite_code')) {
                    $this->Invite_UseCode($sCode, $oUser);
                }
                
                /*
                * Установка фото
                */
                
                if($sPathPhoto = $this->PluginSociality_Social_GetPhotoByData($oProfileData)){

                    if($this->User_CreateProfilePhoto($sPathPhoto, $oUser)){

                        $this->User_CreateProfileAvatar($oUser->getProfileFoto(), $oUser);
                    }

                    $this->Fs_RemoveFileLocal($sPathPhoto);

                }
                /*
                 * Попытка привязать местоположение
                 */
                if($oGeo = $this->PluginSociality_Social_GetGeoByData($oProfileData)){
                    $this->Geo_CreateTarget($oGeo, 'user', $oUser->getId());
                }                
                /*
                 * Привязка социальной сети
                 */
                $this->PluginSociality_Social_CreateRelation($oProfileData, $sProvider, $oUser->getId());
                /*
                 * Отправляем пароль на email
                 */
                $this->User_SendNotifyRegistration($oUser, $sPass);
                $oUser = $this->User_GetUserById($oUser->getId());
                /**
                 * Сразу авторизуем
                 */
                $this->User_Authorization($oUser, false);
                $this->Session_Drop('invite_code');
                /**
                 * Определяем URL для редиректа после авторизации
                 */
                $sUrl = Config::Get('module.user.redirect_after_registration');
                $this->Message_AddNoticeSingle($this->Lang_Get('auth.registration.notices.success'),'',true);
                
                $this->Session_Drop('oUserProfile');
                $this->Session_Drop('provider');
                
                Router::Location($oUser->getUserWebPath());
                
            } else {
                return Router::ActionError($this->Lang_Get('common.error.system.base'));
            }
        } else {
            /**
             * Получаем ошибки
             */
            $aErrors = $oUser->_getValidateErrors();
            return Router::ActionError(serialize($aErrors));
        }
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
                Router::Location($oUser->getUserWebPath());
            }
        }
        Router::LocationAction('auth/register');
    } 
    
    public function EventResetProfile() {
        $this->Session_Drop('oUserProfile');
        
        $sProvider = $this->Session_Get('provider');
        $this->Session_Drop('provider');
        
        $this->Message_AddNoticeSingle(
                $this->Lang_Get('plugin.sociality.auth.profile.reset_profile_notice', ['provider' => $sProvider]),'',true);
        Router::LocationAction('auth/register');
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