<?php
/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Mysqli------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connection = new mysqli($host, $username, $password, $db);

/////////////////////////////////////////////////////////////////////////////////
/*----------------------------------Log error----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('log_errors', 1);
ini_set('error_log','log.txt');

/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Classes-----------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
include "users_key.php";
include_once "../lib/requests.php";

function check_join($connection) {
//$dialogID = '2000000001';
$tokenAcc = 'a23cfa3460c6ea707a096c2967195683aa4415755a3157e65367a4a34f13e1225f85daa7adabfbc36b190';
$tokenGroup = '531869b815ab2be7dc35b23da36f471dea8bf840e884ffae95dafc0f7e16e51ac48ad458b00dbd39a8807';
/////////////////////////////////////////////////////////////////////////////////
/*-----------------------------------Main code---------------------------------*/
/////////////////////////////////////////////////////////////////////////////////

//--Делаем запрос--//
    $url = 'https://api.vk.com/method/messages.getHistory?';
    $params = array(
        'count' => '200',  
        'chat_id' => '1',   
        'access_token' => $tokenAcc,  
        'v' => '5.69',
    );
    $get_params = http_build_query($params);
    $result = GET($url.$get_params);
    $result = $result[response][items];
//-----------------//

//--Смотрим дату последнего оповещения о вступлении в диалог--//
$request = $connection->query("SELECT last_join_date FROM last_date");
$own = $request->fetch_row();
$lastJoinDate = $own[0];
//-----------------------------------------------------------//


//--Сравниваем дату в базе и дату последнего сообщения в диалоге--//
if($result[0][date]>$lastJoinDate){
//----------------------------------------------------------------//

    for($i=0;$i<=200;$i++){
//--Просматриваем кжое сообщение на наличие (вступлния/выхода из диалога)--//
        $user = $result[$i]['action_mid'];
        if($user==null)continue;
//-------------------------------------------------------------------------//
        $request = $connection->query("SELECT user_level FROM users WHERE user_id=$user");//меня смущает эта строка - ответ: ты просто находишь левел юзера и сравниваешь его с джойнед гроуп
     
        $own = $request->fetch_row();
        
        $user_level = $own[0];
        if($result[$i][date]>$lastJoinDate and $result[$i]['action'] == 'chat_invite_user' and ($user_level != 'joined_group')) {
           
            $request = $connection->query("UPDATE users SET user_level = 'joined_group' WHERE user_id = '$user'");
             
            $key = give_key($user,$connection);
           
         $respons = "Добро пожаловать в тестирование приложения BusGO.<br>В течение суток ваш Google Play аккаунт получит доступ к скачиванию Альфа-тестового приложения.<br>Ссылка на приложение https://play.google.com/apps/testing/com.tech.em.demoapp<br>Ваш личный ключ, активируйте его в приложении: $key";
            $request_params = array(
                'message' => $respons,
                'user_id' => $user,
                'access_token' => $tokenGroup,
                'v' => '5.8'
            );
            
            POST('https://api.vk.com/method/messages.send?',$request_params);

        $response = 
            "Сейчас на этапе Альфа теста нам нужно проверить нашу систему, она будет работать следующим образом.
            Когда кто-то из вас сел в транспорт, приложение проверяет где вы едете и какой транспорт проезжает по этой дороге, после вам прилетает уведомление, в котором нужно выбрать транспорт из предложенного списка, а также  оценить загруженность с вашей точки зрения данного транспорта по 5-ти смайликам. 
            Отчеты о работе приложения пишем в следующей форме: 
            1. Делаем обращение именно на мое имя используя @ и мой id. @ id81747790(Эмран) 
            2. Указывайте модель телефона и версию android.
            3. Опишите саму проблему, с которой вы столкнулись, также последовательные шаги, которые привели к этому, проверьте вашу ошибку несколько раз и уже после напишите об этом отчет. 
            3. Прикрепляйте скриншоты.";
             $params = array(
                    'user_id' => $user,    
                    'message' => $response,
                    'access_token' => $tokenGroup,
                    'v' => '5.8',
                );
            
                 POST('https://api.vk.com/method/messages.send?',$params);
                  
        
        
        }
        
        
    }
}

/////////////////////////////////////////////////////////////////////////////////
/*--------------------------------------End------------------------------------*/
/////////////////////////////////////////////////////////////////////////////////
$lastJoinDate = $result[0][date];
$request = $connection->query("UPDATE last_date SET last_join_date = '$lastJoinDate'");
}
check_join($connection);
?>









 