<? header("content-type:text/html; charset=UTF-8"); ob_start;

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보


include('../lib/lib.php'); //시간,날짜변환외


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


?>
