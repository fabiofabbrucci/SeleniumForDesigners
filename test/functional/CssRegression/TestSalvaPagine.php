<?php
require_once 'DesignerSeleniumTestCase.php';

class TestSalvaPagine extends PHPUnit_Extensions_DesignerSeleniumTestCase
{
	protected function setUp()
	{
		$this->config = new CssConfig();
		$this->config->setTestCaseForSaving($this);
	}
	
	public function testFunzioneCheSalvaLeImmagini()
	{
		$this->open('index.php');
		foreach($this->config->get_clicks() as $k=>$v){
			$this->click($v);
			$this->waitForPageToLoad("10000");
			$this->screenshotname = $this->config->code . 'pagina.click' . ($k+1);
			$this->captureScreenshot();
		}		
		$this->assertTrue(true);
	}
}
