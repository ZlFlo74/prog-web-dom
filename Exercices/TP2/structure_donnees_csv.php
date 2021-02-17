<?php

include_once('tp2-helpers.php');

// 4. Structure de données

$filename = $argv[1];
$csv_fields_array = file($filename);

$bornes = array();

foreach ($csv_fields_array as $line)
{
    if ($line != $csv_fields_array[0])
    {
        $line_array = str_getcsv($line);
        $borne_data = array('name' => $line_array[0], 'adr' => $line_array[1], 'lon' => $line_array[2], 'lat' => $line_array[3]);
        $bornes[] = $borne_data;
    }
}

// 5. Proximité

$grenet_coord = array('lon' => 5.72752, 'lat' => 45.19102);

echo "Affichage de tous les points d'accès et de leur distance par rapport à Grenet :\n";
foreach ($bornes as &$borne)
{
    $distance_borne = distance($grenet_coord, $borne);
    $borne['distance'] = $distance_borne;
    echo $borne['name']." : ".$distance_borne."m\n";
}
unset($borne);
echo "\n";

$nbr_bornes_200 = 0;
$distance_plus_proche = 200;
$borne_plus_proche = NULL;
echo "Affichage de tous les points d'accès à moins de 200 mètres :\n";
foreach ($bornes as $borne)
{
    if ($borne['distance'] <= 200)
    {
        echo $borne['name']." : ".$borne['distance']." m\n";
        $nbr_bornes_200++;

        if ($borne['distance'] < $distance_plus_proche)
        {
            $distance_plus_proche = $borne['distance'];
            $borne_plus_proche = $borne;
        }
    }
}
echo "\n(".$nbr_bornes_200." bornes à moins de 200 m)\n";
echo "Borne la plus proche : ".$borne_plus_proche['name'].", ".$distance_plus_proche."m\n\n";

// 6. Proximité Top N

if (isset($argv[2]))
{
    $n = $argv[2];
    if ($n > count($bornes))
        $n = count($bornes);
}
else
    $n = 5;

$distances = array();
foreach ($bornes as $key => $row)
{
    $distances[$key] = $row['distance'];
}
array_multisort($distances, $bornes);

echo "Affichage des N=".$n." bornes les plus proches :\n";
for ($i=0; $i<$n; $i++)
{
    $borne = $bornes[$i];
    echo $borne['name']." : ".$borne['distance']."m\n";
}
echo "\n";

// 7. Géocodage

$adress_counter = 0;
$total = count($bornes);
echo "Téléchargement des adresses dans la structure de données... ".$adress_counter."/".$total."\r";

foreach ($bornes as &$borne)
{
    $api_url = "https://api-adresse.data.gouv.fr/reverse/?lon=".$borne['lon']."&lat=".$borne['lat'];
    $adresse_json = smartcurl($api_url,0);
    $adresse = json_decode($adresse_json)->{'features'}[0]->{'properties'}->{'name'};
    
    $borne['adresse'] = $adresse;

    $adress_counter++;
    echo "Téléchargement des adresses dans la structure de données... ".$adress_counter."/".$total."\r";
}

echo "\nAdresses ajoutées à la liste des points d'accès\n";

// print_r($bornes); // Décommentez cette ligne pour vérifier que les adresses ont bien été ajoutées

?>