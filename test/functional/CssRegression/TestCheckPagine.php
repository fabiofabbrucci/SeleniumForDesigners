<?php
require_once 'DesignerSeleniumTestCase.php';

class TestCheckPagine extends PHPUnit_Extensions_DesignerSeleniumTestCase
{
	protected function setUp()
	{
		$this->config = new CssConfig();
		$this->config->setTestCaseForChecking($this);
	}
	
	public function testControlloLeImmagini()
	{
		$this->open('index.php');
		$bool_result = true;
		foreach($this->config->get_clicks() as $k=>$v){
			$this->click($v);
			$this->waitForPageToLoad("10000");
			$this->screenshotname = $this->config->code . 'pagina.click' . ($k+1);
			$this->captureScreenshot();
			if($this->compare2Images($this->screenshotname)){
				$bool_result = $bool_result and true;
				$result = "ok";
			}else{
				$bool_result = $bool_result and false;
				$result = "KO";
			}
			echo ($k+1) . ": " . $this->screenshotname . ": " . $result . "\r\n";
		}	
		$this->assertTrue($bool_result);	
	}
	
	function compare2Images($screenshotname){
		$ori = 	$this->path_Original . DIRECTORY_SEPARATOR . $screenshotname . '.png';
		$new = 	$this->screenshotPath . DIRECTORY_SEPARATOR . $screenshotname . '.png';
		$diff = $this->path_Diff . DIRECTORY_SEPARATOR . $screenshotname . '.png';
		$log = $this->path_Log . DIRECTORY_SEPARATOR . $screenshotname . '.txt';
		
		$istr = "compare -metric MAE $ori $new $diff 2> $log";
		exec($istr);
		$file_content = file($log);
		if(trim($file_content[0]) == '0 (0)'){
			return true;
		}
		return false;
	}
}
