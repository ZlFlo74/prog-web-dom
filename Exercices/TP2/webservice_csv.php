<?php

include_once('tp2-helpers.php');

$filename = "borneswifi_EPSG4326_20171004_utf8.csv";
$csv_fields_array = file($filename);

$bornes = array();

// On definit la position donnee dans l'URL
if (isset($_GET['top']) && isset($_GET['lon']) && isset($_GET['lat'])) {
    $usr_top = $_GET['top'];
    $usr_lon = $_GET['lon'];
    $usr_lat = $_GET['lat'];
    $usr_coord = array('lon' => $usr_lon, 'lat' => $usr_lat);
}
else {
    echo "Mauvais paramètres dans l'URL : top, lon et lat attendus\n";
    return;
}

// Initialisation de chaque borne avec un champ distance
foreach ($csv_fields_array as $line)
{
    if ($line != $csv_fields_array[0])
    {
        $line_array = str_getcsv($line);
        $borne_data = array(
            'name' => $line_array[0], 
            'adr' => $line_array[1], 
            'lon' => $line_array[2], 
            'lat' => $line_array[3],
            'distance' => distance($usr_coord, array('lon' => $line_array[2], 'lat' => $line_array[3]))
        );
        $bornes[] = $borne_data;
    }
}

// Tri des bornes en fonction de leur distance
$distances = array();
foreach ($bornes as $key => $row)
{
    $distances[$key] = $row['distance'];
}
array_multisort($distances, $bornes);

// Liste des top bornes les plus proches
$bornes_proches = array();
for ($i=0; $i<$usr_top; $i++) {
    $bornes_proches[] = $bornes[$i];
}

// Conversion en JSON et affichage des top bornes les plus proches
echo json_encode($bornes_proches, JSON_UNESCAPED_UNICODE);

?>