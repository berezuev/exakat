<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SessionVariables extends Analyzer {
    /* 1 methods */

    public function testPhp_SessionVariables01()  { $this->generic_test('Php/SessionVariables.01'); }
}
?>