<?php

include('config/config.php');

/**
 * 1. Récupérer le code postal dans la chaine de requête
 * 2. Connection au serveur 
 * 3. Préparation de ma requête SQL 
 * 4. Execution de la requête SQL (avec bindage du code postal reçu dans la requête)
 * 5. Récupération du Jeu d'enregistrement
 * 6. Affichage du jeu d'enregistrement (var_dump);
 */


/** HOlala on veut tester d'abord
 * 
 * 1. Connexion
 * 2. Requête simple avec un code postal en dur
 * 3. Execute et on affiche 
 */
$dbh = new PDO(DB_SGBD.':host='.DB_SGBD_URL.';dbname='.DB_DATABASE.';charset='.DB_CHARSET, DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    

if(array_key_exists('cp',$_GET))
    {
        $codepostal = $_GET['cp']; //On récupère une paramètre dynamique dans l'URL (queryString)
        
        //on prépare la requête. On remplace le paramètre dynamique par un nom de paramètre dans le but de préparer la requête (:credit)
        $sth = $dbh->prepare('SELECT `ville_nom`,`ville_code_postal`
                              FROM `villes` 
                              WHERE `ville_code_postal` LIKE :code_postal');

        //on bind (lier) le paramètre dynamique transmis dans l'URL avec le paramètre de la requête SQL en précisant le type de donnée attendue 
        //$sth->bindValue(':credit', $c, PDO::PARAM_INT);

        //On execute la requête
        $sth->bindValue(':code_postal', $codepostal.'%' , PDO::PARAM_STR);
        $sth->execute();

        //EXEMPLE A NE PAS FAIRE (DANGER SECURITE) -> REQUETE DIRECTE !! $sth = $dbh->query("SELECT * FROM cm_customers WHERE creditLimit > $credit);

        /* 3. Récupère les résultats de la requête - Le jeu d'enregistrement !
            Soit ligne par ligne (1 tableau pour 1 ligne en PHP), soit tout le jeu d'enregistrement (1 tableau contenant toutes les lignes)
        */

        // fetch nous permet de récupérer une ligne - fetchAll de récupérer toutes les lignes !
        $codep = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        //var_dump($codep);
    }   
        
    header('Content-Type: application/json');
      
    echo json_encode ( $codep );    

