<? ob_start(); ?>

<?
include('../lib/db_connect.php');
$connect = dbconn(); //DB컨넥트
$member = member();  //회원정보

if ($member[user_id] != "admin") Error("관리자 메뉴입니다.");
include('../lib/lib.php'); //시간,날짜변환외

include('../_header.php'); ?>

<?
$stop_state = $_POST['stop_state'];
$ctr_no=$_POST['ctr_no'];
$stop_date=$_POST['stop_date'];
$name=$_POST['name'];
$id_manager=$_POST['id_manager'];
$id_incentive =$_POST['id_incentive'];
$id_top =$_POST['id_top'];

$minus_c =$_POST['minus_c'];
$minus_t =$_POST['minus_t'];
$minus_i =$_POST['minus_i'];
$minus_top =$_POST['minus_top'];
$memo_x =$_POST['memo_x'];



$now=date("Ymd H:i:s");

//1.중도해지 처리 루틴*************************************************************************

if ($stop_state=="stop_cus") {

//1.계약서 업데이트
$query="update contract set
          state = '중도해지',
          stop_date ='$stop_date',
          stop_exe_date='$now',
          sum_cus ='-$minus_c',
          sum_manager ='-$minus_t',
          sum_incentive ='-$minus_i',
          sum_top='$minus_top',
          memo_x='$memo_x'

          where ctr_no='$ctr_no'";
          mysql_query("set names utf8", $connect);
          mysql_query($query, $connect);
          mysql_close;


//2.수당테이블에서 해당계약으로 인한 발생분을 삭제
$query="delete from payment where ctr_no='$ctr_no' and id='pay'"; //행을 삭제
          mysql_query("set names utf8", $connect);
          mysql_query($query, $connect);
          mysql_close;

//3.[팀장커미션분]기지급된 수당을 앞으로 받을 수당에서 삭제
$gongje=$minus_t; //공제할 금액을 체크
$query="select * from payment where name='$id_manager' and pay_state='예정' order by pay_date asc"; //팀장이름으로 된 수당을 불러와서
mysql_query("set names utf8", $connect);
$result=mysql_query($query, $connect);
$i=1;
while ($data=mysql_fetch_array($result)) {
     if($gongje>$data[amount]){  //공제금액이 줄 금액보다 크면
          $amount=-round($data[amount]);   //수당을 공제하고
          $gongje=$gongje-$data[amount];   //공제잔액을 줄이고

          $banpum_ctr=$ctr_no."(".$name.")";   //반품 계약서번호를 기록하고
          $banpum_process=$i."차공제(".number_format($amount)."/".number_format($data[amount]).")";          //반품공제 횟수를 기록하고
          $gongje_process="잔액(".number_format($gongje).")/".number_format($minus_t);  //공제잔액/전체공제액

          $query="update payment set
                    pay_state = '공제',
                    stop_exe_date='$now',
                    amount='0',
                    banpum_ctr='$banpum_ctr',
                    gongje_process='$gongje_process',
                    banpum_process='$banpum_process'

          where no='$data[no]'";
          mysql_query("set names utf8", $connect);
          mysql_query($query, $connect);
          mysql_close;
          $i++;

     }else{   // 공제를 완료하는 루틴
          $amount=round($data[amount])-round($gongje);

          $banpum_ctr=$ctr_no."(".$name.")";
          $banpum_process=$i."차공제완료(".number_format(-$gongje)."/".number_format($data[amount]).")";          //반품공제 횟수를 기록하고
          $gongje_process="잔액(0)/".number_format($minus_t);  //공제잔액/전체공제액

          $query="update payment set
                    pay_state = '공제',
                    stop_exe_date='$now',
                    amount='$amount',
                    banpum_ctr='$banpum_ctr',
                    gongje_process='$gongje_process',
                    banpum_process='$banpum_process'

          where no='$data[no]'";
          mysql_query("set names utf8", $connect);
          mysql_query($query, $connect);
          mysql_close;

          $gongje=0;
          if($gongje=="0")break; //다 공제했으면 종료
          }
     }//while문 수당공제완료


     //4.[소개팀장 인센티브분]기지급된 수당을 앞으로 받을 수당에서 삭제
     $gongje=$minus_i; //공제할 금액을 체크
     $query="select * from payment where name='$id_incentive' and pay_state='예정' order by pay_date asc"; //소개팀장이름으로 된 수당을 불러와서
     mysql_query("set names utf8", $connect);
     $result=mysql_query($query, $connect);
     $i=1;
     while ($data=mysql_fetch_array($result)) {
          if($gongje>$data[amount]){  //공제금액이 줄 금액보다 크면
               $amount=-round($data[amount]);   //수당을 공제하고
               $gongje=$gongje-$data[amount];   //공제잔액을 줄이고

               $banpum_ctr=$ctr_no."(".$name.")";   //반품 계약서번호를 기록하고
               $banpum_process=$i."차공제(".number_format($amount)."/".number_format($data[amount]).")";          //반품공제 횟수를 기록하고
               $gongje_process="잔액(".number_format($gongje).")/".number_format($minus_i);  //공제잔액/전체공제액

               $query="update payment set
                         pay_state = '공제',
                         stop_exe_date='$now',
                         amount='$amount',
                         banpum_ctr='$banpum_ctr',
                         gongje_process='$gongje_process',
                         banpum_process='$banpum_process'

               where no='$data[no]'";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close;
               $i++;

          }else{   // 공제를 완료하는 루틴
               $amount=round($data[amount])-round($gongje);

               $banpum_ctr=$ctr_no."(".$name.")";
               $banpum_process=$i."차공제완료(".number_format(-$gongje)."/".number_format($data[amount]).")";          //반품공제 횟수를 기록하고
               $gongje_process="잔액(0)/".number_format($minus_i);  //공제잔액/전체공제액

               $query="update payment set
                         pay_state = '공제',
                         stop_exe_date='$now',
                         amount='$amount',
                         banpum_ctr='$banpum_ctr',
                         gongje_process='$gongje_process',
                         banpum_process='$banpum_process'

               where no='$data[no]'";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close;

               $gongje=0;
               if($gongje=="0")break; //다 공제했으면 종료
               }
          }//while문 수당공제완료


          //5.[본부장 성과급]기지급된 수당을 앞으로 받을 수당에서 삭제
            //중도해지는 본부장이 수당을 받지 않은 상태에서 해지 하므로 삭제할 게  없음..


} //if문 --****중도해지 처리 완료*******






