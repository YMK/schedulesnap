<?php
require_once('simpletest/autorun.php');
class ShowPasses extends HtmlReporter {
    
    function paintPass($message) {
        parent::paintPass($message);
        print "<span class=\"pass\">Pass</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode("->", $breadcrumb);
        print "->$message<br />\n";
    }
}

SimpleTest::prefer(new ShowPasses());

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile('databaseTest.php');
        $this->addFile('otherTest.php');
    }
}




?>
