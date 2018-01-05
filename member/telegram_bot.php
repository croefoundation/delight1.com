

<?php
$text=$_GET["text"];
$chat_id=$_GET["chat_id"];

// $chat_id='-1001121914592'; //임시그룹
$chat_id='-219554043'; //prgroup

//$url="https://api.telegram.org/bot269569500:AAH3zz8-qaUUCNTnfeKIqWcRMxL1BCIpl44/sendMessage?chat_id=-1001121914592&text=";
$url_1="https://api.telegram.org/bot269569500:AAH3zz8-qaUUCNTnfeKIqWcRMxL1BCIpl44/sendMessage?chat_id=";
$url_2=$url_1.$chat_id."&text=";
$go=$url_2.$text;

?>

<script>
location.href='<?=$go?>';
window.alert("성공리에 전송되었습니다.");
</script>



// <?php
// define('BOT_TOKEN', $_GET["bot_token"]);
// define('API_URL', ' https://api.telegram.org/bot'.BOT_TOKEN.'/');
//
// // grab the chatID (modify to Array)
// $chatID = ["chatId1","chatId2"];
//
// // compose reply
// $reply =  sendMessage();
//
// // send reply
// foreach($chatID as $no => $val) {
// $sendto = API_URL."sendmessage?chat_id=".$val."&text=".$reply;
// file_get_contents($sendto);
// }
//
// function sendMessage() {
// $message = urlencode($_GET["body"]);
// return $message;
// }
// ?>
