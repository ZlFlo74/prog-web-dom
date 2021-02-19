<?php

include_once('tp2-helpers.php');

$filename = "borneswifi_EPSG4326.json";
$bornes_obj = json_decode(file_get_contents($filename,TRUE))->{'features'};

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
foreach ($bornes_obj as $borne_obj)
{
    $borne_data = array(
        'name' => $borne_obj->{'properties'}->{'AP_ANTENNE1'}, 
        'adr' => $borne_obj->{'properties'}->{'Antenne 1'}, 
        'lon' => $borne_obj->{'properties'}->{'longitude'}, 
        'lat' => $borne_obj->{'properties'}->{'latitude'},
        'distance' => distance($usr_coord, array('lon' => $borne_obj->{'properties'}->{'longitude'}, 'lat' => $borne_obj->{'properties'}->{'latitude'}))
    );
    $bornes[] = $borne_data;
}

// Tri des bornes en fonction de leur distance
$distances = array();
foreach ($bornes as $key => $row)
{
    $distances[$key] = $row['distance'];
}
array_multisort($distances, $bornes);

// Liste des top bornes les plus proches et ajout du champ adresse pour celles-ci
$bornes_proches = array();
for ($i=0; $i<$usr_top; $i++) {
    $borne = &$bornes[$i];

    $api_url = "https://api-adresse.data.gouv.fr/reverse/?lon=".$borne['lon']."&lat=".$borne['lat'];
    $adresse_json = smartcurl($api_url,0);
    $adresse = json_decode($adresse_json)->{'features'}[0]->{'properties'}->{'name'};
    $borne['adresse'] = $adresse;

    $bornes_proches[] = $bornes[$i];
}

// Conversion en JSON et affichage des top bornes les plus proches
// echo json_encode($bornes_proches, JSON_UNESCAPED_UNICODE); // Décommentez pour question 9

// Affichage demandé en question 10
?>

<html>
  <head>
    <title>Webservice bornes proches Grenoble</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
  </head>
  <body>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Lieu</th>
                <th>Adresse</th>
                <th>Distance</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            foreach ($bornes_proches as $borne) {
                echo "<tr>";
                echo "<td>".$borne['name']."</td>";
                echo "<td>".$borne['adr']."</td>";
                echo "<td>".$borne['adresse']."</td>";
                echo "<td>".$borne['distance']."</td>";
                echo "</tr>";
            }

            ?>
        </tbody>
    </table>
  </body>
</html>