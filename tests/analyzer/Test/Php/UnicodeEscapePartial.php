<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnicodeEscapePartial extends Analyzer {
    /* 1 methods */

    public function testPhp_UnicodeEscapePartial01()  { $this->generic_test('Php/UnicodeEscapePartial.01'); }
}
?>