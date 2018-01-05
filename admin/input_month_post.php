<? header("content-type:text/html; charset=UTF-8"); ob_start;

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자 메뉴입니다.");

$no = $_POST[no];
$id = $_POST[id];

$m1 = $_POST[m1];
$m2 = $_POST[m2];
$m3 = $_POST[m3];
$m4 = $_POST[m4];
$m5 = $_POST[m5];
$m6 = $_POST[m6];
$m7 = $_POST[m7];
$m8 = $_POST[m8];
$m9 = $_POST[m9];
$m10 = $_POST[m10];
$m11 = $_POST[m11];
$m12 = $_POST[m12];

$mm1 = $_POST[mm1];
$mm2 = $_POST[mm2];
$mm3 = $_POST[mm3];
$mm4 = $_POST[mm4];
$mm5 = $_POST[mm5];
$mm6 = $_POST[mm6];
$mm7 = $_POST[mm7];
$mm8 = $_POST[mm8];
$mm9 = $_POST[mm9];
$mm10 = $_POST[mm10];
$mm11 = $_POST[mm11];
$mm12 = $_POST[mm12];
$sum_mm =$_POST[sum_mm];

$query="update contract set
m1 = '$m1',
m2 = '$m2',
m3 = '$m3',
m4 = '$m4',
m5 = '$m5',
m6 = '$m6',
m7 = '$m7',
m8 = '$m8',
m9 = '$m9',
m10 = '$m10',
m11 = '$m11',
m12 = '$m12',

mm1 = '$mm1',
mm2 = '$mm2',
mm3 = '$mm3',
mm4 = '$mm4',
mm5 = '$mm5',
mm6 = '$mm6',
mm7 = '$mm7',
mm8 = '$mm8',
mm9 = '$mm9',
mm10 ='$mm10',
mm11 ='$mm11',
mm12 ='$mm12',
sum_mm ='$sum_mm'

where no='$no' and id='$id' ";
mysql_query("set names utf8", $connect);
mysql_query($query, $connect);

mysql_close;
?>

<script>
// window.alert('월대차 내역이 입력되었습니다.');
location.href='../admin/input_month_page.php?id=<?=$id?>&no=<?=$no?>';
</script>
