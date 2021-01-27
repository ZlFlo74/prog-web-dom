<?php

function cumul($somme, $taux, $duree) {
    return $_POST["somme"]*pow((1+$_POST["taux"]/100),$_POST["duree"]);
}

?>