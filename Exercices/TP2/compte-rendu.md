% PW-DOM  Compte rendu de TP

# Compte-rendu de TP

## Participants 

* CHAPPAZ Florian
* KACHA Tom
* MEIGNEN Hugo

## Points d'accès wifi

0. Visualisation texte :
Pour le fichier CSV, nous avons récupéré la variante codée en UTF-8, car il y avait des problèmes d'affichage sur certains caractères.

1. Comptage :
Nous avons utilisé la commande wc -l.
Cela nous a permis de trouver qu'il y a 68 points d'accès :
- La commande renvoie 69 pour le CSV (1 point d'accès par ligne mais la première ligne est un modèle).
- Elle renvoie 71 pour le JSON, avec 1 accès par ligne, mais la première, deuxième et dernière ligne sont spécifiques au format JSON.

2. Points Multiples :
Afin de déterminer les emplacements différents, leur nombre, et leur nombre de points d’accès, il faut d’abord sélectionner à chaque ligne seulement l’adresse en faisant la commande: 
cut -d, -f2 borneswifi_EPSG4326_20171004_utf8.csv.
On coupe les lignes en délimitant un séparateur, ici la virgule, puis nous choisissons le deuxième champ. Ensuite, il faut supprimer les doublons en comptant combien il y en a pour chaque adresse. Pour cela, on utilise la commande uniq munie de l’option -c qui permet de conserver un exemplaire de chaque adresse, avec le nombre d’occurrence de celles-ci précisé à coté. Enfin, il faut trier cette liste par nombre d’occurrence croissant afin de distinguer plus simplement l’emplacement qui possède le plus de points d’accès (et combien), donc nous ajoutons la commande sort qui sert à trier  les éléments. La commande finale est donc celle-ci :

cut -d, -f2 borneswifi_EPSG4326_20171004_utf8.csv | uniq -c | sort
On constate que le lieu qui possède le plus de bornes est la Bibliothèque Etudes (5 bornes).

Il nous reste juste à mettre en évidence le nombre total de points d’accès différents, il suffit donc cette fois de rajouter la commande wc -l qui compte le nombre de ligne :

cut -d, -f2 borneswifi_EPSG4326_20171004_utf8.csv | uniq -c | wc -l
Soit 58 lieux différents. (59 - 1 car Antenne 1 est un exemple).

3. Comptage PHP :
La routine est contenu dans le fichier comptage.php.
Le nom du fichier a analyser doit être donné en argument.
Après avoir utilisé file() pour transformer le fichier en tableau, on récupère le nombre de ligne à l'aide de count().
A l'aide de la fonction substr(), on vérifie si le fichier est au format CSV ou JSON pour retirer le nombre de lignes adéquat.
Dans le cas d'un fichier d'un autre format, on retourne 0 bornes.

4. Structure de données :
Le code se trouve dans le fichier structure_donnees_csv.php.
Le nom du fichier CSV doit être donné en argument.
Nous avons décidé d'utiliser la fonction file() pour transformer le fichier en tableau.
Puis pour chaque élément du tableau (sauf le premier qui est un modèle), on utilise la fonction str_getcsv() pour obtenir un tableau des différentes propriétés.
Il ne reste plus qu'a créé un tableau associatif pour cette ligne, et d'ajouter ce tableau au tableau général de toutes les bornes ($bornes). On peut vérifier le contenu du tableau final avec la fonction print_r.

5. Proximité
Code situé à la suite de la question 4 pour pouvoir réutiliser notre liste de bornes.
Parcours de la liste des bornes une première fois pour afficher toutes les bornes ainsi que leur distance par rapport à Grenette.
De plus, lors de ce premier parcours, on ajoute à chaque tableau associatif une clé "distance" qui nous sera bien utile par la suite (notamment en question 6). 
Remarque : Pour modifier un élément de tableau dans une boucle foreach, il est important d'ajouter un '&' devant la variable utilisée pour le '... as ...'. Sinon, nous n'avons que des copies des éléments du tableau et la modification ne se fait pas.
Puis deuxième parcours pour :
    - N'afficher que les bornes à moins de 200 mètres et les compter.
    - Trouver la borne la plus proche pour l'afficher ensuite (à l'aide de deux variables : $distance_plus_proche et $borne_plus_proche).

6. Proximité top N
Toujours dans structure_donnees_csv.php.
N est donné en deuxième argument. Si aucun argument n'est donné, la valeur par défaut est 5.
Si l'argument N est supérieur au nombre de bornes, on considère qu'il est égal au nombre de bornes.
On établi ensuite un tableau associatif $distances qui à chaque indice de $bornes associe la distance de la borne à cette indice.
De cette façon, on peut utiliser la fonction array_multisort pour trier les bornes en fonction du tableau $distances.
On affiche alors les N premiers éléments à l'aide d'une boucle for.

7. Géocodage
Toujours dans structure_donnees_csv.php.
La fonction parcourt la liste des bornes. Pour chaque borne : 
- On construit l'URL à fournir avec la latitude et la longitude.
- On donne cette URL à la fonction helpers smartcurl() qui nous renvoie un objet JSON.
- A l'aide de la fonction json_decode(), on récupère seulement la donnée qui nous intéresse. En l'occurence, l'adresse (sans la ville car ici on reste dans Grenoble).
- On ajoute à la borne la clé 'adresse' qui contiendra l'adresse que l'on vient de récupérer.
- Pour vérifier que les adresses ont bien été ajoutées, on peut décommenter la ligne avec le print_r qui affichera toute la structure de donnée.
Petite fonctionnalité supplémentaire : Le temps d'attente pour récupérer toutes les adresses peut être long. Nous avons donc ajouté un compteur en temps réel (ce qui nous a permis de découvrir le métacharactère '\r').

8. Webservice
On crée cette fois-ci un nouveau fichier webservice_csv.php.
C'est une sorte de condensé de tout ce que nous venons de voir.
Quelques différences :
- Les arguments sont passés dans l'URL avec la méthode GET
- On reçoit une sortie en JSON : utilisation de la fonction json_encode() avce le flag JSON_UNESCAPED_UNICODE pour que les caractères spéciaux comme les accents s'affichent correctement.