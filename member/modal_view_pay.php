<? header("content_type:text/html; charset=UTF-8");

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보


$pay_no=$_POST[pay_no];  //계약조회할 회원의 계약서테이블의 번호

//////계약정보를 우선 파악해서 읽어놓는다.///////
$queryp= "select * from contract where no='$pay_no'";
mysql_query("set names ust8",$connect);
$resultp= mysql_query($queryp, $connect);
$modal= mysql_fetch_array($resultp);

include('../lib/lib.php'); //시간함수외

?>

<!-- 1.월대차  2.월대차 아닌것  우선 크게 나누고 **************************************-->

    <? // 1.월대차가 아닌 경우 우선 처리
         if ($modal[type]!="월대차/만기지급" and ($modal[state]==("정상") or $modal[state]==("중도상환"))) {

                         if ($modal[state]=="정상") { ?>
                        <b>계약번호 :</b> <b class="text-danger"><?=$modal[ctr_no]?></b> <br />
                        <b>계약종류 :</b> <b class="text-danger"><?=$modal[type]?></b> <br />
                        <b>계약자명 :</b> <b class="text-info"><?=$modal[name]?></b> <br/>
                        <b>계약상태 :</b> <badge class="badge badge-danger"><?=$modal[state]?></badge> <br/><br/>
                        <?}?>

                                        <? // 4.중도상환 경우 처리
                                     if ($modal[state]=="중도상환") { ?>
                                    <h4><b> 본 계약은 <font color="red"><?=$modal[state]?></font> 되었습니다.</b></h4>
                                     <b>계약번호 :</b> <b class="text-danger"><?=$modal[ctr_no]?></b> <br />
                                     <b>계약종류 :</b> <b class="text-danger"><?=$modal[type]?></b> <br />
                                     <b>계약자명 :</b> <b class="text-info"><?=$modal[name]?></b> <br/>
                                     <b>계약상태 :</b> <badge class="badge badge-danger"><?=$modal[state]?></badge> <br/><br/>
                                     <b>중도상환일 :</b> <b class="text-info"><?=$modal[stop_date]?></b> <br/>
                                     상환일 기준일까지 지급된 고객이자 내역입니다.<br>
                                     <? } //중도상환 노출 종료 ?>


                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="font-size:11px;">
                                <thead class="text-white" style="background-color:#808080">

                    <!-- *****1-1.1년만기(만기지급): 가장많은 유형 -->
                       <? if ($modal[type]=="1년만기/만기지급") { ?>
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">약정기간</th>
                                        <th class="text-center">지급예정일</th>
                                        <th class="text-center">지급이자(원)</th>
                                        <th class="text-center">지급구분</th>
                                    </tr>
                                </thead>
                                <tbody>
                                      <tr>
                                         <td class="text-center">1</td>
                                         <td class="text-center"><?=$modal[ctr_start]?>~<?=$modal[ctr_end]?></td>
                                         <td class="text-center"><?=$modal[ctr_end]?></td>
                                         <td class="text-center"><?=number_format($modal[sum_cus])?></td>
                                         <td class="text-center text-info">만기지급</td> <!--1년(만기지급종료)-->
                                     </tr>
                     <!--2. 1년만기(반기,분기,매월), 6개월(만기)***********-->
                                     <?} else{?>
                                          <tr>
                                              <th class="text-center">NO</th>
                                              <th class="text-center">발생기간</th>
                                              <th class="text-center">지급예정일</th>
                                              <th class="text-center">지급이자(원)</th>
                                              <th class="text-center">지급구분</th>
                                          </tr>
                                         </thead>
                                         <tbody>

                                        <?
                                         $i=1; //수당테이블에서 <계약번호>에 해당하는 여러발생 수당중 <계약자본인이름>으로 된 수당을 불러온다.오름차순으로 정렬하고
                                          $query= "select * from payment where ctr_no='$modal[ctr_no]' and name='$modal[name]' order by pay_date asc";
                                          mysql_query("set names ust8", $connect);
                                          $result= mysql_query($query, $connect);
                                          while($dm = mysql_fetch_array($result)){  ?>
                                               <tr>
                                                 <td class="text-center"><?=$i?></td>
                                                 <?if($i==1){?>
                                                <td class="text-center"><?=$modal[ctr_start]?>~<?=$dm[pay_date]?></td>
                                            <?}else{?>
                                                 <td class="text-center"><?=$start?>~<?=$dm[pay_date]?></td> <?}?>

                                                 <td class="text-center"><?=$dm[pay_date]?></td>
                                                 <td class="text-center"><?=number_format($dm[amount])?></td>
                                                 <td class="text-center text-info"><?=$dm[pay_state]?></td> <!--1.만기지급/2.반기지급/3.분기지급/4.매월지급중선택-->
                                              </tr>
                                              <?
                                              $i++; $start=$dm[pay_date];
                                         } //while문 종료
                                    } //else 문 종료(2.1년만기지급형 이외의 로직)

                                          ?>


                                     <!--total cell-->
                                     <tr style="font-size:11px; background-color:#fae6d1">
                                       <td colspan="2" class="text-center">(1)약정금액</td>
                                       <td class="text-center"><?=number_format($modal[money])?>원</td>
                                       <td class="text-center text-danger"></td>
                                       <td colspan="3" class="text-center"></td>
                                     </tr>

                                     <tr style="font-size:11px; background-color:#fae6d1">
                                           <td colspan="2" class="text-center"><?if($modal[state]!="중도상환"){?>(2)발생이자<?}else{ echo "(2)지급이자"; }?></td>
                                         <td class="text-center"><?if($modal[state]!="중도상환"){?>(년<?= $modal[rate_cus]?> %)<?}else{}?></td>
                                         <td class="text-center text-danger"><?=number_format($modal[sum_cus])?></td>
                                         <td colspan="3" class="text-center"></td>
                                     </tr>

                                     <tr class="text-white" style="font-size:11px; background-color:#5f929d">
                                           <td colspan="2"></td>
                                         <td style="padding-left:25px" class="text-left">원리합계예정액 (1)+(2)</td>
                                         <? $total_money=$modal[money]+$modal[sum_cus]; ?>
                                         <td class="text-center"><?=number_format($total_money)?></td>
                                         <td colspan="3" class="text-center"></td>
                                     </tr>

                                <? }?>


