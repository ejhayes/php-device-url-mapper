<?php
define('TEST_ROOT', dirname(__FILE__) . '/');
require_once('simpletest/autorun.php');

class AllTestsSuite extends TestSuite {
    function __construct() {
        parent::__construct();
		//$this->addFile('someFile.php');
        $this->collect(dirname(__FILE__) . '/unit',
                       new SimplePatternCollector('/_test.php/'));
    }
}
?>