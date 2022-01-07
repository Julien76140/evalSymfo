# evalSymfo

INSTALLATION SYMFONY 

1- Cloner le git
2- Allez dans le terminal est lancez la commande "composer install".
3- Allez dans le .env est modifiez si besoin la ligne "DATABASEURL".
4- Dans le terminal ensuite créé votre BDD "php bin/console doctrine:database:create".
5- Faites les migrations avec ces deux commandes "php bin/console make:migration" et "php bin/console doctrine:migrations:migrate"
 - Si ces deux commandes ne fonctionne pas utilisé cette dernière "php bin/console d:s:u --force".
6- Enfin activez votre server symfony via cette commande "symfony server:start".


ROUTES DISPONIBLE

- "/" Page d'accueil contenant une vue sur les films disponible dans la base de donnée du plus récent au plus ancien ajouté.
- "/getall" Page retournant un JSON de tout les films disponible dans la base de données.
- "/get/{id_film}" Page retournant un JSON du film sélectionné par son id, exemple si dans votre base de donnée il y a dans votre table film, un film qui contient l'id "1" alors 
  -pour avoir son JSON il suffit d'aller sur la route "/get/1" etc.
- "/create" Cette route permet d'ajouter un film à votre BDD. Utilisez par exemple POSTMAN est créez une requête http avec la method "POST" puis dans le "BODY" 
  -allez sur la case "RAW" puis mettez l'affichage en mode JSON.
  -Exemple de JSON d'insertion: {"nom":"Avenger","synopsis":"Quand un ennemi inattendu fait surface pour menacer la sécurité et l'équilibre mondial, Nick Fury,
  directeur de l'agence internationale pour le maintien de la paix, connue sous le nom du S.H.I.E.L.D., doit former une équipe pour éviter une catastrophe mondiale imminente.
  Un effort de recrutement à l'échelle mondiale est mis en place, pour finalement réunir l'équipe de super héros de rêve, dont Iron Man, l'incroyable Hulk, Thor, Captain America,
  Hawkeye et Black Widow.","type":"Action/Aventure","url_image":"https://fr.web.img2.acsta.net/pictures/19/04/04/09/04/0472053.jpg"}
  -Les clés "nom","synopsis","type","url_image" sont les seuls clés disponible et obligatoire, vous obtiendrez un message d'erreur si vous les modifiez.

  
  
