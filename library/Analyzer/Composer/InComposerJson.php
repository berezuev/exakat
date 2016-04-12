<?php

namespace Analyzer\Composer;

use Analyzer;

class InComposerJson extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $config = \Config::factory();
        
        $composerFile = $config->projects_root.'/projects/'.$config->project.'/code/composer.json';
        if (!file_exists($composerFile)) {
            return ;
        }

        $json = json_decode(file_get_contents($composerFile));
        // Not readable? Ignore.
        if ($json === null) {
            return ;
        }

        // Not present? Ignore.
        if (!isset($json->require)) {
            return ;
        }
        
        // Empty? Just nothing to do
        if (empty($json->require)) {
            return ;
        }
        
        $composer = new \Data\Composer();
        $c = array();
        foreach($json->require as $component => $version) {
            if (strpos($component, '/') === false) { continue; }
            $classes = $composer->getComposerClasses($component, $version);
            $c[] = $this->makeFullNSPath($classes);
        }
        $classes = array_merge(...$c);

        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs('EXTENDS')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Use')
             ->outIs('USE')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs(array('Staticconstant', 'Staticproperty' ,'Staticmethodcall'))
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($classes);
        $this->prepareQuery();

        // Interfaces
        $c = array();
        foreach($json->require as $component => $version) {
            if (strpos($component, '/') === false) { continue; }
            $classes = $composer->getComposerInterfaces($component, $version);
            $c[] = $this->makeFullNSPath($classes);
        }
        $classes = array_merge(...$c);

        $this->atomIs('Class')
             ->outIs('IMPLEMENTS')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Interface')
             ->outIs('IMPLEMENTS')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Use')
             ->outIs('USE')
             ->fullnspath($classes);
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

    }
}

?>
