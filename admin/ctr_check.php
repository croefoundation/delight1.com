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

  $ctr_no= $_GET[ctr_no];

   $query=" select * from contract where ctr_no='$ctr_no'";
   $result= mysql_query($query, $connect);
   $data= mysql_fetch_array($result);
   if(!$data[ctr_no]){
	   echo "<strong>".$ctr_no."</strong>는 신규계약이 가능한 계약번호입니다.<br>";
	   ?>
<input type=button value='계약번호로 등록하기' onclick="window.close();">

   <?}else if($data[ctr_no]){
           echo "<strong>[".$ctr_no."]</strong>는 <b>중복된 계약번호</b>이오니,<br> 확인후 다른 계약번호를 입력하세요. <br><br>";
             ?>
        <input type=button value='닫기' onclick="window.close();">
        <input type=button value='계약서 등록으로 가기' onclick="window.close();">

	<?}?>


</body>
