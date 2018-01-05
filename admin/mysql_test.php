<? header("content-type:text/html; charset=UTF-8"); ob_start;

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보


include('../lib/lib.php'); //시간,날짜변환외
include('./contract_type_rate.php'); //지급율 불러오기

$ctr_start="2017-08-10";
$ctr_end="2017-08-15";

$d1=new DateTime("$ctr_start");
$d2=new DateTime("$ctr_end");
$diff=date_diff($d1,$d2);
if($diff->days<185)Error("계약기간이 6개월이상은 되야 되지 않나여?  혹시 계약기간을 잘못입력한 것은 아닌지요?");
echo $diff->days;
echo "<br>";

$d1=new DateTime("2017-08-10");
$d2=new DateTime("2018-08-10");
$diff=date_diff($d1,$d2);
if($diff->days <390){echo "no";}


// $id= $_POST[id];
// $newtype=$_POST[newtype];
// $ctr_no= $_POST[ctr_no];
// $name= $_POST[name];
//
// $name="임영란";
// //이름이 입력되면 회원정보를 불러와서 아이디를 찾고, 팀장과 그 팀장의 소개자와 본부장을 찾아서 등록한다.
// $query_id= "select * from member where name='$name'";
// mysql_query("set names ust8", $connect);
// $result_id= mysql_query($query_id, $connect);
// $dm = mysql_fetch_array($result_id);
//
// $user_id=$dm[user_id]; //유저아이디
// $id_manager=$_POST[id_manager];
// // if ($id_manager!=$dm[id_manager])Error("등록된 담당팀장과 일치하지 않습니다.");
// $id_incentive=$dm[id_incentive];
// $id_top=$dm[id_top];
//
// echo $name."<br>";
// echo $dm[id_manager]."<br>";
// echo $dm[id_incentive]."<br>";
//
// $type= $_POST[type];
// $money= $_POST[money];
// $money_old= $_POST[money_old];
// $money_new= $_POST[money_new];
// $ctr_start=$_POST[ctr_start];
// $ctr_end=$_POST[ctr_end];
// $ctr_date=$_POST[ctr_date];
//
// $tm=$money_old+$money_new;
// if($money_old and $money!=$tm)Error("재계약인경우: 약정금액=[이월자금]+[신규자금] 합입니다. 확인해주세요");
//
// $rate_cus= $_POST[rate_cus];


?>
