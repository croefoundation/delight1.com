<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if(!$member[user_id])Error("로그인 후 이용해 주세요.");
?>


<!-- Top menu -->
<? include('../_header.php');
   include('../lib/lib.php'); //날짜변환외
?>


<?
$query_id= "select * from member where id='mem'";
mysql_query("set names ust8", $connect);
$result_id= mysql_query($query_id, $connect);
while($member = mysql_fetch_array($result_id)){

//회원중에 하위 회원들의 계약을 점검하여
          $query_t ="select * from contract where (id_manager='$member[name]' or id_incentive='$member[name]' or id_top='$member[name]' or name='$member[name]')";
     //     $query_t ="select * from contract where id='ctr'";

          $total_money_t=0; //약정금 총액
          $total_month=0;//월대차금액 합계
          $total_int=0; //이자총액
          $tnt=0; //계약건수
          $tnm=0;//월대차건수

          $result_t=mysql_query($query_t,$connect);
          while($data_t = mysql_fetch_array($result_t)){
               $total_money_t+=$data_t[money];

               if($data_t[type]=="월대차/만기지급"){
                    $total_month+=$data_t[money];
                    $tnm++;
               }
               $total_int+=$data_t[sum_cus];
               $tnt++;
          }

$total_money = $total_money_t-$total_month;

echo $member[name]."< >";
echo $total_money_t."< >";
echo $total_month."< >";
echo $total_money."< >";
echo $tnt."건< >";
echo $tnm."건(월대차)<br>";

$query="update member set
total_money='$total_money',

total_month='$total_month'
where user_id='$member[user_id]' ";
mysql_query("set names utf8", $connect);
mysql_query($query, $connect);

mysql_close;

}

echo "ok <br>";

?>
