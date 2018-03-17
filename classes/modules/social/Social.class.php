<?php


class PluginSociality_ModuleSocial extends ModuleORM
{
    
    public function Init()
    {
        parent::Init();        
    }
    
    public function GetLoginFromProfileData($oProfileData) {
        if(property_exists($oProfileData, 'displayName') ){
            $sFrom ='oo';
        }
    }
}