<!-- 월대차에 대한 납부조회 출력폼 ****************************************************** -->
                 <? if ($modal[type]=="월대차/만기지급" and $modal[state]=="정상") { ?>

                        <b>계약번호 :</b> <b class="text-danger"><?=$modal[ctr_no]?></b><br />
                        <b>계약종류 :</b> <b class="text-danger"><?=$modal[type]?></b>  &nbsp; &nbsp; &nbsp;
                        <b>계약자명 :</b> <b class="text-info"><?=$modal[name]?></b> <br/><br/>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="font-size:11px;">
                                <thead class="text-white" style="background-color:#808080">

                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">예정일</th>
                                        <th class="text-center">예정액</th>
                                        <th class="text-center">*입금일</th>
                                        <th class="text-center">*입금액</th>
                                        <th class="text-center">*납부현황</th>
                                    </tr>
                                </thead>
                                <tbody>

                                   <!-- //날짜 변환 -->
                                     <?
                                     $data=$modal[ctr_start];
                                     $date_y=substr($data,0,4);
                                     $date_m=substr($data,5,2);
                                     $date_d=substr($data,8,2);

                                     for ($i=1; $i <13 ; $i++) {
                                          $dt=computeMonth($date_y,$date_m,$date_d,$i-1);   //날짜 말일적용하여 계산
                                          $mth="m".$i; $mmth="mm".$i;   //매월납입한 달 속성
                                          ?>

                                     <tr>
                                       <td class="text-center"><?=$i?>회차</td>
                                       <td class="text-center"><?=$dt?></td>
                                       <td class="text-center"><?=number_format($modal[money]/12)?></td>
                                       <td class="text-center text-danger"><?=$modal[$mth]?></td>
                                       <td class="text-center text-danger"><?=number_format($modal[$mmth])?></td>
                                       <td class="text-center">
                                            <?if($modal[$mmth]){?> <span class="badge badge-danger">납입완료</span> <?}else{?> <span class="text-info">입금예정</span> <?}?>
                                       </td>

                                    </tr>

                                    <?}?>

                                    <!--total cell-->
                                    <tr style="font-size:11px; background-color:#fae6d1">
                                       <td style="padding-left:25px" colspan="2" class="text-left">(1)납부할 총액</td>
                                       <td class="text-center text-danger"><?=number_format($modal[money])?></td>
                                       <td class="text-right"><b>*누적입금액 :</b></td>
                                       <td class="text-center text-danger"><?=number_format($modal[sum_mm])?></td>
                                       <td class="text-center"></td>
                                    </tr>

                                    <tr style="font-size:11px; background-color:#fae6d1">
                                        <td style="padding-left:25px" colspan="2" class="text-left">(2)발생이자(년<?= $modal[rate_cus]?> %)</td>
                                        <td class="text-center text-danger"><?=number_format($modal[sum_cus])?></td>
                                        <td colspan="3" class="text-center"></td>
                                    </tr>

                                    <tr class="text-white" style="font-size:11px; background-color:#5f929d">
                                        <td style="padding-left:25px" colspan="2" class="text-left">원리합계예정액 (1)+(2)</td>
                                        <? $total_money=$modal[money]+$modal[sum_cus]; ?>
                                        <td class="text-center"><?=number_format($total_money)?></td>
                                        <td colspan="3" class="text-center"></td>
                                    </tr>

                                    <? } //월대차 출력폼 끝 ?>



