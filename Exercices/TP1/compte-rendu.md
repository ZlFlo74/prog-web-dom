% PW-DOM  Compte rendu de TP

# Compte-rendu de TP

## Participants 

* CHAPPAZ Florian
* KACHA Tom
* MEIGNEN Hugo

## EXO 1 : Diagnostic

Le diagnostic indique que nos installations se sont bien déroulés.
Les erreurs introduits sont bien détectés (Navigateur et CLI).

## EXO 2 : Calcul d'intérêts

On récupère les données dans un formulaire, puis le résultat s'affiche sur une autre page : resultat.php.
On remarque avec la méthode GET que les données peuvent directement modifées dans le lien.
Ce n'est pas le cas avec la méthode POST qui masque les données envoyées (pratique et nécessaire pour l'envoi d'informations confidentielles).
Avec la méthode POST, on reçoit aussi une demande de confirmation avant de recharger la page php.

clicalcul.php joue le même rôle, mais en version CLI, où les données sont données en argument par l'utilisateur.

## EXO 3 : Un peu de style en CSS

Utilisation des propriétés border, text-decoration, color et font-weight.

## EXO 4 : Table de multiplication

La table de multiplication est plus lisible grâce aux propriétés CSS border, border-collapse et padding.
La demande du nombre de colonnes, de lignes et de la ligne surlignée se fait directement dans le lien (donc on 
utilise forcément la méthode GET). Cela reste facultatif, par défaut, la table est de 10x10 et il n'y a pas de ligne surlignée.

## EXO 5 : Unicode 

L'importation des bibliothèques Unicode données dans le dossier Modèles nous a facilité la tâche.
La version CLI fonctionne bien grâce à la méthode unicode_char.

Pour la version navigateur, on utilise un formulaire html qui envoie les résultats sur la page unicode-initials.php.
Rencontrant des difficultés, nous avons abandonné l'implémentation des lignes correspondantes.
La difficulté venait probablement de la commande unicode qui réclamait un fichier manquant mais qui marchait pourtant.
PHP ne reconnaissait alors surement pas la sortie donnée par la fonction exec()

## EXO 6 : Agenda WEB

Cette fois ci, les formulaires sont sur la même page que la page de résultat. Nous avons réussi à bien styliser le calendrier
avec le CSS, et le positionnement des formulaires (utilisation de flex).
On arrive à récupérer les nombre de jours et les jours correspondant à chaque mois à l'aide de diverses boucles en donnant un deuxième arguement à la fonction date(), qui ne prendra alors plus la date du jour, mais la date donnée en deuxième arguement (en nous aidant également de la fonction strtodate()).
Pour sélectionner le mois, nous n'avons pas utilisé d'input de type date. En effet, ce type n'est pas compatible avec certains navigateurs modernes (notamment Safari). Nous avons préféré donné une énumération avec une section select, qui se présente comme un menu déroulant.

L'ajout d'un évènement se fait avec la deuxième partie du formulaire. L'évenement surligne alors la case de la date donné, et l'évenement s'affiche en survolant la case (on utilise un attribut title).

Nous n'avons pas poursuivi l'exercice en utilisant les sessions. Nous avons préféré nous concentrer sur le reste du TP qui semblait plus simple et plus important.

