<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_ParenthesisAsParameter extends Analyzer {
    /* 2 methods */

    public function testPhp_ParenthesisAsParameter01()  { $this->generic_test('Php/ParenthesisAsParameter.01'); }
    public function testPhp_ParenthesisAsParameter02()  { $this->generic_test('Php/ParenthesisAsParameter.02'); }
}
?>