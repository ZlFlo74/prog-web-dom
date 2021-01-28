<?php
    require_once('libunicodega.php');

    for ($i=1; $i<$argc; $i++) {
        echo unidecode_char($argv[$i][0]);
    }
    echo "\n";
?>