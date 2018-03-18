<?php

class PluginSociality_HookLogin extends Hook
{

    public function RegisterHook()
    {
        $this->AddHook('template_form_login_begin', 'formLoginBegin', __CLASS__);
    }

    public function formLoginBegin()
    {      
        $aButs = $this->PluginSociality_Social_GetButsEnable('login');
        
        $this->Viewer_Assign('aButs', $aButs, true);
        
        $this->Viewer_Assign('sType', 'login');
        
        return $this->Viewer_Fetch('component@sociality:buts');
    }
    
}