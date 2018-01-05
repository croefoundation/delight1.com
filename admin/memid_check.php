<? header("content-type:text/html; charset=UTF-8"); ob_start;?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <!--다국어 언어: UTF-8-->
<style type="text/css">
BODY,TD,SELECT,input,DIV,form,TEXTAREA,center,option,pre,
blockquote {font-size:9pt;font-family:굴림,돋움;color:#686668;line-height:130%}
</style>
<body>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트

  $user_id= $_GET[user_id];

   $query=" select * from member where user_id='$user_id'";
   $result= mysql_query($query, $connect);
   $data= mysql_fetch_array($result);
   if(!$data[user_id]){
	   echo "<strong>".$user_id."</strong>는 가능한 아이디 입니다.<br>";
	   ?>
<input type=button value='아이디 등록하기' onclick="window.close();">

   <?}else if($data[user_id]){
           echo "<strong>[".$user_id."]</strong>는 <b>중복된 아이디</b>이오니,<br> 확인후 다른 아이디를 입력하세요. <br><br>";
             ?>
        <input type=button value='닫기' onclick="window.close();">
        <input type=button value='회원등록으로 가기' onclick="window.close();">

	<?}?>


</body>
