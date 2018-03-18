<?php

class PluginSociality_HookRegisterForm extends Hook
{

    public function RegisterHook()
    {
        $this->AddHook('template_registration_begin', 'RegisterBegin', __CLASS__);
        $this->AddHook('viewer_init_assign', 'AssignRequest', __CLASS__);
    }

    public function RegisterBegin($aParams)
    {
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            return $this->RegisterButsHtml();
        }
        $sProvider = $this->Session_Get('provider');
        
        $oViewer = $this->Viewer_GetLocalViewer();
        /*
         * Проверяем на существование email. Если есть, показываем текстовку или  делаем вход
         */   
        $oUser = Engine::GetEntity('ModuleUser_EntityUser');        
        if( $oUser->ValidateMailExists($oProfileData->email, []) !== true ) {
            $oUser = $this->User_GetUserByMail($oProfileData->email);
            
            if(!$oSocial = $this->PluginSociality_Social_GetSocialByFilter(['user_id' => $oUser->getId(), 'social_type' => $sProvider])){
                $oViewer->Assign('sAttach', $this->Lang_Get('plugin.sociality.auth.error.email_exists', ['email' => $oProfileData->email]), true);
                $oViewer->Assign('sAttachText', $this->Lang_Get('plugin.sociality.auth.error.email_exists_text', 
                        ['provider' => $sProvider, 'email' => $oProfileData->email]), true);
            }else{
            
                $this->User_Authorization($oUser, false);
                Router::Location($oUser->getUserWebPath());
            }
        }
        
        
        $oViewer->Assign('oProfileData', $oProfileData, true);
        $oViewer->Assign('sProvider', $sProvider, true);
        return $oViewer->Fetch('component@sociality:profile');
    }
    
    public function AssignRequest($param) {
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            return false;
        }
        $_REQUEST['login'] = $this->PluginSociality_Social_GetLoginFromProfileData($oProfileData);
        $_REQUEST['mail'] = $oProfileData->email;
    }
    
    public function RegisterButsHtml() {
        
        $aButs = $this->PluginSociality_Social_GetButsEnable('register');
        
        $this->Viewer_Assign('aButs', $aButs, true);
        $this->Viewer_Assign('sType', 'register');
        
        return $this->Viewer_Fetch('component@sociality:buts');
    }
}