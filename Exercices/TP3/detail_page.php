<?php 

include_once('tp3-helpers.php');

?>

<html>
  <head>
    <title>The Movie Database : Movie Detail Form</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
  </head>
  <body>
    <form method="get" action="detail_page.php">
        <label for="movie_id">Movie ID : </label> <input type="number" min="0" id="movie_id" name="movie_id"/> <br />
        <input type="submit" />
    </form>
    <?php 
    
    if (isset($_GET['movie_id'])) {
        $url_component = "movie/".$_GET['movie_id'];

        // Récupération des données JSON pour les trois versions du film
        $api_response_vo = tmdbget($url_component);
        $api_reponse_array_vo = json_decode($api_response_vo);
        $api_response_en = tmdbget($url_component, array("language"=>"en"));
        $api_reponse_array_en = json_decode($api_response_en);
        $api_response_fr = tmdbget($url_component, array("language"=>"fr"));
        $api_reponse_array_fr = json_decode($api_response_fr);

        if (property_exists($api_reponse_array_vo, "success")) {
            echo "<p>Echec : L'identifiant demandé n'existe pas</p>";
        }
        else {
            // Récupération des configurations pour les images
            $config = tmdbget("configuration");
            $config_array = json_decode($config);

            // Construction des différents liens pour les différentes versions de l'affiche 
            $base_url = $config_array->{'images'}->{'base_url'};
            $size = $config_array->{'images'}->{'poster_sizes'}[0]; // on prendra la plus petite taille
            $poster_url_vo = $base_url.$size.$api_reponse_array_vo->{'poster_path'};
            $poster_url_en = $base_url.$size.$api_reponse_array_en->{'poster_path'};
            $poster_url_fr = $base_url.$size.$api_reponse_array_fr->{'poster_path'};

            echo "
                <table>
                    <tr>
                        <th></th>
                        <th>Version Originale</th>
                        <th>Version Anglaise</th>
                        <th>Version Française</th>
                    </tr>
                    <tr>
                        <th>Titre</th>
                        <td>".$api_reponse_array_vo->{'title'}."</td>
                        <td>".$api_reponse_array_en->{'title'}."</td>
                        <td>".$api_reponse_array_fr->{'title'}."</td>
                    </tr>
                    <tr>
                        <th>Titre original</th>
                        <td>".$api_reponse_array_vo->{'original_title'}."</td>
                        <td>".$api_reponse_array_en->{'original_title'}."</td>
                        <td>".$api_reponse_array_fr->{'original_title'}."</td>
                    </tr>
                    <tr>
                        <th>Tagline</th>
                        <td>".$api_reponse_array_vo->{'tagline'}."</td>
                        <td>".$api_reponse_array_en->{'tagline'}."</td>
                        <td>".$api_reponse_array_fr->{'tagline'}."</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>".$api_reponse_array_vo->{'overview'}."</td>
                        <td>".$api_reponse_array_en->{'overview'}."</td>
                        <td>".$api_reponse_array_fr->{'overview'}."</td>
                    </tr>
                    <tr>
                        <th>Page</th>
                        <td><a href='".$api_reponse_array_vo->{'homepage'}."'>".$api_reponse_array_vo->{'homepage'}."</td>
                        <td><a href='".$api_reponse_array_en->{'homepage'}."'>".$api_reponse_array_en->{'homepage'}."</td>
                        <td><a href='".$api_reponse_array_fr->{'homepage'}."'>".$api_reponse_array_fr->{'homepage'}."</td>
                    </tr>
                    <tr>
                        <th>Affiche</th>
                        <td><img src='".$poster_url_vo."' alt='Affiche de ".$api_reponse_array_vo->{'title'}." (VO)' ></td>
                        <td><img src='".$poster_url_en."' alt='Affiche de ".$api_reponse_array_en->{'title'}." (VA)' ></td>
                        <td><img src='".$poster_url_fr."' alt='Affiche de ".$api_reponse_array_fr->{'title'}." (VF)' ></td>
                    </tr>
                </table>";
        }
    }

    
    ?>

  </body>
</html>