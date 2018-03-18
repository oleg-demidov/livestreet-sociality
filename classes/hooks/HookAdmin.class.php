<?php

class PluginSociality_HookAdmin extends Hook
{

    public function RegisterHook()
    {
        $this->AddHook('admin_delete_content_after', 'UserContentDelete', __CLASS__, 10);
    }
    
    public function UserContentDelete(&$aParams) {
        $aSocials = $this->PluginSociality_Social_GetSocialItemsByFilter(['user_id' => $aParams['oUser']->getId()]);
        foreach($aSocials as $oSocial){
            $oSocial->Delete();
        }
    }
}