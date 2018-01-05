<? header("content-type:text/html; charset=UTF-8"); ob_start;

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if(!$member[user_id])Error("로그인 후 이용해 주세요.");


$id= $_POST[id];
$idx= $_POST[idx]; //다른곳에서 회원수정
$no=$_POST[no];
$birth= $_POST[birth];
$sex= $_POST[sex];
$email= $_POST[email];
$tel=$_POST[tel];
$addr_1= $_POST[addr_1];
$addr_2= $_POST[addr_2];
$account= $_POST[account];
$pw=$_POST[pw];


if(!$pw){
     Error("비밀번호를 입력하세요.");
}elseif($member[pw]!=$pw)Error("비밀번호가 같지 않습니다.");


$query="update member set
          birth='$birth',
          sex='$sex',
          email='$email',
          tel='$tel',
          addr_1='$addr_1',
          addr_2='$addr_2',
          account='$account'

		where id='$id' and no='$no' ";
mysql_query("set names utf8", $connect);
mysql_query($query, $connect);

mysql_close;
?>

<?
if(!$idx){
echo "
<script>
window.alert('개인정보가 수정되었습니다.');
location.href='./myinfo.php?id=<?=$id?>&no=<?=$no?>';
</script>
";
}else{
     echo "
     <script>
     window.alert('회원정보가 수정되었습니다.');
     location.href='./list_member.php';
     </script>
     ";

}

?>
