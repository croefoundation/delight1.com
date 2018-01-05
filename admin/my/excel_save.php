<?
 header("Content-type:application/vnd.ms-excel");
 header("Content-Dispostion:attachment;filename=저장되는엑셀파일이름.xls");
 header("Content-Description:PHP4GenerateData");
 //저장하기 위해서 꼭 필요한 해더 값들이다
 //결국 위에 3줄로 엑셀이 정리되고 저장된다고 생각하면된다.
 //헤더 앞에는 어떠한 값도 공백도 출력해서는 안된다.
?>
 <meta charset="utf-8"/>
<?
 $sStartDate = $_GET['sStartDate'];
 $sEndDate = $_GET['sEndDate'];
 //이전 페이지에서 엑셀 저장버튼을 누르게되면
 //날라오는 날짜 범위를 저장하는 변수
 include "dbinfo.php";
 if($sEndDate && $sStartDate){
  $sStartDate1 = $sStartDate." 00:00:00";
  $sEndDate1 = $sEndDate." 23:59:59";
  $query = "SELECT * FROM a_stats where dtm >= '{$sStartDate1}' and dtm <= '{$sEndDate1}'";
  echo $query;
  $result = mysql_query($query);
 }else if(!$sEndDate && !$sStartDate){
  $query = "SELECT * FROM a_stats";
  echo $query;
  $result = mysql_query($query);
 }else{
  echo "값이 없습니다. 엑셀 저장 취소를 해주세요";
 }
 //아래쪽 html 코드는 엑셀 저장 기본 형식이고
 // border="1" cellspacing="0" cellpadding="0" 는 없어도 상관없다
 //table 기준으로 판단을해서
 //</tr>을만나면 줄뛰우고 </td>를 만나면 칸을 띄우며 순차적으로 저장
?>
<html>
 <body>
  <table border="1" cellspacing="0" cellpadding="0">
   <tr>
    <td>번호
    </td>
    <td>키워드
    </td>
   </tr>
   <?
    $i=1;
    while($data = mysql_fetch_array($result)){
     $a_re_key = $data[a_re_key];
   ?>
   <tr>
    <td><?=$i?>
    </td>
    <td><?=$a_re_key?>
    </td>
   </tr>
   <?
    $i++;
    }
   ?>
  </table>
 </body>
</html>
﻿
