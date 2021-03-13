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

                foreach($api_response_movies_array->{'parts'} as $movie) {
                  echo "<li><h3>".$movie->{'title'}."</h3>";
                  echo "<p><b>ID : </b>".$movie->{'id'}."<br>";
                  echo "<b>Date de sortie : </b>".$movie->{'release_date'}."</p></li>";
                }

                echo "</ul>";
            }
        }
    }

    ?>

  </body>
</html>