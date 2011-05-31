<?php
require_once 'DesignerSeleniumTestCase.php';

class TestScorriLePagine extends PHPUnit_Extensions_DesignerSeleniumTestCase
{
	private $url_list_file = 'cache/urls.txt';
	
	protected function setUp()
	{
		$this->config = new CssConfig();
		$this->config->setTestCaseForSaving($this);
		$this->loadUrls();
	}
	
	private function loadUrls(){
		echo "Lancio il salvataggio degli urls (massimo ".$this->config->max_urls.") \n";
		require_once '../../inc/Parser.php';
		$this->p = new Parser('', $this->config->url, $this->config->max_urls);
		foreach($this->p->get_links() as $link)
			$this->config->add_url($link['url']);
		echo "Trovati ".count($this->config->get_urls())." urls \n";
		$this->saveUrls($this->p->get_links());
	}
	
	private function saveUrls($links){
		foreach($links as $link)
			$urls[] = $link['url'];
		file_put_contents($this->url_list_file, implode("\n",$urls));
	}
	
	public function testScanPage()
	{
		foreach(array_slice($this->config->get_urls(), 0, $this->config->max_urls, true) as $i=> $v){
			echo ($i+1).": $v \n";
			$v = trim($v);
			$this->screenshotname = $this->config->code . '.page-'.$this->config->clean_name($v);
			$this->open($v);
			$this->waitForPageToLoad("6000");
			$this->captureScreenshot();
		}		
		echo $this->config->url_local.'test/functional/CssRegression/screen/'."\n";
		echo str_replace("/", "\\", 'c:\\xampp\\htdocs\\'.$this->config->code.'\\test/functional/CssRegression/screen/');
	}
}
