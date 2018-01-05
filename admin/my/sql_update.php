<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보
include('../lib/lib.php');




//계약테이블을 조회해서 우선 불러온다..-----------
$query_r= "select * from contract where id='ctr'";
mysql_query("set names ust8", $connect);
$result_r= mysql_query($query_r, $connect);
while($data = mysql_fetch_array($result_r)){ //while문 시작 계약서를 하나둘씩 불러와서..수당테이블에 저장한다.--------------------------------


$id= $data[id];
$newtype=$data[newtype];
$ctr_no= $data[ctr_no];
$name= $data[name];
$user_id=$data[user_id]; //유저아이디
$id_manager=$data[id_manager];
$id_incentive=$data[id_incentive];
$id_top=$data[id_top];

$type= $data[type];
$money= $data[money];
$money_old= $data[money_old];
$money_new= $data[money_new];
$ctr_start=$data[ctr_start];
$ctr_end=$data[ctr_end];
$ctr_date=$data[ctr_date];

$rate_cus= $data[rate_cus];


// 수당,수수료 계산해서..
$sum_cus= $money*$rate_cus/100;
$sum_manager= $money*$rate_manager/100;
$sum_incentive= $money*$rate_incentive/100;
$sum_top=$money*$rate_top/100;


// 계약서 테이블에 업데이트

$query="update contract set
sum_cus='$sum_cus',
sum_manager='$sum_manager',
sum_incentive='$sum_incentive',
sum_top='$sum_top'

where ctr_no='$ctr_no' ";
mysql_query("set names utf8", $connect);
mysql_query($query, $connect);
mysql_close;

}//while contract table 업데이트 종료

echo "ok";
?>
