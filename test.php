<?php
    include 'lib/trello.php';
    
    $apikey = "b20e914d048a15d49e141960106ab347";
    $apitoken = "3ceedce72ab61ac8c46f2e07aacb4a462fd2ed010b56f273c4341340093bfb4a";
    $card_name = "Report";
    $desc = "lol";
    $list_id = "5a71fd8ac80efb5f055e8aa5";
    
    $trello = new trello_api($apitoken,"https://api.trello.com",$apikey);
    $gg = $trello->request('POST', '/1/cards', array('name' => $card_name, 'idList' => $list_id, 'labels' => 'red', 'desc' => $desc));
     print_r($gg);
    unset($trello);
    
?>