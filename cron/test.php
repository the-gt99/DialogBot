<?php
/*

    $host = "localhost";
    $username = "u148682720_test";
    $password = "egtXOIZTaE1y";
    $db = "u148682720_test";
    $connecton = new mysqli($host, $username, $password, $db);
    
    $request = $connecton->query("SELECT last_join_date FROM users");
    $own = $request->fetch_row();
    print_r($own);
    $connecton->close();
    
    */
    include "../lib/requests.php";
    
    $apikey = "b20e914d048a15d49e141960106ab347";
    $apitoken = "3ceedce72ab61ac8c46f2e07aacb4a462fd2ed010b56f273c4341340093bfb4a";
    $card_name = "Report date:".gmdate('d.m.y H:i:s',time()+10800);
    $desc = '66';
    $list_id = "5a71fd8ac80efb5f055e8aa5";
    
    $url = 'https://api.trello.com/1/cards';
    $params = "key=$apikey&token=$apitoken&name=$card_name&idList=$list_id&labels=red&desc=$desc";
    
    POST($url,$params);

  
?>