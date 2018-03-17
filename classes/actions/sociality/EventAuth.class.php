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
        
        if(Config::Get('plugin.sociality.register_scenario') == 'return_form'){
            $this->Session_Set('authRedirect', 'auth/register');
        }else{
            $this->Session_Set('authRedirect', 'sociality/register_hard');
        }        
        
        Router::LocationAction('sociality/'. $this->GetParam(0).'/start');
    }  
    
    public function EventRegisterHard() {
        
        print_r($this->Session_Get('oUserProfile'));
        
        /**
         * Создаем объект пользователя и устанавливаем сценарий валидации
         */
        $oUser = Engine::GetEntity('ModuleUser_EntityUser');
        $oUser->_setValidateScenario('registration');
        /**
         * Заполняем поля (данные)
         */
        $oUser->setLogin(getRequestStr('login'));
        $oUser->setMail(getRequestStr('mail'));
        $oUser->setPassword(getRequestStr('password'));
        $oUser->setPasswordConfirm(getRequestStr('password_confirm'));
        $oUser->setCaptcha(getRequestStr('captcha'));
        $oUser->setDateRegister(date("Y-m-d H:i:s"));
        $oUser->setIpRegister(func_getIp());
        /**
         * Если используется активация, то генерим код активации
         */
        if (Config::Get('general.reg.activation')) {
            $oUser->setActivate(0);
            $oUser->setActivateKey(md5(func_generator() . time()));
        } else {
            $oUser->setActivate(1);
            $oUser->setActivateKey(null);
        }
        $this->Hook_Run('registration_validate_before', array('oUser' => $oUser));
        /**
         * Запускаем валидацию
         */
        if ($oUser->_Validate()) {
            $this->Hook_Run('registration_validate_after', array('oUser' => $oUser));
            $oUser->setPassword($this->User_MakeHashPassword($oUser->getPassword()));
            if ($this->User_Add($oUser)) {
                $this->Hook_Run('registration_after', array('oUser' => $oUser));
                /**
                 * Убиваем каптчу
                 */
                $this->Session_Drop('captcha_keystring_user_signup');
                /**
                 * Подписываем пользователя на дефолтные события в ленте активности
                 */
                $this->Stream_switchUserEventDefaultTypes($oUser->getId());
                /**
                 * Если юзер зарегистрировался по приглашению то обновляем инвайт
                 */
                if ($sCode = $this->GetInviteRegister()) {
                    $this->Invite_UseCode($sCode, $oUser);
                }
                /**
                 * Если стоит регистрация с активацией то проводим её
                 */
                if (Config::Get('general.reg.activation')) {
                    /**
                     * Отправляем на мыло письмо о подтверждении регистрации
                     */
                    $this->User_SendNotifyRegistrationActivate($oUser, getRequestStr('password'));
                    $this->Viewer_AssignAjax('sUrlRedirect', Router::GetPath('auth/register-confirm'));
                } else {
                    $this->User_SendNotifyRegistration($oUser, getRequestStr('password'));
                    $oUser = $this->User_GetUserById($oUser->getId());
                    /**
                     * Сразу авторизуем
                     */
                    $this->User_Authorization($oUser, false);
                    $this->DropInviteRegister();
                    /**
                     * Определяем URL для редиректа после авторизации
                     */
                    $sUrl = Config::Get('module.user.redirect_after_registration');
                    if (getRequestStr('return-path')) {
                        $sUrl = getRequestStr('return-path');
                    }
                    $this->Viewer_AssignAjax('sUrlRedirect', $sUrl ? $sUrl : Router::GetPath('/'));
                    $this->Message_AddNoticeSingle($this->Lang_Get('auth.registration.notices.success'));
                }
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
                return;
            }
        } else {
            /**
             * Получаем ошибки
             */
            $this->Viewer_AssignAjax('errors', $oUser->_getValidateErrors());
            $this->Message_AddErrorSingle(null);
        }
    }
    
    public function EventLogin()
    {
        echo 'login';
    } 
}