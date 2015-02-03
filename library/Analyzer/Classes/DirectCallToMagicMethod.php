<?php

namespace Analyzer\Classes;

use Analyzer;

class DirectCallToMagicMethod extends Analyzer\Analyzer {
    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini');
        $magicMethods = $magicMethods['magicMethod'];

        $this->atomIs('Functioncall')
             ->code($magicMethods)
             ->inIs('METHOD')
             ->raw('filter{ it.out("CLASS").filter{it.code.toLowerCase() in ["static", "parent", "self"]}.any() == false}');
        $this->prepareQuery();
    }
}

?>
