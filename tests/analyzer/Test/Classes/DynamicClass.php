<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DynamicClass extends Analyzer {
    /* 1 methods */

    public function testClasses_DynamicClass01()  { $this->generic_test('Classes_DynamicClass.01'); }
}
?>