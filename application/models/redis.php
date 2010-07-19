<?php

class Redis extends Model {

    function Redis()
    {
        parent::Model();
		require_once APPPATH.'libraries/Predis.php';
		$this->predis = new Predis\Client();
		$this->predis->connect();
		$this->key_prefix = 'justincampbell.me';
		$this->key_prefix .= ':';
		$this->logging = FALSE;
    }
   
}

?>