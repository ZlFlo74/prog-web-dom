<?php

include_once('tp2-helpers.php');

$filename = "DSPE_ANT_GSM_EPSG4326.json";
$bornes_obj = json_decode(file_get_contents($filename,TRUE))->{'features'};

$bornes = array();

// On definit la position donnee dans l'URL
if (isset($_GET['top']) && isset($_GET['op']) && isset($_GET['lon']) && isset($_GET['lat'])) {
    $usr_top = $_GET['top'];
    $usr_op = $_GET['op'];
    $usr_lon = $_GET['lon'];
    $usr_lat = $_GET['lat'];
    $usr_coord = array('lon' => $usr_lon, 'lat' => $usr_lat);
}
else {
    echo "Mauvais paramètres dans l'URL : top, op, lon et lat attendus\n";
    return;
}

// Initialisation de chaque borne avec un champ distance
foreach ($bornes_obj as $borne_obj)
{
    $borne_data = array(
        'id' => $borne_obj->{'properties'}->{'ANT_ID'}, 
        'adresse' => $borne_obj->{'properties'}->{'ANT_ADRES_LIBEL'},  // Cette fois-ci libellé d'adresse
        'op' => $borne_obj->{'properties'}->{'OPERATEUR'},
        'lon' => $borne_obj->{'geometry'}->{'coordinates'}[0], 
        'lat' => $borne_obj->{'geometry'}->{'coordinates'}[1],
        'distance' => distance($usr_coord, array('lon' => $borne_obj->{'geometry'}->{'coordinates'}[0], 'lat' => $borne_obj->{'geometry'}->{'coordinates'}[1]))
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

// Liste des top bornes les plus proches de l'opérateur
$bornes_proches = array();
$i = 0; // Compteur de liste
$j = 0; // Compteur du nombre d'antennes correspondantes à la demande 
while ($j < $usr_top && $i < count($bornes)) {
    $borne = $bornes[$i];
    if ($borne['op'] === $usr_op) {
        $bornes_proches[] = $borne;
        $j++;
    }
    $i++;
}

?>

<html>
  <head>
    <title>Webservice Antennes GSM proches Grenoble</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
  </head>
  <body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Adresse</th>
                <th>Distance</th>
                <th>Opérateur</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            foreach ($bornes_proches as $borne) {
                echo "<tr>";
                echo "<td>".$borne['id']."</td>";
                echo "<td>".$borne['adresse']."</td>";
                echo "<td>".$borne['distance']."</td>";
                echo "<td>".$borne['op']."</td>";
                echo "</tr>";
            }

            ?>
        </tbody>
    </table>
  </body>
</html>