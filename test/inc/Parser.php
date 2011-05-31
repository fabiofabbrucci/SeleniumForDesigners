<?php
require_once('phpQuery-onefile.php');

class Parser{
	private $base_url;
	private $url;
	private $content;
	private $links = array();
	private $max;
	
	function __construct($url, $base, $max = 10){
		$this->url = $url;
		$this->base_url = $base;
		//$this->cont = $cont;
		$this->max = $max;
		$this->find_urls($this->base_url.$url);
	}
	
	function get_links(){
		return $this->links;
	}
	
	function find_urls($url){
		if(!$this->content = file_get_contents($url)){
			throw new Exception('Errore nel caricare il contenuto di '.$this->base_url.$this->url);
		}

		phpQuery::newDocumentHTML($this->content);
		
		foreach(pq('a') as $a){
			$link = pq($a)->attr('href');
			$link = $this->get_rel_url($link);
			if($this->is_link_inside($link)){
				$this->links[] = array('url' => $link, 'parsed' => 0);
			}
		}

		if(count($this->links) < $this->max){
			foreach($this->links as $index => $link){
				if($link['parsed'] == 0){
					$this->links[$index]['parsed'] = 1;
					$this->find_urls($this->get_clean_url($link['url']));
				}
			}
		}
		return true;
	}
	
	function get_clean_url($url){
		if(substr($url, 0, strlen($this->base_url)) == $this->base_url)
			return $url;
		return $this->base_url.$url;
	}
	
	function get_rel_url($url){
		if(substr($url, 0, strlen($this->base_url)) == $this->base_url)
			return substr($url, strlen($this->base_url));
		return $url;
	}
	
	function is_link_inside($link){
		foreach($this->links as $l)
			if($link == $l['url'])
				return false;
		if(strpos($link, '/intra/') !== false)
			return false;
		if(substr($link, 0, 1) == '?')
			return true;
		if(in_array($link, array('index.php', 'default.php')))
			return true;
		if(substr($link, 0, strlen($this->base_url)) == $this->base_url)
			return true;
		return false;
	}
}