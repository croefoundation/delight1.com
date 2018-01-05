<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자 메뉴입니다.");

include('../_header.php');
?>


<?
$rate_com="19"; //회사지급율 **********
$rate_incentive="0.5"; //원래는 계약테이블에서 불러와야 하지만..
$rate_top="0.5";
?>

<?
//날짜계산 함수
    function computeMonth($year, $month, $day, $addMonths) {
        $month += $addMonths;
        $endDay = getMonthEndDay($year, $month);//ここで、前述した月末日を求める関数を使用します
        if($day > $endDay) $day = $endDay;
        $dt = mktime(0, 0, 0, $month, $day, $year);//正規化
        return date("Y-m-d", $dt);
    }

    function getMonthEndDay($year, $month) {
        $dt = mktime(0, 0, 0, $month + 1, 0, $year);
        return date("d", $dt);
    }
?>



<?
//계약테이블을 조회해서 우선 불러온다..-----------
$query_r= "select * from contract where id='ctr'";
mysql_query("set names ust8", $connect);
$result_r= mysql_query($query_r, $connect);
while($data = mysql_fetch_array($result_r)){ //while문 시작--------------------------------

     //1.$data[name] //약정이자지급 :약정조건에 따라  지급일: 만기지급-종료일  , 약정조건 있으면 종료일-기간별, 지급율: rate_cus
     //2.$data[id_manager] //팀장 커미션 12회 지급   지급일: 시작일+1개월,~12개월  지급율  rate_manager=rate_com-rate_cus
     //3.$data[id_incentive] //팀장 인센티브지급 1회 지급  지급일:시작일+1개월, 지급율 rate_incentive
     //4. $data[id_top]//본부장 성과급 1회, 지급일 : 종료일, 지급일 rate_top

     //1.고객 수수료지급 루틴 *******************************************************
     if ($data[name] and $data[rate_cus]) {

          $id="pay"; //고객이자
          $name=$data[name];
          $user_id=$data[user_id];
          $pay_type="pay_c";
          $pay_name="고객(약정이자)";

          //지급일, 지급액, 지급회차
          if ($data[type]=="1년만기/만기지급") { $pay_date=$data[ctr_end]; $amount=$data[money]*$data[rate_cus]/100; $period="만기일시";}
          elseif($data[type]=="월대차/만기지급") { $pay_date=$data[ctr_end]; $amount=$data[money]*$data[rate_cus]/100; $period="만기일시";}
          else{ $pay_date="약정일에 분할지급"; $amount="약정이자"; $period="약정회수만큼";}

          //수수료 발생근거
          $ctr_no=$data[ctr_no];
          $ctr_name=$data[name];
          $comment=date("Ymd H:i:s");
          $pay_state="예정";

          //DB입력
          $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                  VALUES ('$id', '$name', '$user_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
          mysql_query("set names utf8", $connect);
          mysql_query($query, $connect);
          mysql_close; //끝내기.
     } //1.고객수수료 지급 종료----



     //2.팀장 커미션지급 루틴 ****************************************************************
     if ($data[rate_manager] and $data[id_manager]) {

          $id="pay"; //팀장커미션
          $name=$data[id_manager];
               //팀장 아이디 및 회원정보 조회
               $qt= "select * from member where name='$data[id_manager]' ";
               mysql_query("set names ust8",$connect);
               $rt= mysql_query($qt, $connect);
               $mt= mysql_fetch_array($rt);
          $user_id=$mt[user_id];
          $pay_type="pay_t";
          $pay_name="팀장(커미션)";

          $pay_date=$data[ctr_end];
          $amount=$data[money]*$data[rate_cus]/100; $period="만기일시";


          //수수료 발생근거
          $ctr_no=$data[ctr_no];
          $ctr_name=$data[name];
          $comment=date("Ymd H:i:s");
          $pay_state="예정";

          //지급일, 지급액, 지급회차
               //날짜 변환
                 $date_tr=$data[ctr_start];
                 $date_y=substr($date_tr,0,4);
                 $date_m=substr($date_tr,5,2);
                 $date_d=substr($date_tr,8,2);

           //반복구문으로 1계약당 조회후 12회 디비입력
          for ($i=1; $i <13 ; $i++) {
               $dt=computeMonth($date_y,$date_m,$date_d,$i);   //날짜 말일적용하여 계산

               $pay_date=$dt;
               $amount=($data[money]*$data[rate_manager]/100)/12;
               $period=$i;

               //DB입력
               $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                       VALUES ('$id', '$name', '$user_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close; //끝내기.

               }  //for문 종료

     } //2.팀장 커미션 12회 지급 종료--



          //3.팀장 인센티브지급 루틴 ****************************************************************
          if ($data[rate_incentive] and $data[id_incentive]) {

               $id="pay"; //팀장 인센티브
               $name=$data[id_incentive];
                    //팀장 아이디 및 회원정보 조회
                    $qt= "select * from member where name='$data[id_incentive]' ";
                    mysql_query("set names ust8",$connect);
                    $rt= mysql_query($qt, $connect);
                    $mt= mysql_fetch_array($rt);
               $user_id=$mt[user_id];
               $pay_type="pay_i";
               $pay_name="팀장(인센티브)";

               //수수료 발생근거
               $ctr_no=$data[ctr_no];
               $ctr_name=$data[name];
               $comment=date("Ymd H:i:s");
               $pay_state="예정";

               //지급일, 지급액, 지급회차
                    //날짜 변환
                      $date_tr=$data[ctr_start];
                      $date_y=substr($date_tr,0,4);
                      $date_m=substr($date_tr,5,2);
                      $date_d=substr($date_tr,8,2);
               $dt=computeMonth($date_y,$date_m,$date_d,1);   //날짜 말일적용하여 계산

               $pay_date=$dt;
               $amount=($data[money]*$data[rate_incentive]/100);
               $period="1";

               //DB입력
               $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                       VALUES ('$id', '$name', '$user_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close; //끝내기.


          } //3.팀장 인센티브 1회 지급 종료--



          //4.본부장 성과급 지급 루틴 ****************************************************************
          if ($data[rate_top] and $data[id_top]) {

               $id="pay"; //본부장 성과급
               $name=$data[id_top];
                    //팀장 아이디 및 회원정보 조회
                    $qt= "select * from member where name='$data[id_top]' ";
                    mysql_query("set names ust8",$connect);
                    $rt= mysql_query($qt, $connect);
                    $mt= mysql_fetch_array($rt);
               $user_id=$mt[user_id];
               $pay_type="pay_top";
               $pay_name="본부장(성과급)";

               //수수료 발생근거
               $ctr_no=$data[ctr_no];
               $ctr_name=$data[name];
               $comment=date("Ymd H:i:s");
               $pay_state="예정";

               //지급일, 지급액, 지급회차
               $pay_date=$data[ctr_end];
               $amount=($data[money]*$data[rate_top]/100);
               $period="1";

               //DB입력
               $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                       VALUES ('$id', '$name', '$user_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close; //끝내기.

          } //4.본부장 성과급 1회 지급 종료--


     }//while문 종료-----------------------------------------


?>





등록되었습니다.
