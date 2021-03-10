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