//**************************중도상환처리 루틴 **************************************************
if ($stop_state=="stop_com") {


//1.계약서 업데이트
     $query="update contract set
               state = '중도상환',
               stop_date ='$stop_date',
               stop_exe_date='$now',
               sum_cus ='$minus_c',
               sum_manager ='$minus_t',
               sum_incentive ='$minus_i',
               sum_top='$minus_top',
               memo_x='$memo_x'

               where ctr_no='$ctr_no'";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close;


     //2. <고객> 수당테이블에서 해당계약으로 인해 발생된 수당을 불러와서  그 사람이름으로 조회

     $query="select * from payment where ctr_no='$ctr_no' and name='$name' order by pay_date asc";
     //지급된 수당수를 파악해서
          $query_count="select count(*) from payment where ctr_no='$ctr_no' and name='$name' and pay_state='지급완료' order by pay_date asc";
          mysql_query("set names utf8");  //언어셋 utf8
          $result1= mysql_query($query_count, $connect);
          $temp= mysql_fetch_array($result1);
          $count_pay= $temp[0];//지급된 수당횟수

     mysql_query("set names utf8", $connect);
     $result=mysql_query($query, $connect);

     $i=1;
     $next=$count_pay+1;
     $banpum_ctr=$ctr_no."(".$name.")";
     While($data=mysql_fetch_array($result)){ //당사자 수당을 불러와서.
               if($data[pay_state]=='지급완료'){
                    //지급이 된거라면 우선 중도상환 업데이트 해주고
                    $query="update payment set
                              stop_exe_date='$now',
                              banpum_ctr='$banpum_ctr',
                              banpum_process='중도상환'

                    where no='$data[no]'";
                    mysql_query("set names utf8", $connect);
                    mysql_query($query, $connect);
                    mysql_close;

               }elseif($i==$next){
                    //하나더
                    $query="update payment set
                              pay_state='지급완료',
                              pay_date_out='$now',
                              stop_exe_date='$now',
                              banpum_ctr='$banpum_ctr',
                              banpum_process='중도상환'

                    where no='$data[no]'";
                    mysql_query("set names utf8", $connect);
                    mysql_query($query, $connect);
                    mysql_close;

               }else{
               //나머지 수당테이블 삭제
               $query="delete from payment where no='$data[no]' and ctr_no='$ctr_no' and id='pay'"; //행을 삭제
                         mysql_query($query, $connect);
                         mysql_close;
               } //if문 종료
               $i++;

     }//while문 해당조건 처리종료


     //3. 팀장커미션-- 수당테이블에서 해당계약으로 인해 발생된 수당을 불러와서  그 사람이름으로 조회

     $query="select * from payment where ctr_no='$ctr_no' and name='$id_manager' order by pay_date asc";
     //지급된 수당수를 파악해서
          $query_count="select count(*) from payment where ctr_no='$ctr_no' and name='$id_manager' and pay_state='지급완료' order by pay_date asc";
          mysql_query("set names utf8");  //언어셋 utf8
          $result1= mysql_query($query_count, $connect);
          $temp= mysql_fetch_array($result1);
          $count_pay= $temp[0];//지급된 수당횟수

     mysql_query("set names utf8", $connect);
     $result=mysql_query($query, $connect);

     $i=1;
     $next=$count_pay+1;
     $banpum_ctr=$ctr_no."(".$name.")";
     While($data=mysql_fetch_array($result)){ //당사자 수당을 불러와서.
               if($data[pay_state]=='지급완료'){
                    //지급이 된거라면 우선 중도상환 업데이트 해주고
                    $query="update payment set
                              stop_exe_date='$now',
                              banpum_ctr='$banpum_ctr',
                              banpum_process='중도상환'

                    where no='$data[no]'";
                    mysql_query("set names utf8", $connect);
                    mysql_query($query, $connect);
                    mysql_close;

               }elseif($i==$next){
                    //하나더
                    $query="update payment set
                              pay_state='지급완료',
                              pay_date_out='$now',
                              stop_exe_date='$now',
                              banpum_ctr='$banpum_ctr',
                              banpum_process='중도상환'

                    where no='$data[no]'";
                    mysql_query("set names utf8", $connect);
                    mysql_query($query, $connect);
                    mysql_close;

               }else{
               //나머지 수당테이블 삭제
               $query="delete from payment where no='$data[no]' and ctr_no='$ctr_no' and id='pay'"; //행을 삭제
                         mysql_query($query, $connect);
                         mysql_close;
               } //if문 종료
               $i++;

     }//while문 해당조건 처리종료



     //4. 소개팀장 인센티브 중도상환-- 수당테이블에서 해당계약으로 인해 발생된 수당을 불러와서  그 사람이름으로 조회

     $query="select * from payment where ctr_no='$ctr_no' and name='$id_incentive' order by pay_date asc";
     mysql_query("set names utf8", $connect);
     $result=mysql_query($query, $connect);
     $data=mysql_fetch_array($result);

     $banpum_ctr=$ctr_no."(".$name.")";
     if($data[pay_state]=='지급완료'){
                    //지급이 된거라면 우선 중도상환 업데이트 해주고
                    $query="update payment set
                              stop_exe_date='$now',
                              banpum_ctr='$banpum_ctr',
                              banpum_process='중도상환'

                    where no='$data[no]'";
                    mysql_query("set names utf8", $connect);
                    mysql_query($query, $connect);
                    mysql_close;

               }else{
                    //하나더 지급안된건 해주고..
                    $query="update payment set
                              pay_state='지급완료',
                              pay_date_out='$now',
                              stop_exe_date='$now',
                              banpum_ctr='$banpum_ctr',
                              banpum_process='중도상환'

                    where no='$data[no]'";
                    mysql_query("set names utf8", $connect);
                    mysql_query($query, $connect);
                    mysql_close;
               }  //인센티브 중도상환 종료



          //5. 본부장 처리 발생된 수당을 불러와서  그 사람이름으로 조회
          $query="delete from payment where name='$id_top' and ctr_no='$ctr_no' and pay_type='pay_top'"; //행을 삭제
                    mysql_query($query, $connect);
                    mysql_close;






} //if문 --****중도상환 처리 완료*******

if ($stop_state == 'stop_cus') {
    $state = "중도해지(고객사유)";
} else {
    $state = "중도상환(회사사유)";
}
?>

<script>
window.alert('<?=$state?>가 처리되었습니다.');
location.href='../member/list_ctr.php';
</script>
