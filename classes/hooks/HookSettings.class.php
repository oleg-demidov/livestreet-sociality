<?php

class PluginSociality_HookSettings extends Hook
{

    public function RegisterHook()
    {
        $this->AddHook('template_user_settings_profile_end', 'SettingsForm', __CLASS__);
        $this->AddHook('sociality_get_data', 'SettingsAddBind', __CLASS__, 666);
        $this->AddHook('action_shutdown_settings', 'SettingsAddComponentBind', __CLASS__);
            
    }

    public function SettingsForm($aParams)
    {
        if(!$oUserCurrent = $this->User_GetUserCurrent()){
            return;
        }
        $aSocials = $this->PluginSociality_Oauth_GetSocialItemsByUserId($oUserCurrent->getId());
        
        $this->Viewer_Assign('aSocials', $aSocials,true);
        
        $buttons = $this->PluginSociality_Oauth_GetOrderButtons();
        
        $this->Viewer_Assign('sSizeButton', Config::Get('plugin.sociality.size'));
        
        $this->Viewer_Assign('isBinds', 1);
        
        $this->Viewer_Assign('sUriPluginSkin', $this->Component_GetWebPath('sociality:buts'));
        
        $this->Viewer_Assign('aButtonsNames', $buttons);
        
        return $this->Viewer_Fetch('component@sociality:binds');
    }
    
    public function SettingsAddBind($aParams) {
        if(Router::GetParam(0) != 'bind'){
            return;
        }
        if(!$oUserCurrent = $this->User_GetUserCurrent()){
            return;
        }

        if(! $oSocial = $this->PluginSociality_Oauth_GetSocialBySocialID( $oUserProfile->identifier, $aParams['provider']))  
        {
            $oSocial = $this->PluginSociality_Oauth_CreateSocialEntity($oUserCurrent->getId(),  $aParams['data']->identifier);
            $oSocial->setProfileUrl($aParams['data']->profileURL);
            $this->PluginSociality_Oauth_AddSocial($oSocial);
        }
        else
        {         
            $oSocial->setUserId($oUserCurrent->getId());
            $this->PluginSociality_Oauth_UpdateSocial($oSocial);
        }
        
        if($iFieldId = $this->User_userFieldExistsByName(strtolower($aParams['provider']))){
            $this->User_setUserFieldsValues($oUserCurrent->getId(),array($iFieldId[0]['id'] => $aParams['data']->identifier));
        }
        
        Router::LocationAction('settings');
    }
    
    public function SettingsAddComponentBind($param) {
        $this->Component_Add('sociality:binds');
    }
    
}