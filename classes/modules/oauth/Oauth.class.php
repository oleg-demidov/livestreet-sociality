<?php


class PluginSociality_ModuleOauth extends Module
{

    /** @var PluginSociality_ModuleOauth_MapperOauth */
    protected $mapper;
    
    protected $provider;

    public function Init()
    {
        $this->mapper = Engine::GetMapper(__CLASS__);
        //$Oauth = Engine::GetEntity('PluginSocialauthlite_Oauth_Oauth');
    }
    
    public function SetProvider($provider){  
        
        $this->provider = $provider;
        
    }
    public function DeleteSocialByUserId($iUserId) {
        return $this->mapper->DeleteSocialByUserId($iUserId);
    }
    
    public function AddSocial(PluginSociality_ModuleOauth_EntitySocial $oSocial)
    {
        return $this->mapper->AddSocial($oSocial);
    }

    public function GetUserBySocialID( $social_user_id )
    {
        return $this->mapper->GetUserBySocialTypeID($this->provider, $social_user_id);
    }
    
    public function GetSocialBySocialID( $social_user_id, $type=null )
    {
        return $this->mapper->GetSocialBySocialTypeID($type?$type:$this->provider, $social_user_id);
    }
    
    public function UpdateSocial(PluginSociality_ModuleOauth_EntitySocial $oSocial)
    {
        return $this->mapper->UpdateSocial($oSocial);
    }
    
    public function GetSocialItemsByUserId($iUserId) 
    {
        return $this->mapper->GetSocialItemsByUserId($iUserId);
    }
    
    public function CreateSocialEntity( $iIdUser, $iIdSocialUser )
    {
        /** @var PluginSocialauthlite_ModuleOauth_EntityOauth $Oauth */
        $oSocial = Engine::GetEntity('PluginSociality_Oauth_Social');
        $oSocial
            ->SetDateReceived(date('Y-m-d H:i:s'))
            //->SetDateExpire(date('Y-m-d H:i:s', $expire))
            //->SetToken($token)
            ->SetSocialID($iIdSocialUser)
            ->SetSocialType($this->provider)
            ->SetUserID($iIdUser);

        return $oSocial;
    }
    
    
    public function CreateUserEntity($aProfile)
    {
        $oUser = Engine::GetEntity('ModuleUser_EntityUser');
        /**
         * Заполняем поля (данные)
         */
        $oUser->setLogin($aProfile['login']);
        $oUser->setMail($aProfile['email']);
        $oUser->setProfileName($aProfile['name']);
        $oUser->setPassword(md5($aProfile['pass']));
        $oUser->setDateRegister(date("Y-m-d H:i:s"));
        $oUser->setIpRegister(func_getIp());
        $oUser->setActivate(1);
        $oUser->setActivateKey(null);
        if(isset($aProfile['gender']))$oUser->setProfileSex($aProfile['gender']);
        $oUser->setProfileCountry($aProfile['country']);
        $oUser->setProfileCity($aProfile['city']);
        if(isset($aProfile['bdate']))$oUser->setProfileBirthday($aProfile['bdate']);
        
        return $oUser;
    }
    
    public function GenerateLogin($aNames){
        
        function NormName($sName){
            return mb_substr( trim($sName), 0, 10 );
        }
        
        $sLoginTry = ucfirst($this->rus2translit(NormName($aNameSplit[0])));
        if($sLoginTry && !$this->User_GetUserByLogin($sLoginTry)){
            return $sLoginTry;
        }
        
        $sLoginTry = $this->rus2translit(NormName($aNames[1]));
        if($sLoginTry  && !$this->User_GetUserByLogin($sLoginTry)){
            return $sLoginTry;
        }
        
        $sLoginTry = $this->rus2translit(NormName($aNames[1].$aNames[2]));
        if($sLoginTry && !$this->User_GetUserByLogin($sLoginTry)){
            return $sLoginTry;
        }
        
        
        
        if($aNames[3]){
            $sLoginTry = $this->rus2translit(NormName($aNames[1].$aNames[2].$aNames[3]));
            if($sLoginTry && !$this->User_GetUserByLogin($sLoginTry)){
                return $sLoginTry;
            }
        }
        
    }
    
    public function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya', 
            ' '=>''
        );
        return strtr(trim($string), $converter);
    }
    
    public function SetUserGeo($aGeo, &$oUser) {
        
        function SetUserGeoTarget($aGeo, $iIdUser){
            if($aGeo['count']){
                $this->Geo_CreateTarget($aGeo['collection'][0], 'user', $iIdUser);
            }
        }
        
        function GetFilter($sGeo){
            return ['name_ru_like'=>'%'.$sGeo.'%', 'name_en_like'=>'%'.$sGeo.'%'];
        }
        
        if($aGeo['country']){
            $aGeoCountry = $this->Geo_GetCountries(GetFilter($aGeo['country']), [], 1, 1);
            //echo 'country';print_r($aGeoCountry);
            SetUserGeoTarget($aGeoCountry, $oUser->getId());
        }
        
        if($aGeo['region']){   
            $aGeoRegion = $this->Geo_GetRegions(GetFilter($aGeo['region']), [], 1, 1);
            //echo 'region';print_r($aGeoRegion);
            SetUserGeoTarget($aGeoRegion, $oUser->getId());
        }
                
        if($aGeo['city']){
            $aGeoCity = $this->Geo_GetCities(GetFilter($aGeo['city']), [], 1, 1);
            //echo 'city';print_r($aGeoCity);
            SetUserGeoTarget($aGeoCity, $oUser->getId());
        }
        
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
        curl_setopt($curl, CURLOPT_URL, $url);
	 
	//имитируем браузер опера
	curl_setopt($curl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.7.62 Version/11.01');
	 
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
    
    public function GetOrderButtons()
    {
        
        $config = Config::Get('plugin.sociality.ha');
        $order = explode(',', Config::Get('plugin.sociality.order'));
        
        $order2 = array();
        
        for ($i=0; sizeof($order) > $i; $i++){
            $provider = trim($order[$i]);
            if(!isset($config['providers'][$provider]))
                continue;
            if($config['providers'][$provider]['enabled']){
                $order2[] = $provider;
            }
        }
        return $order2;
    }
    
   
}