<?php


class PluginSociality_ModuleSocial extends ModuleORM
{
    
    public function Init()
    {
        parent::Init();        
    }
    
    public function GetLoginFromProfileData($oProfileData) {
        $oUser = Engine::GetEntity('ModuleUser_EntityUser');
        
        if( $oProfileData->displayName ){
            if($oUser->ValidateLoginExists($oProfileData->displayName, []) === true){
                return $oProfileData->displayName;
            }
        }
        
        
        if( $oProfileData->email ){
            $sEmailLogin= substr($oProfileData->email, 0, stripos( $oProfileData->email , '@'));
            if($oUser->ValidateLoginExists( $sEmailLogin, [] ) === true){
                return $sEmailLogin;
            }
        }
        
        if( $oProfileData->firstName ){
            $sName = $this->Text_Transliteration( $oProfileData->firstName . $oProfileData->lastName);
            if($oUser->ValidateLoginExists( $sName, [] ) === true){
                return $sName;
            }
        }
        
        return $oProfileData->identifier;
    }
    
    public function CreateRelation($oProfileData, $sProvider, $iUserId) {
        if($oSocial = $this->PluginSociality_Social_GetSocialByFilter(['social_id' => $oProfileData->identifier, 
            'social_type' => $sProvider])){
            return $this->Lang_Get('plugin.sociality.auth.error.social_busy');
        }
        $oSocial = Engine::GetEntity('PluginSociality_Social_Social');
        $oSocial->setUserId( $iUserId );
        $oSocial->setProfileUrl( $oProfileData->profileURL );
        $oSocial->setSocialId( $oProfileData->identifier );
        $oSocial->setSocialType( $sProvider );
        $oSocial->Save();
        
        return true;
    }
    
    public function GetPhotoFromProfileData($oProfileData) {
        
        if( $oProfileData->photoURL ){
            return $oProfileData->photoURL;
        }
        
        if( $oProfileData->photo_max ){
            return $oProfileData->photo_max;
        }
        
        return $oProfileData->photo_rec;
    }
    
    
    public function GetButsRegister() {
        $aButs = $this->GetButsEnable('register');
    }
    
    public function GetOrderProviders() {
        $aProviders = Config::Get('plugin.sociality.ha.providers');
        $aOrder = explode(',', Config::Get('plugin.sociality.order')); 
        
        $aProvidersOrder = [];
        
        foreach($aOrder as $sProvider){
            if(!isset($aProviders[$sProvider])){
                continue;
            }
            if(!$aProviders[$sProvider]['enabled']){
                continue;
            }
            $aProvidersOrder[$sProvider] = $aProviders[$sProvider];
        }
        return $aProvidersOrder;
    }
    
    public function GetButsEnable($sType) {
        $aProvidersOrder = array_keys( $this->GetOrderProviders() ) ;
        
        $aButsOrder = [];
        
        foreach($aProvidersOrder as $sProvider){
            $aButsOrder[] = [
                'img' => $this->Component_GetWebPath('sociality:buts').'/img/'.$sProvider.'-'.Config::Get('plugin.sociality.size').'.png',
                'title' => $this->Lang_Get('plugin.sociality.auth.buts.'.$sType, ['provider' => $sProvider]),
                'url' => Router::GetPath('sociality/'.$sType.'/'.$sProvider)
            ];
        }
        
        return $aButsOrder;
    }
}