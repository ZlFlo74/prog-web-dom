% PW-DOM  Compte rendu de TP

# Compte-rendu de TP

## Participants 

* CHAPPAZ Florian
* KACHA Tom
* MEIGNEN Hugo

## Points d'accès wifi

0. <strong>Visualisation texte</strong><br>
Pour le fichier CSV, nous avons récupéré la variante codée en UTF-8, car il y avait des problèmes d'affichage sur certains caractères.

1. <strong>Comptage</strong><br>
Nous avons utilisé la commande wc -l.<br>
Cela nous a permis de trouver qu'il y a 68 points d'accès :
- La commande renvoie 69 pour le CSV (1 point d'accès par ligne mais la première ligne est un modèle).
- Elle renvoie 71 pour le JSON, avec 1 accès par ligne, mais la première, deuxième et dernière ligne sont spécifiques au format JSON.

2. <strong>Points Multiples</strong><br>
Afin de déterminer les emplacements différents, leur nombre, et leur nombre de points d’accès, il faut d’abord sélectionner à chaque ligne seulement l’adresse en faisant la commande: <br>
<code>cut -d, -f2 borneswifi_EPSG4326_20171004_utf8.csv</code><br>
On coupe les lignes en délimitant un séparateur, ici la virgule, puis nous choisissons le deuxième champ. Ensuite, il faut supprimer les doublons en comptant combien il y en a pour chaque adresse. Pour cela, on utilise la commande uniq munie de l’option -c qui permet de conserver un exemplaire de chaque adresse, avec le nombre d’occurrence de celles-ci précisé à coté. Enfin, il faut trier cette liste par nombre d’occurrence croissant afin de distinguer plus simplement l’emplacement qui possède le plus de points d’accès (et combien), donc nous ajoutons la commande sort qui sert à trier  les éléments. La commande finale est donc celle-ci :<br>
<code>cut -d, -f2 borneswifi_EPSG4326_20171004_utf8.csv | uniq -c | sort</code><br>
On constate que le lieu qui possède le plus de bornes est la Bibliothèque Etudes (5 bornes).
Il nous reste juste à mettre en évidence le nombre total de points d’accès différents, il suffit donc cette fois de rajouter la commande wc -l qui compte le nombre de ligne :<br>
<code>cut -d, -f2 borneswifi_EPSG4326_20171004_utf8.csv | uniq -c | wc -l</code><br>
Soit 58 lieux différents. (59 - 1 car Antenne 1 est un exemple).

3. <strong>Comptage PHP</strong><br>
La routine est contenu dans le fichier comptage.php.<br>
Le nom du fichier a analyser doit être donné en argument.<br>
Après avoir utilisé file() pour transformer le fichier en tableau, on récupère le nombre de ligne à l'aide de count().
A l'aide de la fonction substr(), on vérifie si le fichier est au format CSV ou JSON pour retirer le nombre de lignes adéquat.<br>
Dans le cas d'un fichier d'un autre format, on retourne 0 bornes.

4. <strong>Structure de données</strong><br>
Le code se trouve dans le fichier structure_donnees_csv.php.<br>
Le nom du fichier CSV doit être donné en argument.<br>
Nous avons décidé d'utiliser la fonction file() pour transformer le fichier en tableau.<br>
Puis pour chaque élément du tableau (sauf le premier qui est un modèle), on utilise la fonction str_getcsv() pour obtenir un tableau des différentes propriétés.<br>
Il ne reste plus qu'a créé un tableau associatif pour cette ligne, et d'ajouter ce tableau au tableau général de toutes les bornes ($bornes). On peut vérifier le contenu du tableau final avec la fonction print_r.

5. <strong>Proximité</strong><br>
Code situé à la suite de la question 4 pour pouvoir réutiliser notre liste de bornes.
Parcours de la liste des bornes une première fois pour afficher toutes les bornes ainsi que leur distance par rapport à Grenette.<br>
De plus, lors de ce premier parcours, on ajoute à chaque tableau associatif une clé "distance" qui nous sera bien utile par la suite (notamment en question 6). <br>
Remarque : Pour modifier un élément de tableau dans une boucle foreach, il est important d'ajouter un '&' devant la variable utilisée pour le '... as ...'. Sinon, nous n'avons que des copies des éléments du tableau et la modification ne se fait pas.
Puis deuxième parcours pour :
    - N'afficher que les bornes à moins de 200 mètres et les compter.
    - Trouver la borne la plus proche pour l'afficher ensuite (à l'aide de deux variables : $distance_plus_proche et $borne_plus_proche).

6. <strong>Proximité top N</strong><br>
Toujours dans structure_donnees_csv.php.<br>
N est donné en deuxième argument. Si aucun argument n'est donné, la valeur par défaut est 5.<br>
Si l'argument N est supérieur au nombre de bornes, on considère qu'il est égal au nombre de bornes.<br>
On établi ensuite un tableau associatif $distances qui à chaque indice de $bornes associe la distance de la borne à cette indice.<br>
De cette façon, on peut utiliser la fonction array_multisort pour trier les bornes en fonction du tableau $distances.
On affiche alors les N premiers éléments à l'aide d'une boucle for.

