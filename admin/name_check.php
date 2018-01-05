<?ob_start();?>
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

  $name= $_GET[name];

   $query=" select * from member where name='$name'";
   $result= mysql_query($query, $connect);
   $data= mysql_fetch_array($result);
   if($data[name]){
	   echo "<strong>".$name."</strong>는 같은 이름이 있습니다.<br><br><b>아이디(생년월일):".$data[user_id]."</b>
        을 확인해보시고, 동명이인이면 홍길동A,홍길동B 등으로 등록하십시오.<br> ";
	   ?>
<input type=button value='고객명으로 등록하기' onclick="window.close();">

   <?}else if(!$data[name]){
           echo "<strong>[".$name."]</strong>님 회원등록이 가능합니다.<br> 환영합니다.<br><br>";
             ?>
        <input type=button value='닫기' onclick="window.close();">
        <input type=button value='회원등록으로 가기' onclick="window.close();">

	<?}?>


</body>
