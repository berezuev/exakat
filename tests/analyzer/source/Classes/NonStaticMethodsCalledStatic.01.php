<?php

class x {
    public function abc() { print __METHOD__."\n"; }
    static function sabc() { print __METHOD__."\n"; }
}


$a = new x();

$a->abc();   // OK
$a->sabc();  // Possible but weird

x::abc();    // Should not be possible but actually is
x::sabc();   // OK
y::sabc();   // OK, as unknown

?>