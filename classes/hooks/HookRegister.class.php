<?php

class PluginSociality_HookRegister extends Hook
{

    public function RegisterHook()
    {
        $this->AddHook('registration_validate_before', 'RegisterValidateBefore', __CLASS__);
        $this->AddHook('registration_after', 'RegisterAfter', __CLASS__);
    }

    public function RegisterValidateBefore($aParams) {
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            return;
        }
        
        if(!$oUser = $aParams['oUser']){
            return;
        }
        /*
         * Отключаем активацию
         */        
        $oUser->setActivate(1);
        $oUser->setActivateKey(null);
        Config::Set('general.reg.activation', false);    
        
        $oUser->setProfileSex($this->PluginSociality_Social_GetGender($oProfileData));
        $oUser->setProfileBirthday($this->PluginSociality_Social_GetBirthdayDatetime($oProfileData));
        $oUser->setProfileName( $oProfileData->firstName . ' ' . $oProfileData->lastName );
        $oUser->setProfileCountry( $oProfileData->country );
        $oUser->setProfileRegion( $oProfileData->region );
        $oUser->setProfileCity( $oProfileData->city?$oProfileData->city:$oProfileData->home_town );
        
    }
    
    public function RegisterAfter($aParams) {
        $sProvider = $this->Session_Get('provider');
        
        if(!$oProfileData = $this->Session_Get('oUserProfile')){
            return;
        }
        
        if(!$oUser = $aParams['oUser']){
            return;
        }
        /*
        * Установка фото
        */

        if($sPathPhoto = $this->PluginSociality_Social_GetPhotoByData($oProfileData)){

            if($this->User_CreateProfilePhoto($sPathPhoto, $oUser)){

                $this->User_CreateProfileAvatar($oUser->getProfileFoto(), $oUser);
            }

            $this->Fs_RemoveFileLocal($sPathPhoto);

        }
        /*
         * Попытка привязать местоположение
         */
        if($oGeo = $this->PluginSociality_Social_GetGeoByData($oProfileData)){
            $this->Geo_CreateTarget($oGeo, 'user', $oUser->getId());
        }                
        /*
         * Привязка социальной сети
         */
        $oGeo = $this->PluginSociality_Social_CreateRelation($oProfileData, $sProvider, $oUser->getId());
        
        $this->Session_Drop('oUserProfile');
        $this->Session_Drop('provider');
    }
}