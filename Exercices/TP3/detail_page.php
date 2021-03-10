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
        $api_response = tmdbget($url_component, array("language=fr"));
        $api_reponse_array = json_decode($api_response);

        print_r($api_reponse_array);

        if (property_exists($api_reponse_array, "success")) {
            echo "<p>Echec : L'identifiant demandé n'existe pas</p>";
        }
        else {
            echo "<p><b>Titre : </b>".$api_reponse_array->{'title'}."</p>";
            echo "<p><b>Titre original : </b>".$api_reponse_array->{'original_title'}."</p>";
            echo "<p><b>Tagline : </b>".$api_reponse_array->{'tagline'}."</p>";
            echo "<p><b>Description : </b>".$api_reponse_array->{'overview'}."</p>";
            echo "<p><b>Page : </b><a href='".$api_reponse_array->{'homepage'}."'>".$api_reponse_array->{'homepage'}."</a></p>";
        }
    }
    
    ?>

  </body>
</html>