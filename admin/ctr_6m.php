
<?
////////////////////////////6개월/만기형-고객1/팀장1/인센티브-없음/본부장-없음********************************

//1.고객 수수료지급 루틴 *******************************************************
if($rate_cus){
          $pid = "pay"; //수당게시판
          $pname = $name;
          $puser_id = $user_id;

          $pay_type="pay_c";
          $pay_name="고객(약정이자)";

          //지급일, 지급액, 지급회차
          $pay_date=$ctr_end; //만기일1회지급
          $amount=($money*$rate_cus)/100;
          $period="만기지급";

          //수수료 발생근거
          $ctr_name=$name;
          $comment=date("Ymd H:i:s");
          $pay_state="예정";

          //DB입력
          $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                  VALUES ('$pid', '$pname', '$puser_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
          mysql_query("set names utf8", $connect);
          mysql_query($query, $connect);
          mysql_close; //끝내기.

     }//1.고객수수료 지급 종료----



//2.팀장 커미션지급 루틴 ****************************************************************
     if ($rate_manager) {

          $pid="pay"; //팀장커미션
          $pname=$id_manager;

               //팀장 아이디 및 회원정보 조회
               $qt= "select * from member where name='$id_manager' ";
               mysql_query("set names ust8",$connect);
               $rt= mysql_query($qt, $connect);
               $mt= mysql_fetch_array($rt);
          $puser_id=$mt[user_id];
          $pay_type="pay_t";
          $pay_name="팀장(커미션)";

          //지급일, 지급액, 지급회차
          $pay_date=$ctr_end; //만기일1회지급
          $amount=($money*$rate_manager)/100;
          $period="만기지급";

          //수수료 발생근거
          $ctr_name=$name;
          $comment=date("Ymd H:i:s");
          $pay_state="예정";

          //DB입력
          $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                  VALUES ('$pid', '$pname', '$puser_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
          mysql_query("set names utf8", $connect);
          mysql_query($query, $connect);
          mysql_close; //끝내기.


     } //2.팀장 커미션 1회 지급 종료--


//3.팀장-인센티브  없음
//4.본부장-성과급 없음.
$rate_incentive=0;
$rate_top=0;


?>
