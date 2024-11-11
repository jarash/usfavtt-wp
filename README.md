# Thème XMAG pour le site USFAV Tennis de Table

## Installation

* Uploader le zip du Thème dans Wordpress (Apparences > Thèmes > Ajouter un Thème)

## Fonctionnalités du Thème

Il s'agit d'un thème enfant de [Xmag](https://wordpress.org/themes/xmag/) avec les ajouts suivants :

* Ajustement CSS pour le plugin _Simple Calendar_ via le shortcode `[css_calendar_list]`
* Ajout d'un Custom Field `color` pour les Post `calendar` afin de permettre de choisir une couleur pour les différents types de calendriers

## Plugin USFAV TT

Un plugin est livré avec le thème pour ajouter des fonctionnalités au Thème. 
Le plugin ACF est requis pour le bon fonctionnement du plugin.

### Fonctionnalités

* Menu de paramètre dans l'admin Wordpress
* Gestion des joueurs
    * Ajout d'un Post Type `joueur`
    * Ajout d'un Post Type `equipe`
    * Import des joueurs via l'API FFTT [package fftt-api](https://packagist.org/packages/jarash/fftt-api)
* Ajout d'un short_code `[ranking]` pour afficher la progression des joueurs durant la saison
* Prochainement : ajout d'un short_code `[get_match]` pour afficher le résultat d'un match entre 2 équipes

