
<?
////////////////////////////1년/반기형-고객2/팀장12/인센티브1/본부장1 ********************************

//1.고객 수수료지급 루틴 *******************************************************
if($rate_cus){
          $pid = "pay"; //수당게시판
          $pname = $name;
          $puser_id = $user_id;

          $pay_type="pay_c";
          $pay_name="고객(약정이자)";

          //수수료 발생근거
          $ctr_name=$name;
          $comment=date("Ymd H:i:s");
          $pay_state="예정";

          //지급일, 지급액, 지급회차
               //날짜 변환
                 $date_tr=$ctr_end; //마감일 기준
                 $date_y=substr($date_tr,0,4);
                 $date_m=substr($date_tr,5,2);
                 $date_d=substr($date_tr,8,2);

                 if($date_d=="30"){$date_d=31;} //역계산을 할때 오류수정...마감일 기준으로하다보니..
                 if($date_m=="02" and ($date_d=="28"or $date_d=="29")){$date_d=31;}

           //반복구문으로 1계약당 조회후 2회 디비입력
          for ($i=1; $i <3 ; $i++) {
               $dt=computeMonth($date_y,$date_m,$date_d,5*$i-10);   //날짜 말일적용하여 역으로 계산(5n-10)

               $pay_date=$dt;
               $amount=($money*$rate_cus/100)/2; //2회로 분할지급
               $period=$i."회/반기";

               //DB입력
               $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                       VALUES ('$pid', '$pname', '$puser_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close; //끝내기.

               }  //for문 종료

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


          //수수료 발생근거
          $ctr_name=$name;
          $comment=date("Ymd H:i:s");
          $pay_state="예정";

          //지급일, 지급액, 지급회차
               //날짜 변환
                 $date_tr=$ctr_end; //마감일 기준
                 $date_y=substr($date_tr,0,4);
                 $date_m=substr($date_tr,5,2);
                 $date_d=substr($date_tr,8,2);

                 if($date_d=="30"){$date_d=31;} //역계산을 할때 오류수정...마감일 기준으로하다보니..
                 if($date_m=="02" and ($date_d=="28"or $date_d=="29")){$date_d=31;}

           //반복구문으로 1계약당 조회후 12회 디비입력
          for ($i=1; $i <13 ; $i++) {
               $dt=computeMonth($date_y,$date_m,$date_d,$i-12);   //날짜 말일적용하여 역으로 계산

               $pay_date=$dt;
               $amount=($money*$rate_manager/100)/12;
               $period=$i."회/12th";

               //DB입력
               $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                       VALUES ('$pid', '$pname', '$puser_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close; //끝내기.

               }  //for문 종료

     } //2.팀장 커미션 12회 지급 종료--



//3.팀장 인센티브지급 루틴 ****************************************************************
          if ($rate_incentive) {

               $pid="pay"; //수당게시판
               $pname=$id_incentive;

                    //팀장 아이디 및 회원정보 조회
                    $qt= "select * from member where name='$id_incentive' ";
                    mysql_query("set names ust8",$connect);
                    $rt= mysql_query($qt, $connect);
                    $mt= mysql_fetch_array($rt);
               $puser_id=$mt[user_id];

               $pay_type="pay_i";
               $pay_name="팀장(인센티브)";

               //수수료 발생근거
               $ctr_name=$name;
               $comment=date("Ymd H:i:s");
               $pay_state="예정";

               //지급일, 지급액, 지급회차
                    //날짜 변환
                      $date_tr=$ctr_end; //마감일 기준
                      $date_y=substr($date_tr,0,4);
                      $date_m=substr($date_tr,5,2);
                      $date_d=substr($date_tr,8,2);

                      if($date_d=="30"){$date_d=31;} //역계산을 할때 오류수정...마감일 기준으로하다보니..
                      if($date_m=="02" and ($date_d=="28"or $date_d=="29")){$date_d=31;}

               $dt=computeMonth($date_y,$date_m,$date_d,-11);   //날짜 말일적용하여 계산

               $pay_date=$dt;
               $amount=($money*$rate_incentive/100);
               $period="익월1회";

               //DB입력
               $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                       VALUES ('$pid', '$pname', '$puser_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close; //끝내기.

          } //3.팀장 인센티브 1회 지급 종료--



//4.본부장 성과급 지급 루틴 ****************************************************************
          if ($rate_top) {

               $pid="pay"; //수당게시판
               $pname=$id_top;

                    //팀장 아이디 및 회원정보 조회
                    $qt= "select * from member where name='$id_top' ";
                    mysql_query("set names ust8",$connect);
                    $rt= mysql_query($qt, $connect);
                    $mt= mysql_fetch_array($rt);
               $puser_id=$mt[user_id];

               $pay_type="pay_top";
               $pay_name="본부장(성과급)";

               //수수료 발생근거
               $ctr_name=$name;
               $comment=date("Ymd H:i:s");
               $pay_state="예정";

               //지급일, 지급액, 지급회차

               $pay_date=$ctr_end; //마감일 1회지급
               $amount=($money*$rate_top/100);
               $period="만기지급";

               //DB입력
               $query="INSERT INTO payment (id, name, user_id, pay_name, pay_type, pay_date, amount, period, ctr_no, ctr_name, comment, pay_state)
                       VALUES ('$pid', '$pname', '$puser_id', '$pay_name', '$pay_type', '$pay_date', '$amount', '$period', '$ctr_no', '$ctr_name', '$comment', '$pay_state')";
               mysql_query("set names utf8", $connect);
               mysql_query($query, $connect);
               mysql_close; //끝내기.

          } //4.본부장 성과급 1회 지급 종료--

?>
