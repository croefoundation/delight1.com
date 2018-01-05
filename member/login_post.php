<? header("content-type:text/html; charset=UTF-8"); ob_start;

include("../lib/db_connect.php");
$connect= dbconn();

$user_id= $_POST[user_id];
$pws= $_POST[pw];


$pw=$pws;
// $pw=md5($pws);


// 나의 정보 데이터 가지고 오기!
$query=" select * from member where user_id='$user_id'";
mysql_query("set names utf8");
$result= mysql_query($query, $connect);
$member= mysql_fetch_array($result);


if(!$user_id){
    Error("아이디를 입력하세요.");
}elseif(!$member[user_id])Error("존재하지 않는 회원 아이디 입니다.");


if(!$pw){
     Error("비밀번호를 입력하세요.");
}elseif($member[pw]!=$pw)Error("비밀번호가 같지 않습니다.");

if($member[user_id] and $member[pw]==$pw){
$tmp=$member[user_id]."//".$member[pw];
setcookie("COOKIES",$tmp,time()+60*60*0.5,"/" );  //60초*60분*24시간--현재 1시간동안 유효
}
?>

<script>
// window. alert('로그인 되었습니다.');
location.href='../member/list.php';
</script>
