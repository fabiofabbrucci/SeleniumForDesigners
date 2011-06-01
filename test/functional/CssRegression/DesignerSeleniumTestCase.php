<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
require_once 'CssConfig.php';

/**
 * 
 * @author Fabio FAbbrucci fabio.fabbrucci@gmail.com
 *
 */
abstract class PHPUnit_Extensions_DesignerSeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase
{
	protected $config;
	
	protected $screenshotname = null;
    protected $path_Original = null;
    protected $path_Diff = null;
    protected $path_Log = null;
	
    protected function onNotSuccessfulTest(Exception $e)
    {
        if ($e instanceof PHPUnit_Framework_ExpectationFailedException) {
            $buffer  = 'Current URL: ' . $this->drivers[0]->getLocation() . "\n";
            $message = $e->getCustomMessage();
     		if ($this->captureScreenshotOnFailure && !empty($this->screenshotPath) && !empty($this->screenshotUrl)) {
				$buffer .= $this->captureScreenshot();
        	}
        	return '';
            
        }
        try {
            $this->stop();
        }catch (RuntimeException $e) {
        }

        if ($e instanceof PHPUnit_Framework_ExpectationFailedException) {
            if (!empty($message)) {
                $buffer .= "\n" . $message;
            }
            $e->setCustomMessage($buffer);
        }
        throw $e;
    }
    
    protected function captureScreenshot(){
    	if(!$screenshotname = $this->getScreenshotname()) $screenshotname = $this->testId;
    	$filename = $this->screenshotPath . "/" . $screenshotname . '.png';
    	if(!file_exists($this->screenshotPath))
    		throw new Exception('Doesnt exist folder '.$this->screenshotPath);
		$this->drivers[0]->captureEntirePageScreenshot( $filename );
		return 'Screenshot: ' . $this->screenshotUrl . '/' . $screenshotname . ".png\n";
    }
    
    protected function getScreenshotname(){
    	return $this->screenshotname;
    }
    
    public function setScreenshotPath($path){
    	$this->screenshotPath = $path;
    }
    
    public function setScreenshotUrl($url){
    	$this->screenshotUrl = $url;
    }
    
    public function setPathOriginal($path){
    	$this->path_Original = $path;
    }
    
    public function setPathLog($path){
    	$this->path_Log = $path;
    }
    
    public function setPathDiff($path){
    	$this->path_Diff = $path;
    }
}
