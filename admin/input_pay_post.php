<?php

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자용 페이지입니다.");

$pay_date_out=$_POST['pay_date_out'];
$pay_no=$_POST['pay_no'];
if(!$pay_no)Error("지급일과 지급여부를 먼저 선택하십시오");

for ($i=0; $i<sizeof($pay_no);  $i++) {
     $no=$pay_no[$i];
     $date_out=$pay_date_out[$i];

     $query="update payment set
          pay_date_out='$date_out',
          pay_state = '지급완료'

          where no='$no' ";
     mysql_query("set names utf8", $connect);
     mysql_query($query, $connect);
     mysql_close;

}


 ?>

<script>
window.alert('지급완료 되었습니다.');
location.href='./input_pay.php';
</script>
