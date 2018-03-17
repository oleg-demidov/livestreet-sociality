<?php

class PluginSociality_ModuleOauth_MapperOauth extends Mapper
{

    public function AddSocial(PluginSociality_ModuleOauth_EntitySocial $oSocial)
    {
        $oauth_table = Config::Get('plugin.sociality.table.sociality');
        $sql = "INSERT INTO {$oauth_table} SET ?a ON DUPLICATE KEY UPDATE ?a";
        $aPars = $oSocial->_getData();
        if ($this->oDb->query($sql, $aPars, $aPars) === 0) {
            return true;
        }
        return false;
    }

    public function UpdateSocial(PluginSociality_ModuleOauth_EntitySocial $oSocial)
    {
        $oauth_table = Config::Get('plugin.sociality.table.sociality');
        $sql = "UPDATE {$oauth_table}
            SET
              date_received = ?,
              user_id = ?d             
            WHERE
              id = ?d"; 
        
        if ($this->oDb->query($sql,
                $oSocial->GetDateReceived(),
                $oSocial->getUserId(),
                $oSocial->GetID()
                
            ) === 0
        ) {
            return true;
        }
        return false;
    }

    public function OauthExists($social_type, $social_user_id)
    {
        $oauth_table = Config::Get('plugin.sociality.table.sociality');
        $sql = "SELECT COUNT(*) FROM {$oauth_table} WHERE social_type = ? AND social_id = ?";
        $res = $this->oDb->query($sql, $social_type, $social_user_id);
        return (int)$res[0]['COUNT(*)'] > 0;
    }

    public function GetSocialBySocialTypeID($social_type, $id_user)
    {
        $oauth_table = Config::Get('plugin.sociality.table.sociality');
        
        $sql = "SELECT * FROM {$oauth_table} WHERE social_type = ? AND social_id = ?";
        
        
        if($aRow = $this->oDb->selectRow($sql, $social_type, $id_user)){
            
            return Engine::GetEntity('PluginSociality_Oauth_Social', $aRow);
            
        }
        return false;
    }

    public function GetUserBySocialTypeID($social_type, $social_user_id)
    {
        $social_table = Config::Get('plugin.sociality.table.sociality');
        
        $user_table = Config::Get('db.table.user');
        
        $sql = "SELECT u.* FROM {$user_table} AS u JOIN {$social_table} as o ON o.social_type = ? AND o.social_id = ? AND u.user_id = o.user_id LIMIT 1";
        
        //echo $sql,$social_type, $social_user_id;
        
        if ($aRow = $this->oDb->selectRow($sql, $social_type, $social_user_id)) {
           
            return Engine::GetEntity('User', $aRow);
       
        }

        return null;
    }
    
    public function GetSocialItemsByUserId($iUserId)
    {
        $oauth_table = Config::Get('plugin.sociality.table.sociality');
        
        $sql = "SELECT * FROM {$oauth_table} WHERE user_id = ?";
        
        $aRes = array();
        if ($aRows = $this->oDb->select($sql, $iUserId)) {
            foreach ($aRows as $aRow) {
                $aRes[] = Engine::GetEntity('PluginSociality_Oauth_Social', $aRow);
            }
        }
        return $aRes;
    }
    
    public function DeleteSocialByUserId($iUserId) {
        $sql = "DELETE FROM ".Config::Get('plugin.sociality.table.sociality')." WHERE user_id = ?d";
        return $this->oDb->query($sql, $iUserId);
    }
}