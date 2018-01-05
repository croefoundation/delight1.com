<? header("content-type:text/html; charset=UTF-8"); ob_start;

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자용 페이지입니다.");


$id= $_POST[id];
$name= $_POST[name];
$user_id=$_POST[user_id];



//회원아이디를 입력하면 회원정보를 불러와서 기 등록자인지 체크하고, 아이디가 같은지 체크한다.
$query_id= "select * from member where user_id='$user_id'";
mysql_query("set names ust8", $connect);
$result_id= mysql_query($query_id, $connect);
$dm = mysql_fetch_array($result_id);
if ($user_id==$dm[user_id])Error("이미 등록된 회원입니다. \n 회원등록 필요없이 바로 계약 등록을 할수 있습니다.");



$pw= $_POST[pw];
$pw1= $_POST[pw1];

if($pw!=$pw1)Error("두번 입력한 비밀번호가 서로 일치하지 않습니다.");

$birth= $_POST[birth];
$jumin= $_POST[jumin];
$sex= $_POST[sex];
$email= $_POST[email];
$tel=$_POST[tel];
$addr_1= $_POST[addr_1];
$addr_2= $_POST[addr_2];
$account= $_POST[account];
$level= $_POST[level];
$id_manager=$_POST[id_manager];

if($level=="B" and ($id_manager!=$name))Error("팀장은 담당팀장이 자신입니다.");

//회원정보를 불러와서 팀장을 찾고 그 팀장의 소개자와 본부장을 찾아서 등록한다.
$query_m= "select * from member where name='$id_manager'";
mysql_query("set names ust8", $connect);
$result_m= mysql_query($query_m, $connect);
$dm = mysql_fetch_array($result_m);


if(!$dm[name])Error("담당팀장이 미등록입니다. \n 담당 팀장을 먼저 등록후에 회원등록을 하십시오!");
//팀장은 자신을  자신의 팀장으로 하니까..


$id_incentive=$dm[id_incentive];
$id_top=$dm[id_top];




//DB입력
$query1="INSERT INTO member (id,name,user_id,pw,birth, jumin, sex, email, tel, addr_1, addr_2, account, level,id_manager,id_incentive,id_top)
                   VALUES ('$id','$name','$user_id','$pw','$birth', '$jumin', '$sex', '$email', '$tel', '$addr_1', '$addr_2', '$account', '$level','$id_manager','$id_incentive','$id_top')";
mysql_query("set names utf8", $connect);
mysql_query($query1, $connect);
mysql_close; //끝내기.



?>

<script>
window.alert('회원정보가 등록되었습니다.');
location.href='../member/list_member.php';
</script>

<?
echo $name."<br>";
echo $id_manager."<br>";
echo $id_incentive."<br>";
echo $id_top."<br>";
?>
