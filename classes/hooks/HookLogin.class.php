<?php

class PluginSociality_HookLogin extends Hook
{

    public function RegisterHook()
    {
            $this->AddHook('template_form_login_begin', 'login_form_injection', __CLASS__);
            $this->AddHook('template_registration_end', 'login_form_injection', __CLASS__, 10);
            $this->AddHook('admin_delete_content_after', 'UserContentDelete', __CLASS__, 10);
    }

    public function login_form_injection()
    {      
        $buttons = $this->PluginSociality_Oauth_GetOrderButtons();
        
        $this->Viewer_Assign('sSizeButton', Config::Get('plugin.sociality.size'));
        
        $this->Viewer_Assign('sUriPluginSkin', $this->Component_GetWebPath('sociality:buts'));
        
        $this->Viewer_Assign('aButtonsNames', $buttons);
        
        return $this->Viewer_Fetch('component@sociality:buts');
    }
    
    public function UserContentDelete(&$aParams) {
        $this->PluginSociality_Oauth_DeleteSocialByUserId($aParams['oUser']->getId());
    }
}