<!-- 중도해지 처리건 ************************************* -->
                                    <? // 3.중도해지 경우 처리
                                    if ($modal[state]=="중도해지") { ?>

                                                  <h4><b> 본 계약은 <font color="red"><?=$modal[state]?></font> 되었습니다.</b></h4>
                                                   <b>계약번호 :</b> <b class="text-danger"><?=$modal[ctr_no]?></b> <br />
                                                   <b>계약종류 :</b> <b class="text-danger"><?=$modal[type]?></b> <br />
                                                   <b>계약자명 :</b> <b class="text-info"><?=$modal[name]?></b> <br/>
                                                   <b>계약상태 :</b> <badge class="badge badge-danger"><?=$modal[state]?></badge> <br/><br/>
                                                   <b>중도해지일 :</b> <b class="text-info"><?=$modal[stop_date]?></b> <br/>
                                                   <div class="table-responsive">
                                                       <table class="table table-striped table-hover" style="font-size:11px;">
                                                           <thead class="text-white" style="background-color:#808080">
                                                           <tbody>


                                                                 <tr>
                                                                    <td class="text-center td-left">고객 약정원금  </td>
                                                                    <td colspan="4"  class="text-center td-left text-danger"><?=number_format($modal[money])?></td>
                                                                </tr>

                                                                 <?if($member[level]=="C"){   //고객화면노출?>
                                                                <tr>
                                                                   <td class="text-center">(1)기지급액  </td>
                                                                   <td class="text-center"><?=number_format(-$modal[sum_cus])?></td>
                                                               </tr>
                                                               <tr>
                                                                  <td class="text-center">(2)공제금액  </td>
                                                                  <td class="text-center text-danger">-<?=number_format($modal[sum_cus])?></td>
                                                             </tr>
                                                             <tr>
                                                               <td class="text-center td-left1">중도해지 정산금액 </td>
                                                               <td class="text-center text-danger td-left1"><?=number_format($modal[money]+$modal[sum_cus])?></td>
                                                             </tr>

                                                          <?}else{   // 팀장이상 화면노출 ?>

                                                               <tr>
                                                                  <td class="text-center text-left">구 분 </td>
                                                                   <td class="text-center">성 명</td>
                                                                  <td class="text-center">지급한 금액</td>
                                                                  <td class="text-center">공제 금액</td>
                                                              </tr>
                                                               <tr>
                                                                  <td class="text-left" style="padding-left:20px;">(1)고 객 </td>
                                                                  <td class="text-center"><?=$modal[name]?></td>
                                                                  <td class="text-center"><?=number_format(-$modal[sum_cus])?></td>
                                                                  <td class="text-center text-danger"><?=number_format($modal[sum_cus])?></td>
                                                              </tr>
                                                              <tr>
                                                                 <td class="text-left" style="padding-left:20px;">(2)팀장커미션</td>
                                                                 <td class="text-center"><?=$modal[id_manager]?></td>
                                                                 <td class="text-center"><?=number_format(-$modal[sum_manager])?></td>
                                                                 <td class="text-center text-danger"><?=number_format($modal[sum_manager])?></td>
                                                             </tr>
                                                             <tr>
                                                               <td class="text-left" style="padding-left:20px;">(3)소개팀장 인센티브 </td>
                                                               <td class="text-center"><?=$modal[id_incentive]?></td>
                                                               <td class="text-center"><?=number_format(-$modal[sum_incentive])?></td>
                                                               <td class="text-center text-danger"><?=number_format($modal[sum_incentive])?></td>
                                                            </tr>
                                                            <tr>
                                                              <td class="text-center td-left1">공제금액 합계  </td>
                                                              <td colspan="4"  class="text-center td-left1"><?=number_format($modal[sum_cus]+$modal[sum_manager]+$modal[sum_incentive])?></td>
                                                           </tr>

                                                          <?} //팀장이상 노출종료 ?>

                                                           <? } //중도해지 노출 종료 ?>



                                </tbody>
                            </table>


                        </div>
