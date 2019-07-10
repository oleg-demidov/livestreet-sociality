<?php


/**
 * Description of EventTopic
 *
 * @author oleg
 */

use Hybridauth\Hybridauth;

class PluginSociality_ActionSociality_EventOAuth extends Event {
    
    public function Init()
    {
    }

    public function EventStart() {

        $this->SetTemplate(false);
        
        if(!$sRedirect = $this->Session_Get('authRedirect')) {
            return Router::ActionError('', $this->Lang_Get('plugin.sociality.auth.error.no_access') );
        }
        
        $config = Config::Get('plugin.sociality.ha');
        
        if( !isset($config['providers'][$this->sCurrentEvent]) ){
            return Router::ActionError('', $this->Lang_Get('plugin.sociality.auth.error.no_provider', ['name' => $this->sCurrentEvent]));                
        }
        
        $config['callback'] = Config::Get('path.root.web') . '/sociality/' . $this->sCurrentEvent. '/start';
                
        $oUserProfile = null;
        try{
            
            $hybridauth = new Hybridauth( $config );

            $оProvider = $hybridauth->authenticate( $this->sCurrentEvent );
            
            $oSession = $hybridauth->getSessionData( );

            $oUserProfile = $оProvider->getUserProfile();

        }
        catch( Exception $e ){
            return Router::ActionError(
                $e -> getMessage(). get_class($e), 
                $this->Lang_Get(
                    'plugin.sociality.auth.error.ha_auth_stop', 
                    ['name' => $this->sCurrentEvent])
            );
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