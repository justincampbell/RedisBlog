<?php

class Blog_model extends Model {

    function Blog_model()
    {
        parent::Model();
		require_once APPPATH.'libraries/Predis.php';
		$this->redis = new Predis\Client();
		$this->redis->connect();
		$this->key_prefix = 'myblog:';
		$this->permalink = $config['base_url'] . ''; // can we get controller name?
		$this->logging = FALSE;
    }

    function GetPublished($start=1, $end=10)
    {
		return $this->redis->zrevrange($this->key_prefix.'published', $start-1, $end-1);
    }

    function GetArticles($start=1, $end=10)
    {
		return $this->redis->zrevrange($this->key_prefix.'articles', $start-1, $end-1);
    }	

    function GetEntry($id, $all=FALSE) // $all allows it to be returned even if not published (editer/drafts/export)
    {
    	if (($this->redis->zscore($this->key_prefix.'published',$id)) || ($all)) {
    		$entry = json_decode($this->redis->get($this->key_prefix.'entry:'.$id));
    		$entry->permalink = $this->permalink.$entry->id;
			$entry->published = $this->redis->zscore($this->key_prefix.'published',$id);
	    	return $entry;
		} else {
			return;
		}
    }

    function SetEntry($id, $entry)
    {
		$this->redis->zadd($this->key_prefix.'articles', $entry->created ,$id);
		$entry = json_encode($entry);
		return $this->redis->set($this->key_prefix.'entry:'.$id, $entry);
    }
    
    function PublishEntry($id, $timestamp)
    {
		return $this->redis->zadd($this->key_prefix.'published', $timestamp, $id);
    }

	function AddView($id=NULL)
	{
		$this->load->library('user_agent');
		if ($this->agent->is_robot()) return;
		$return->total = $this->redis->incr($this->key_prefix.'entry:'.$id.':views');
		//IP + browser
		$this->redis->sadd(
			$this->key_prefix.'entry:'.$id.':visitors',
			md5($_SERVER["REMOTE_ADDR"] . $this->agent->agent_string())
		);
		$return->unique = $this->redis->scard($this->key_prefix.'entry:'.$id.':visitors');
		/*
		//Cookie w/ expiration
		$this->load->helper('cookie');
		if (get_cookie('visitor_id_'.$id)) {
			return;
		} else {
			$visitor_id = md5(rand());
			if (set_cookie('visitor_id_'.$id, $visitor_id, 43200))
				$this->redis->sadd($this->key_prefix.'views:unique:'.$id, $visitor_id);
		}
		*/
		return $return;
	}

    function GetViews($id)
    {
		$return->total = $this->redis->get($this->key_prefix.'entry:'.$id.':views');
		$return->unique = $this->redis->scard($this->key_prefix.'entry:'.$id.':visitors');
		return $return;
    }

/*	// Future stuff
    function GetTags($id)
    {
		return $this->redis->smembers($this->key_prefix.$id.':tags');
    }
*/

}

?>