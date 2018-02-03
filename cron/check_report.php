<?php
//////////////////////////Логирование ошибок////////////////////////////////////
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);

/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Classes-----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
include "../lib/requests.php";

/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Mysqli------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connection = new mysqli($host, $username, $password, $db);
$trello = new trello_api($apitoken,"https://api.trello.com",$apikey);

function generate_report($connection,$trello) {
$tokenAcc = 'a23cfa3460c6ea707a096c2967195683aa4415755a3157e65367a4a34f13e1225f85daa7adabfbc36b190';    
$count = 200;

$apikey = "b20e914d048a15d49e141960106ab347";
$apitoken = "3ceedce72ab61ac8c46f2e07aacb4a462fd2ed010b56f273c4341340093bfb4a";
$list_id = "5a71fd8ac80efb5f055e8aa5";

//////////////////////////Шлем запрос///////////////////////////////////////////
    $url = 'https://api.vk.com/method/messages.getHistory';
    $params= "count=200&peer_id=2000000001&access_token=$tokenAcc&v=5.71";
    $result = POST($url,$params);
    $result = $result[response][items];
    
//////////////////////////Сортируем/////////////////////////////////////////////
$request = $connection->query("SELECT last_message_date FROM last_date");
$own = $request->fetch_row();
$lastMessageDate = $own[0];
///Если дата последнего сообщения равна дате что есть у нас, значит новых сообщений не было и парсить смысла нет.
if($result[0][date]>$lastMessageDate){
    
    for($i=0;$i<=200;$i++){

        if($result[$i][date]>$lastMessageDate and strripos($result[$i][body],'шибка')>0) {
            
        echo($i);       
            $id = $result[$i][from_id];
            
             $request = $connection->query("SELECT user_rating FROM users WHERE user_id = $id");
             $own = $request->fetch_row();
             $rating = $own[0];
             
             $request = $connection->query("UPDATE users SET user_rating = '$rating+1' WHERE user_id = '$id'");
             
             //$reports->addParam('general', $result[$i][id], $id);
            
            $card_name = "Report date:".gmdate('d.m.y H:i:s',time()+10800);
            $desc = $result[$i][body];
            
            $url = 'https://api.trello.com/1/cards';
            $params = "key=$apikey&token=$apitoken&name=$card_name&idList=$list_id&labels=red&desc=$desc";
            POST($url,$params);
        }
    }
}
////////////////////////////////////////////////////////////////////////////////
$lastMessageDate = $result[0][date];
$request = $connection->query("UPDATE last_date SET last_message_date = '$lastMessageDate'");
}
echo(generate_report($connection,$trello));
?>
