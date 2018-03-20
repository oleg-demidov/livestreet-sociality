<?php


/**
 * Description of EventSettings
 *
 * @author oleg
 */

class PluginSociality_ActionSociality_EventSettings extends Event {
    
   // TODO: Возможность отвязки
    public function EventAttach() {
        if(!$oUser = $this->User_GetUserCurrent()){
            Router::LocationAction('/');
        }
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            if(!$this->GetParam(0)){
                return Router::ActionError('',$this->Lang_Get('plugin.sociality.auth.error.no_provider_name'));
            }
            $this->Session_Set('authRedirect', 'sociality/attach');
            Router::LocationAction('sociality/'. $this->GetParam(0).'/start');
        }
        
        $sProvider = $this->Session_Get('provider');
        
        $this->Session_Drop('oUserProfile');
        $this->Session_Drop('provider');
        /*
        * Привязка социальной сети
        */        
        if(($sMess = $this->PluginSociality_Social_CreateRelation($oProfileData, $sProvider, $oUser->getId())) !== true){
            $this->Message_AddError($sMess, '', true);
        }
        
        $this->Message_AddError($this->Lang_Get('plugin.sociality.auth.profile.attach_success', $sProvider), '', true);
        
        Router::LocationAction('settings');
    }
}