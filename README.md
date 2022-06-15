Ce projet est dévéloppé pour l'apprentissage sur la création de plugin sur Wordpress.

Le fichier mère avec le premier plugin mv-slider est sur la racine du dossier et appelé mv-slider.php. Pour mon premier projet j'ai fait appel à la bibliothèque jQuery et à flexslider

#Prérequis

J'ai travaillé en local avec Wampserver: pour utiliser apache j'ai changé le port 80 par 8081 dans les fichiers de configuration. J'ai mi le dossier téléchargé wordpress dans le dossier WWW de wamp. Dans phpmyadmin j'ai créé une nouvelle base de donnée en utf-8_general_ci. Je suis parti dans les parametres utilisateurs -> changé de mot de passe et j'ai mi un mot de passe puis j'ai enregistré.

Dans mon dossier wordpress j'ai copier le fichier wp-config-sample.php que j'ai renommé wp-config.php pour remplacer les informations nécessaires pour communiquer avec ma base de donnée locale.

Sur wordpress admin j'ai besoin du plugin simply show hooks. Il permetra d'ajouter des filtres, de classer les éléments,...

<<VM Slider>>
Ce plugin traite particulièrement l'ajout de la fonction "slide"
On a besoin du d'installer d'abord le plugin "simply show hooks"

<VM Testimonials>
Ce plugin traite particulièrement l'ajout de la fonction sur l'ajout des "avis" sur les templates
On a besoin du d'installer d'abord le plugin "show current template"
