<?php

$expected     = array('import_request_variables( )', 
                      'extract($_POST, EXTR_IF_EXISTS | EXTR_REFS)', 
                      'extract($_GET, EXTR_IF_EXISTS)',
                      'foreach($_REQUEST as $k5 => $v5) { /**/ } ',
                      'extract($_FILES)');

$expected_not = array('extract($_REQUEST, EXTR_SKIP | EXTR_REFS)');

?>