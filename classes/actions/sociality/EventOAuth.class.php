<?php


/**
 * Description of EventTopic
 *
 * @author oleg
 */

class PluginSociality_ActionSociality_EventOAuth extends Event {
    
    public function Init()
    {
    }

    public function EventStart() {

        $this->SetTemplate(false);
        
        if(!$sRedirect = $this->Session_Get('authRedirect')) {
            return Router::ActionError('', $this->Lang_Get('plugin.sociality.auth.error.no_access') );
        }
        
        $providers = Config::Get('plugin.sociality.ha');
        
        if( !isset($providers['providers'][$this->sCurrentEvent]) ){
            return Router::ActionError('', $this->Lang_Get('plugin.sociality.auth.error.no_provider', ['name' => $this->sCurrentEvent]));                
        }
        
        $providers['base_url'] = Config::Get('path.root.web') . '/sociality/' . $this->sCurrentEvent. '/end';
        
        $oUserProfile = null;
        try{
            
            $hybridauth = new Hybrid_Auth( $providers );

            $оProvider = $hybridauth->authenticate( $this->sCurrentEvent );
            
            $oSession = $hybridauth->getSessionData( );

            $oUserProfile = $оProvider->getUserProfile();

        }
        catch( Exception $e ){
            return Router::ActionError('', $this->Lang_Get('plugin.sociality.auth.error.ha_auth_stop', ['name' => $this->sCurrentEvent]));
        }

        if(!is_object($oUserProfile)){            
            return Router::ActionError('', $this->Lang_Get('plugin.sociality.auth.error.ha_no_data', ['name' => $this->sCurrentEvent]));
        }  
        
        $this->Session_Set('provider', $this->sCurrentEvent);
        $this->Session_Set('oUserProfile', $oUserProfile);        
       
        
        Router::LocationAction( $sRedirect );
    }
    
    public function EventEnd()
    {
        /*
         * Необходимый редирект для hybridauth
         */
        $_REQUEST['hauth.done'] = $this->sCurrentEvent;
        Hybrid_Endpoint::process();
    } 
    
}