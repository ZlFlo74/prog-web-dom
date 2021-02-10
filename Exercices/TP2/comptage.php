<?php

$filename = $argv[1];
$bornes = file($filename);
if (substr($filename, -4) == ".csv")
{
    $nbr_bornes = count($bornes) - 1;
}
else if (substr($filename, -5) == ".json")
{
    $nbr_bornes = count($bornes) - 3;
}
else
{
    $nbr_bornes = 0;
}

echo $nbr_bornes." bornes.\n";

?>