<?php

class PluginSociality_HookSettings extends Hook
{

    public function RegisterHook()
    {
        $this->AddHook('template_user_settings_profile_end', 'SettingsForm', __CLASS__);
            
    }

    public function SettingsForm($aParams)
    {
        $aButs = $this->PluginSociality_Social_GetButsEnable('attach');
        
        $this->Viewer_Assign('aButs', $aButs, true);
        $this->Viewer_Assign('sType', 'attach');
        
        return $this->Viewer_Fetch('component@sociality:buts');       
        
    }
    
}