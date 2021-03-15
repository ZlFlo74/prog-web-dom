% PW-DOM  Compte rendu de TP

# Compte-rendu de TP

Sujet choisi : Utilisation de The Movie DataBase

## Participants 

* CHAPPAZ Florian
* KACHA Tom
* MEIGNEN Hugo

## Mise en jambes

1. La réponse donnée par l'API est au format JSON. L'extension de navigateur installée au préalable nous permet d'ailleurs de présenter cette réponse de manière lisible.<br>
Il s'agit du film Fight Club.<br>
Lorsqu'on ajoute le paramètre <code>language=fr</code>, plusieurs informations sont traduites en français, comme la description (overview) et le genre. On remarque également que le poster_path change. On peut supposer que c'est un nouveau chemin correspondant à l'affiche française du film.<br>

2. Lorsqu'on appelle l'API avec curl en ligne de commande, on obtient la même réponse que précédemment.<br>
Nous avons créé un petit programme en php <em>tmdb_api_cli.php</em>, qui utilise la fonction tmdbget du Helper pour obtenir la réponse de l'API. Nous décodons ensuite cette réponse à l'aide de json_decode pour pouvoir l'afficher de manière plus lisible. Une simple remarque sur le premier argument de tmdbget : ce n'est pas l'url en entier qui est attendu, mais le chemin relatif du film par rapport à l'API, donc ici : "movie/550". Le paramètre api_key est ajouté par la fonction, et les autres paramètres comme "language=fr" peuvent être donnés en second argument.<br>

3. Le fichier pour le client web de cette question est <em>detail_page.php</em>.<br>
La page se présente comme un simple formulaire demandant un entier : l'identifiant d'un film. A l'aide de la methode GET, cet identifiant est envoyé à la fonction tmdbget. Puis comme précédemment, on utilise json_decode pour pouvoir récupérer les différentes informations et afficher en HTML celles qui nous intéresse.<br>
On vérifiera cependant que l'ID donné par l'utilisateur correspond à un film. Si on donne à l'API un ID qui n'existe pas, la réponse donnée possède une propriété "success" égal à "false". Quand l'ID existe, cette propriété n'existe pas. On vérifie donc si la propriété "success" existe dans la réponse grâce à la fonction property_exists, et la cas échéant, on affichera un message d'échec.<br>

4. Cette fois-ci, on effectue trois appels à l'API : un pour la VO, un pour la VA et un pour la VF. On stocke alors les réponses dans 3 objets différents.<br>
Ensuite on construit notre tableau en accédant aux informations comme précédemment, parmi les trois objets que nous avons récolté.

5. On peut récupérer la réponse de l'API Configuration avec tmdbget.<br>
On peut ainsi récupérer les informations comme la base de l'URL et les tailles de posters disponibles (ici on prendra toujours la taille la plus petite, donc à l'indice 0 du tableau des tailles).<br>
On contruit alors trois URLs différents pour chaque version, qu'on utilisera dans les balises img sur la dernière ligne du tableau.<br>
Nous avons également ajouté à chaque balise img un attribut alt, différent en fonction de la version.<br>

6. Cette fois-ci, on change de fichier. On crée un nouveau fichier nommé <em>collection_distribution_search_and_query.php</em> (on l'utilisera aussi pour la question 7).<br>
Pour récupérer l'entrée de l'utilisateur, en l'occurence le nom d'une collection, on gère le formulaire exactement de la même manière que précédemment.<br>
Cette fois-ci, on utilise la fonction de recherche de l'API. L'argument que l'on va passer à tmdbget() sera donc 'search/collection, car c'est une collection que l'on veut chercher. Cependant, avec search, on doit ajouter le paramètre query, c'est-à-dire les termes que l'on veut rechercher. En l'occurence, l'entrée de l'utilisateur dans le formulaire.<br>
On peut alors récupérer la réponse de l'API, de la même manière que précédemment. Gérons tout d'abord le cas où la recherche n'a aboutit à aucun résultat : la réponse contient un champ 'total_results'. Si ce dernier est à 0, on affiche un message indiquant qu'aucun résultat n'a été trouvé.<br>
Dans les autres cas, la réponse nous apporte quelques informations par rapport à la collection, en particulier son ID. Mais celà ne nous suffit pas pour pouvoir afficher l'ensemble des films. Nous devons donc refaire appel à l'API, mais cette fois-ci en passant à tmdbget() l'argument 'collection/:id' où :id sera remplacé par l'ID de la collection (trouvé grâce à la recherche). Si par hasard la recherche retourne plusieurs collections, nous retournerons l'ensemble des films de toutes les collections (et nous appellerons donc l'API autant de fois que nécessaire).<br>
Pour chaque film, on récupère l'ID et la date de sortie.<br>

7. Au vu de la quantité d'acteurs pour un seul film et la redondance de certains au sein d'une collection, il semble pertinent de rassembler tous les acteurs d'une même collection dans un tableau (ce n'est visiblement pas possible d'obtenir les crédits d'une collection avec l'API). C'est ce que nous avons fait : on créé tout d'abord un tableau vide pour une collection. Pour chaque film de la collection, on récupère ses crédits. Mais ces crédits sont spécifiques au film. Or on souhaite également connaître le nombre de films dans lesquels l'acteur a joué. C'est possible grâce à l'API, en passant le chemin '/person/:personid/movie_credits', et on comptera alors le nombre de films où l'acteur a joué.<br>
Pour chaque acteur dans les crédits, on vérifie qu'il n'a pas déjà été ajouté au tableau, et dans ce cas, nous ajoutons les informations (tableau de taille 2 composé du nom du personnage joué et des informations récupéré par rapport à tous les films dans lequel l'acteur a joué) au tableau en leur donnant pour clé le nom de l'acteur.<br>
