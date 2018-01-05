
<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보
include('../lib/lib.php');


$query_id= "select * from contract where id='ctr'";
mysql_query("set names ust8", $connect);
$result_id= mysql_query($query_id, $connect);
while($dm = mysql_fetch_array($result_id)){
     //날짜 변환
       $date_tr=$dm[ctr_date]; //마감일 기준
       $date_y=substr($date_tr,0,4);
       $date_m=substr($date_tr,5,2);
       $date_d=substr($date_tr,8,2);
       $dt=computeMonth($date_y,$date_m,$date_d,0);   //날짜 말일

$money=$dm[money];
$rate_cus=$dm[rate_cus];
$rate_manager=$dm[rate_manager];
$rate_incentive=$dm[rate_incentive];
$rate_top=$dm[rate_top];

       $sum_cus= $money*$rate_cus/100;
       $sum_manager= ($money*$rate_manager)/100;
       $sum_incentive= ($money*$rate_incentive)/100;
       $sum_top=($money*$rate_top)/100;

//echo $dm[no]." ".$sum_cus."  ".$sum_manager."  ".$sum_incentive."  ".$sum_top."<br>" ;

$no=$dm[no];
$query="update contract set

sum_cus='$sum_cus',
sum_manager='$sum_manager',
sum_incentive='$sum_incentive',
sum_top='$sum_top'

where no='$no' ";
mysql_query("set names utf8", $connect);
mysql_query($query, $connect);

mysql_close;




// echo "ok";
}


?>
