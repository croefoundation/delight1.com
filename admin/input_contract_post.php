<? header("content-type:text/html; charset=UTF-8"); ob_start;

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자용 페이지입니다.");
include('../lib/lib.php'); //시간,날짜변환외
include('./contract_type_rate.php'); //지급율 불러오기


$id= $_POST[id];
$newtype=$_POST[newtype];
$ctr_no= $_POST[ctr_no];
$name= $_POST[name];
$why=$_POST[why]; //한팀장 고객확인용

//계약번호가 입력되면 중복여부를 체크한다.
$query_ctr= "select * from contract where ctr_no='$ctr_no'";
mysql_query("set names ust8", $connect);
$result_ctr= mysql_query($query_ctr, $connect);
$data_ctr = mysql_fetch_array($result_ctr);
if($data_ctr[ctr_no])Error("이미 중복된 계약번호가 존재합니다. /n 확인후 다른 계약번호를 입력하세요!");


//이름이 입력되면 회원정보를 불러와서 아이디를 찾고, 팀장과 그 팀장의 소개자와 본부장을 찾아서 등록한다.
$query_id= "select * from member where name='$name'";
mysql_query("set names ust8", $connect);
$result_id= mysql_query($query_id, $connect);
$dm = mysql_fetch_array($result_id);

$user_id=$dm[user_id]; //유저아이디


$id_manager=$_POST[id_manager];
if ($id_manager!=$dm[id_manager])Error("등록된 담당팀장과 일치하지 않습니다.");
$id_incentive=$dm[id_incentive];
$id_top=$dm[id_top];


$type= $_POST[type];
$money= $_POST[money];
$money_old= $_POST[money_old];
$money_new= $_POST[money_new];
$ctr_start=$_POST[ctr_start];
$ctr_end=$_POST[ctr_end];
$ctr_date=$_POST[ctr_date];

$d1=new DateTime("$ctr_start");
$d2=new DateTime("$ctr_end");
$diff=date_diff($d1,$d2);
if($diff->days<170)Error("계약기간이 6개월이상은 되야 되지 않나여?  혹시 계약기간을 잘못입력한 것은 아닌지요?");

$tm=$money_old+$money_new;
if($money_old and $money!=$tm)Error("재계약인경우: 약정금액=[이월자금]+[신규자금] 합입니다. 확인해주세요");

$rate_cus= $_POST[rate_cus];


if($why=="고객용"){
$rate_manager=0;
$rate_incentive=0;
$rate_top=0;
}

//계약에 따라 지급율을 정하고 수당마감을 계약종류별로 진행한다. 그리고 payment 수당테이블에 저장한다.
switch ($type) {
     case '1년만기/만기지급':
          $rate_manager= $rate_1yman-$rate_cus; //1년/만기형-고객1/팀장12/인센티브1/본부장1 **********

          if($why=="고객용"){$rate_manager=0;}//한팀장 고객확인용
          include('./ctr_1yman.php');
          break;

     case '1년만기/반기지급':
          $rate_manager= $rate_1yban-$rate_cus; //1년/반기형-고객2/팀장12/인센티브1/본부장1 **********
          if($why=="고객용"){$rate_manager=0;}//한팀장 고객확인용
          include('./ctr_1yban.php');
          break;

     case '1년만기/분기지급':
          $rate_manager= $rate_1ybun-$rate_cus; //1년/분기형-고객4/팀장12/인센티브1/본부장1 **********
          if($why=="고객용"){$rate_manager=0;}//한팀장 고객확인용
          include('./ctr_1ybun.php');
          break;

     case '1년만기/매월지급':
          $rate_manager= $rate_1ymon-$rate_cus; //1년/매월형-고객12/팀장12/인센티브1/본부장1 **********
          if($why=="고객용"){$rate_manager=0;}//한팀장 고객확인용
          include('./ctr_1ymon.php');
          break;

     case '6개월만기/만기지급':
          $rate_manager= $rate_6m-$rate_cus; //6개월/만기형
          if($why=="고객용"){$rate_manager=0;}//한팀장 고객확인용
          include('./ctr_6m.php');
          break;

     case '월대차/만기지급':
          $rate_manager= $rate_month-$rate_cus; //1년/분기형-고객4/팀장12/인센티브1/본부장1 **********
          if($why=="고객용"){$rate_manager=0;}//한팀장 고객확인용
          include('./ctr_month.php');
          break;
} //계약종류 선택 종료



//$rate_incentive=  위에주어짐
//$rate_top=  위에 주어짐

$sum_cus= $money*$rate_cus/100;
$sum_manager= $money*$rate_manager/100;
$sum_incentive= $money*$rate_incentive/100;
$sum_top=$money*$rate_top/100;



$memo=$_POST[memo];
$ctr_old=$_POST[ctr_old];
$state="정상";





//DB입력
$query1="INSERT INTO contract (id,newtype,ctr_no,name,user_id,type,money,money_old,money_new,ctr_start,ctr_end,ctr_date,rate_cus,rate_manager,rate_incentive,rate_top,sum_cus, sum_manager, sum_incentive, sum_top, memo, ctr_old, state, id_manager, id_incentive, id_top)
                   VALUES ('$id','$newtype','$ctr_no','$name','$user_id','$type','$money','$money_old','$money_new','$ctr_start','$ctr_end','$ctr_date','$rate_cus','$rate_manager','$rate_incentive', '$rate_top','$sum_cus','$sum_manager','$sum_incentive','$sum_top','$memo','$ctr_old','$state','$id_manager','$id_incentive','$id_top')";
mysql_query("set names utf8", $connect);
mysql_query($query1, $connect);
mysql_close; //끝내기.



// 회원등록,수당계산이 완료되면.. 전체회원들을 돌려서..데쉬보드를 업데이드 한다...수당테이블에서 회원테이블로 데이터 통계저장
$query="select * from member where id='mem'";
$result=mysql_query($query,$connect);
while($member = mysql_fetch_array($result)){
include('../admin/dashboard.php');
}




?>


   <script>
   window.alert('계약정보가 등록되었습니다.');
   location.href='../member/list.php';
   </script>
