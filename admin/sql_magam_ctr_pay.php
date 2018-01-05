<? header("content-type:text/html; charset=UTF-8"); ob_start;

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error_member("관리자용 페이지입니다.");
include('../lib/lib.php'); //시간,날짜변환외
include('./contract_type_rate.php'); //지급율 불러오기

?>
<script>
window.alert('기존 수당테이블을 삭제하고 마감을 새로 돌립니다.');
</script>

<?


//1.먼저 수당을 계산하기 전에 수당테이블을 싹 지운다.. 다시 저장하기 위해서..
//주의***모든 내용 삭제..
//테이블은 남기고 테이블 안의 줄만 전체 삭제 한후.. 다시 오토인크리먼트 초기화
$sql="TRUNCATE TABLE payment" ;
mysql_query($sql,$connect);


//계약테이블을 조회해서 우선 불러온다..-----------
$query_r= "select * from contract where id='ctr' order by ctr_start asc";
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

//$rate_incentive=  위에주어짐  /contract_type_rate.php /include됨.
//$rate_top=  위에 주어짐   /contract_type_rate.php /include됨


//계약에 따라 지급율을 정하고 수당마감을 계약종류별로 진행한다. 그리고 payment 수당테이블에 저장한다.
switch ($type) {
     case '1년만기/만기지급':
          $rate_manager= $rate_1yman-$rate_cus; //1년/만기형-고객1/팀장12/인센티브1/본부장1 **********
          include('./ctr_1yman.php');
          break;

     case '1년만기/반기지급':
          $rate_manager= $rate_1yban-$rate_cus; //1년/반기형-고객2/팀장12/인센티브1/본부장1 **********
          include('./ctr_1yban.php');
          break;

     case '1년만기/분기지급':
          $rate_manager= $rate_1ybun-$rate_cus; //1년/분기형-고객4/팀장12/인센티브1/본부장1 **********
          include('./ctr_1ybun.php');
          break;

     case '1년만기/매월지급':
          $rate_manager= $rate_1ymon-$rate_cus; //1년/매월형-고객12/팀장12/인센티브1/본부장1 **********
          include('./ctr_1ymon.php');
          break;

     case '6개월만기/만기지급':
          $rate_manager= $rate_6m-$rate_cus; //6개월/만기형
          include('./ctr_6m.php');
          break;

     case '월대차/만기지급':
          $rate_manager= $rate_month-$rate_cus; //1년/분기형-고객4/팀장12/인센티브1/본부장1 **********
          include('./ctr_month.php');
          break;
} //계약종류 선택 종료



} //WHILE문종료--계약서 불러와서 하나씩 체크해서 마감돌리기종료

//*********************************************************************************

//수당계산이 완료되면.. 전체회원들을 돌려서..데쉬보드를 업데이드 한다...수당테이블에서 회원테이블로 데이터 통계저장
$query="select * from member where id='mem'";
$result=mysql_query($query,$connect);
while($member = mysql_fetch_array($result)){
include('../admin/dashboard.php');
}



//수당테이블의 번호를 보기좋게 정리해준다.****

for ($i=1; $i <4 ; $i++) {

if($i=1){$table="payment";}
if($i=2){$table="contract";}
if($i=3){$table="member";}

$sql="ALTER TABLE $table AUTO_INCREMENT=1" ;
mysql_query($sql,$connect);

$sql="SET @COUNT = 0 ";
mysql_query($sql,$connect);

$sql="UPDATE `$table` SET `$table`.`no` = @COUNT:=@COUNT+1 ";
mysql_query($sql,$connect);

//맨마지막을 시작값을 다시 세팅함
$query_count ="select count(*) from $table "; //수당테이블 전체 갯수세기
$result1= mysql_query($query_count, $connect);
$temp= mysql_fetch_array($result1);
$totals= $temp[0];

$new=$totals+1;
$sql="ALTER TABLE $table AUTO_INCREMENT=$new" ;
mysql_query($sql,$connect);

}



?>


   <script>
   window.alert('계약정보를 불러와서 마감을 돌렸었습니다.');
   location.href='../member/list_pay.php';
   </script>
