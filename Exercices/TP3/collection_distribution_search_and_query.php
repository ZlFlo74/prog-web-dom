<?php 

include_once('tp3-helpers.php');

?>

<html>
  <head>
    <title>The Movie Database : Collection & Distribution by name</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
  </head>
  <body>
    <form method="get" action="collection_distribution_search_and_query.php">
        <label for="collection_name">Collection name : </label> <input type="text" id="collection_name" name="collection_name"/> <br />
        <input type="submit" />
    </form>

    <?php 
    
    if (isset($_GET['collection_name'])) {
        $url_component = "search/collection" ;
        $query_arg = $_GET['collection_name'];

        $api_response = tmdbget($url_component, array("query"=>$query_arg));
        $api_response_array = json_decode($api_response);

        if ($api_response_array->{'total_results'} == 0) {
            echo "<p>Désole, aucune collection trouvée</p>";
        }
        else {
            foreach($api_response_array->{'results'} as $collection) {
                echo "<h2>".$collection->{'name'}."</h2>";
                $url_component = "collection/".$collection->{'id'};
                $api_response_movies = tmdbget($url_component);
                $api_response_movies_array = json_decode($api_response_movies);
                echo "<ul>";

                $collection_credits = array(); // Stocke tous les acteurs de la collection : name => [Objet représentant l'acteur]

                foreach($api_response_movies_array->{'parts'} as $movie) {
                  echo "<li><h3>".$movie->{'title'}."</h3>";
                  echo "<p><b>ID : </b>".$movie->{'id'}."<br>";
                  echo "<b>Release date : </b>".$movie->{'release_date'}."</p>";

                  // Pour chaque film, on récupère ses crédits
                  $url_component = "movie/".$movie->{'id'}."/credits";
                  $api_response_credits = tmdbget($url_component);
                  $credits_array = json_decode($api_response_credits)->{'cast'};
                  
                  // On parcours chaque participant, en ne traitant que les acteurs (ceux ayant une propriété character)
                  foreach ($credits_array as $people) {
                    if (property_exists($people, "character")) {
                      $url_component = "person/".$people->{'id'}."/movie_credits";
                      $people_array = json_decode(tmdbget($url_component));
                      $people_name = $people->{'name'};
                      $character = $people->{'character'};
                      if (!array_key_exists($people_name, $collection_credits)) {
                        $collection_credits[$people_name] = array($character, $people_array);
                      }
                    }
                  }

                  echo "</li>";
                }

                echo "</ul>";

                echo "<h3>Collection credits</h3>";
                echo "<ul>";
                foreach ($collection_credits as $actor_name=>$infos_array) {
                  echo "<li><p>";
                  echo "<b>".$actor_name." : </b>Role=".$infos_array[0].", Number of movies=".count($infos_array[1]->{'cast'});
                  echo "</p></li>";
                }
                echo "</ul>";
            }
        }
    }

    ?>

  </body>
</html>