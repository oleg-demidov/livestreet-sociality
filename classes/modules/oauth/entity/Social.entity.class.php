<?php

class PluginSociality_ModuleOauth_EntitySocial extends Entity
{

	public function GetID() { return $this->_getDataOne('id'); }
        
	public function GetUserID() { return $this->_getDataOne('user_id'); }

	public function GetSocialID() { return $this->_getDataOne('social_id'); }

	public function GetSocialType() { return $this->_getDataOne('social_type'); }
        
        public function GetProfileUrl() { return $this->_getDataOne('profile_url'); }

	public function GetToken() { return $this->_getDataOne('token'); }

	public function GetDateReceived() { return $this->_getDataOne('date_received'); }

	public function GetDateExpire() { return $this->_getDataOne('date_expire'); }

	public function SetID($value)
	{
		$this->_aData['id'] = $value;
		return $this;
	}

	public function SetUserID($value)
	{
		$this->_aData['user_id'] = $value;
		return $this;
	}
        
        public function SetProfileUrl($value)
	{
		$this->_aData['profile_url'] = $value;
		return $this;
	}

	public function SetSocialID($value)
	{
		$this->_aData['social_id'] = $value;
		return $this;
	}

	public function SetSocialType($value)
	{
		$this->_aData['social_type'] = $value;
		return $this;
	}

	public function SetToken($value)
	{
		$this->_aData['token'] = $value;
		return $this;
	}

	public function SetDateReceived($value)
	{
		$this->_aData['date_received'] = $value;
		return $this;
	}

	public function SetDateExpire($value)
	{
		$this->_aData['date_expire'] = $value;
		return $this;
	}
}