<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extspl extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extspl01()  { $this->generic_test('Extensions_Extspl.01'); }
}
?>