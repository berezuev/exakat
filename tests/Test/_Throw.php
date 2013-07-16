<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Throw extends Tokenizeur {
    /* 4 methods */

    public function test_Throw01()  { $this->generic_test('_Throw.01'); }
    public function test_Throw02()  { $this->generic_test('_Throw.02'); }
    public function test_Throw03()  { $this->generic_test('_Throw.03'); }
    public function test_Throw04()  { $this->generic_test('_Throw.04'); }
}
?>