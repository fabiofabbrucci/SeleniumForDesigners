<?php
/**
 * 
 * @author Fabio Fabbrucci fabio.fabbrucci@gmail.com
 *
 */
class CssConfig{
	
	public $code = 'SeleniumForDesigners';
	public $url_local;
	public $url;
	public $max_urls = 10;
	
	private $clicks = array(
		"link=Azienda",
		"link=Contatti",
	);
	private $urls = array();

    public $browser = '*firefox'; 
    public $base_url = null;
    public $base_path = null;
    public $url_screen = null;
    
    function __construct(){
    	if(!$this->code)
    		throw new Exception('Necessario impostare un CODE');
    	$this->url_local = 'http://localhost/'.$this->code.'/';
		$this->url = 'http://localhost/'.$this->code.'/';
    	$this->base_url = $this->url_local;
    	$this->base_path = '/var/www/'.$this->code.'/test/functional/CssRegression/';
    	$this->url_screen = $this->base_url . 'test/functional/CssRegression/';
    }
    
    function add_url($url){
    	$this->urls[] = $url;
    }
    function get_urls(){
    	return $this->urls;
    }
    function add_click($click){
    	$this->clicks[] = $click;
    }
    function get_clicks(){
    	return $this->clicks;
    }
    
    function setTestCase($tc){
    	$tc->setScreenshotPath($this->base_path);
    	$tc->setScreenshotUrl($this->url_screen);
		$tc->setBrowserUrl($this->base_url);
    }
    
    function setTestCaseForSaving($tc){
    	$this->base_path .= "screen";
    	$this->url_screen .= "screen";
    	$tc->setBrowser('*firefox');
    	$this->setTestCase($tc);
    }
    
    function setTestCaseForChecking($tc){
    	$tc->setBrowser('*firefox');
    	$this->url_screen .= "cache";
    	$tc->setPathOriginal($this->base_path . 'screen');
    	$tc->setPathDiff($this->base_path . 'diff');
    	$tc->setPathLog($this->base_path . 'log');
    	$this->base_path .= "cache";
    	$this->setTestCase($tc);
    }
    
    function clean_name($name){
    	return str_replace(array('?','&','='), '.', $name);
    }
}