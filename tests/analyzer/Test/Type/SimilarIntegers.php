<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SimilarIntegers extends Analyzer {
    /* 1 methods */

    public function testType_SimilarIntegers01()  { $this->generic_test('Type/SimilarIntegers.01'); }
}
?>