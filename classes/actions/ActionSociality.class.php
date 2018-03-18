<?php


class PluginSociality_ActionSociality extends ActionPlugin
{
    
    public function Init()
    {        
       
    }


    protected function RegisterEvent()
    {
        $this->RegisterEventExternal('OAuth','PluginSociality_ActionSociality_EventOAuth');
        $this->AddEventPreg('/^(.+)$/i', '/^start$/i', 'OAuth::EventStart');
        $this->AddEventPreg('/^(.+)$/i', '/^end$/i', 'OAuth::EventEnd');
        
        $this->RegisterEventExternal('Auth','PluginSociality_ActionSociality_EventAuth');
        $this->AddEventPreg('/^register$/i', '/^(.+)$/i', 'Auth::EventRegister');
        $this->AddEventPreg('/^login$/i', '/^(.+)?$/i', 'Auth::EventLogin');
        $this->AddEventPreg('/^register_hard$/i', 'Auth::EventRegisterHard');
        $this->AddEventPreg('/^reset_profile$/i', 'Auth::EventResetProfile');
        $this->AddEventPreg('/^attach_profile$/i', 'Auth::EventAttachWithEnter');
        
        $this->RegisterEventExternal('Settings','PluginSociality_ActionSociality_EventSettings');
        $this->AddEventPreg('/^attach$/i', '/^(.+)?$/i', 'Settings::EventAttach');
    }

}