7. <strong>Géocodage</strong><br>
Toujours dans structure_donnees_csv.php.<br>
La fonction parcourt la liste des bornes. Pour chaque borne : 
- On construit l'URL à fournir avec la latitude et la longitude.
- On donne cette URL à la fonction helpers smartcurl() qui nous renvoie un objet JSON.
- A l'aide de la fonction json_decode(), on récupère seulement la donnée qui nous intéresse. En l'occurence, l'adresse (sans la ville car ici on reste dans Grenoble).
- On ajoute à la borne la clé 'adresse' qui contiendra l'adresse que l'on vient de récupérer.
- Pour vérifier que les adresses ont bien été ajoutées, on peut décommenter la ligne avec le print_r qui affichera toute la structure de donnée.<br>
Petite fonctionnalité supplémentaire : Le temps d'attente pour récupérer toutes les adresses peut être long. Nous avons donc ajouté un compteur en temps réel (ce qui nous a permis de découvrir le métacharactère '\r').

8. <strong>Webservice</strong><br>
On crée cette fois-ci un nouveau fichier webservice_csv.php.<br>
C'est une sorte de condensé de tout ce que nous venons de voir.<br>
Quelques différences :
- Les arguments sont passés dans l'URL avec la méthode GET
- On reçoit une sortie en JSON : utilisation de la fonction json_encode() avce le flag JSON_UNESCAPED_UNICODE pour que les caractères spéciaux comme les accents s'affichent correctement.

9. <strong>Format JSON</strong><br>
On crée un nouveau fichier webservice_json.php.<br>
On reprend en grande partie le code de la question précédente. <br>
La seule différence réside dans le traitement du fichier : on récupère le contenu du fichier JSON avec file_get_contents, puis on transforme le JSON en objet PHP avec json_decode().

10. <strong>Client webservice</strong><br>
Création du fichier client_webservice.php.<br>
Simple formulaire comme nous en avons déjà vu en TP1.<br>
Arguments passé avec la méthode GET à webservice_json.php. Nous avons pour l'occasion modifié l'affichage de ce webservice : le résultat est affiché sous forme de tableau.


## Antennes GSM

1. <strong>CSV Antennes</strong><br>
A l'aide de la commande wc -l, on trouve que 100 antennes sont référencées.<br>
Le jeu de données contient des informations en plus par rapport à celui des bornes Wifi. Ces informations sont pour la plupart spécifiques au réseau cellulaire :
- Si l'antenne est microcellulaire ou non, c'est-à-dire qui ne couvre qu'une petite zone définie (comme un centre commercial).
- L'opérateur exploitant l'antenne.
- Les technologies que l'antenne est capable de diffuser : 2G/3G/4G.
- Un numéro cartoradio et un numéro de support : ces informations ne nous seront à priori pas utiles.
- Trois champs reprécisant si l'antenne possède ou non chacune des trois technologies 2G, 3G ou 4G.
Les autres champs peuvent être comparés à ceux des bornes Wifi : un ID d'antenne, un ID d'adresse, des coordonnées et une adresse.<br>
Dans le cadre d'une démarche OpenData, cela à d'abord un intérêt de transparence par rapport à la couverture de chaque opérateur. On peut alors imaginer pouvoir établir une carte de couverture cellulaire de Grenoble par opérateur et par technologie.

2. <strong>Statistiques opérateurs</strong><br>
Pour répondre à cette question, nous avons, à l'instar de la deuxième question de la partie précédente, utilisé trois commandes reliées avec des pipes :<br>
<code>cut -d ';' -f4 DSPE_ANT_GSM_EPSG4326.csv | sort | uniq -c</code><br>
<code>cut</code> permet de ne récupérer que les champs "Opérateur" de chaque ligne, <code>sort</code> les trie (et donc rassemble les opérateurs identiques), et <code>uniq</code> ne garde qu'un exemplaire de chaque (et l'option <code>-c</code> compte tous les doublons).<br>
On obtient la sortie :<br>
<code><pre>
  26 BYG
  18 FREE
   1 OPERATEUR
  26 ORA
  30 SFR
</pre></code><br>
On a donc 4 opérateurs et le nombre d'antennes est affiché à côté de leur nom.<br>
Pour répondre à cette question, nous avons fait le choix d'utiliser la ligne de commande, car nous avons déjà traité une question similaire à la partie précédente, et de plus, une fois que l'outil est maîtrisé, cette solution est la plus simple et rapide. Ecrire un script PHP aurait été beaucoup plus long et plus lourd.

3. <strong>KML Validation</strong>
Le format KML étant une application de XML, la vérification synthaxique est très simple à effectuer à l'aide de l'outil xmllint accompagné du flag --noout. Lorsque l'on exécute la commande sur notre jeu de données, aucun message d'erreur n'est affiché.<br>
Cependant, sur ce genre de jeu de données, il peut être intéressant de vérifier que le jeu de données est conforme à un schéma. Par exemple on aimerait vérifier que pour chaque antenne tous les champs sont indiqués. On pourrait faire cela en rédigeant un fichier XSD. Cela serait très complexe. Mais on constate dans le KML la présence de balises Schema qui indiquent justement un schéma pour chaque dossier d'antennes. Il doit donc certainement exister des outils de validation spécifiques au format KML qui se servent de cette balise Schema.