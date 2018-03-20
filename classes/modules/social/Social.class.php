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
        
        if( $oProfileData->domain ){
            if($oUser->ValidateLoginExists($oProfileData->domain, []) === true){
                return $oProfileData->domain;
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
        
        if($iFieldId = $this->User_userFieldExistsByName( strtolower($sProvider) ) and $oProfileData->profileURL){
            $this->User_setUserFieldsValues($iUserId, array($iFieldId[0]['id'] => $oProfileData->profileURL));
        }
        return true;
    }
    
    public function GetGeoByData($oProfileData) {
        
        if($oGeo = $this->Geo_GetCities(['name_ru_like' => $oProfileData->city?$oProfileData->city:$oProfileData->home_town], [], 1, 1) and 
                $oGeo['count']){
            return $oGeo['collection'][0];
        }
        
        if($oGeo = $this->Geo_GetRegions(['name_ru_like' => $oProfileData->region], [], 1, 1) and $oGeo['count']){
            return $oGeo['collection'][0];
        }
        
        if($oGeo = $this->Geo_GetCountries(['name_ru_like' => $oProfileData->country], [], 1, 1) and $oGeo['count']){
            return $oGeo['collection'][0];
        }
        return null;
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
    
    public function GetGender($oProfileData) {
        $aTransGender = [
            'male' => 'man',
            'female' => 'woman'
        ];
                
        if( !in_array( $oProfileData->gender, array_keys($aTransGender) ) ){
            return 'other';
        }
        
        return $aTransGender[$oProfileData->gender];
    }
    
    public function GetBirthdayDatetime($oProfileData) {
        if(!$oProfileData->bdate){
            return null;
        }
        
        $date = DateTime::createFromFormat('j.m.Y', $oProfileData->bdate);
        return $date->format("Y-m-d H:i:s");
    }
    
    public function GetPhotoByData($oProfileData) {
        $sUrl = $this->GetPhotoFromProfileData($oProfileData);    
        return $this->GetPhotoByUrl($sUrl);
    }
    
    /*
     * Скачивание файла
     */
    public function GetPhotoByUrl($sUrl) {
        
        $sFileTmp = Config::Get('sys.cache.dir') .'image/'. func_generator();
        if(!file_exists(Config::Get('sys.cache.dir') .'image/')){
            mkdir ( Config::Get('sys.cache.dir') .'image/' ,  0777 , true  );
        }
        if(!$oCurl = curl_init($sUrl)){
            $this->Message_AddError('Avatar error curl');
            return null;
        }
        if(!$fPhoto = fopen($sFileTmp, 'wb')){
            $this->Message_AddError('Avatar error open');
            return null;
        }
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($oCurl, CURLOPT_BINARYTRANSFER,1);
        curl_setopt($oCurl, CURLOPT_FILE, $fPhoto);
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_URL, $sUrl);
	 
	//имитируем браузер опера
	curl_setopt($oCurl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.7.62 Version/11.01');
	 
        curl_exec($oCurl);
        curl_close($oCurl);
        fclose($fPhoto);
        
        /*
         * Если не получилось curl
         */
        if(!filesize($sFileTmp)){
            file_put_contents($sFileTmp, file_get_contents($sUrl));
        }

        return $sFileTmp;
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