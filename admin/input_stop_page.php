<? ob_start(); ?>
<html>

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
$name=$_POST['name'];
$stop_date=$_POST['stop_date'];


//계약서조회 후 계약번호와 이름이 같은지 확인
$query_ctr = "select * from contract where ctr_no='$ctr_no'";
mysql_query("set names ust8", $connect);
$result_ctr = mysql_query($query_ctr, $connect);
$ctr = mysql_fetch_array($result_ctr);

if ($ctr[ctr_no] != $ctr_no) {
    Error("존재하지 않는 계약입니다.");
}
if ($ctr[name] != $name) {
    Error("계약번호와 계약자가 일치하지 않습니다.");
}
if ($stop_state == 'stop_cus') {
    $state = "중도해지(고객사유)";
} else {
    $state = "중도상환(회사사유)";
}

//고객 수당테이블 조회
$query_pay = "select * from payment where ctr_no='$ctr_no' and name='$name' order by pay_date asc";
mysql_query("set names ust8", $connect);
$result_pay = mysql_query($query_pay, $connect);
// $pay = mysql_fetch_array($result_pay);

//팀장 커미션 수당테이블 조회
$query_manager = "select * from payment where ctr_no='$ctr_no' and name='$ctr[id_manager]' order by pay_date asc";
mysql_query("set names ust8", $connect);
$result_manager = mysql_query($query_manager, $connect);

//소개팀장 인센티브 수당테이블 조회
$query_incentive = "select * from payment where ctr_no='$ctr_no' and name='$ctr[id_incentive]'";
mysql_query("set names ust8", $connect);
$result_incentive = mysql_query($query_incentive, $connect);

//본부장 성과급 수당테이블 조회
$query_top = "select * from payment where ctr_no='$ctr_no' and name='$ctr[id_top]'";
mysql_query("set names ust8", $connect);
$result_top = mysql_query($query_top, $connect);

?>

<div class="wraper container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b class="panel-title text-danger" style="font-size:30px"><?= $state ?> 처리 </b>
            </div>
            <div class="panel-body">
                <h4><b>1.계약정보</h4>
                <table class="table table-bordered" style="font-size:12px;">
                    <tr>
                        <td class="td-left" style="padding-left:20px;">계약번호(신청종류)</td>
                        <td> 계약번호: <b class="text-danger"><font size="3">[ <?= $ctr_no ?> ]</font>&nbsp;&nbsp;&nbsp;&nbsp; ---><?= $state ?>
                                신청</b></td>
                    </tr>
                    <tr>
                        <td class="td-left " style="padding-left:20px;">계약자 성명</td>
                        <td> <?= $name ?> (<?=$ctr[user_id]?>)</td>
                    </tr>

                    <tr>
                        <td class="td-left " style="padding-left:20px;">계약 내용</td>
                        <td>
                            1.계약종류 : <b class="text-info"><?= $ctr[type] ?> </b><br>
                            2.계약금액 : <b class="text-info"><?= number_format($ctr[money]) ?> 원</b><br>
                            3.이자율(%): <b class="text-info"><?= $ctr[rate_cus] ?>(%) </b> <br>
                            4.관리팀장 : <b class="text-info"><?= $ctr[id_manager] ?> (<?= $ctr[rate_manager] ?> %) </b><br>
                            5.소개팀장 : <b class="text-info"><?= $ctr[id_incentive] ?>(<?= $ctr[rate_incentive] ?> %) </b><br>
                            6.본부장 : <b class="text-info"><?= $ctr[id_top] ?> (<?= $ctr[rate_top] ?> %) </b><br>
                            7.계약기간 : <b class="text-info"><?= $ctr[ctr_start] ?>~<?= $ctr[ctr_end] ?> </b><br>

                        </td>
                    </tr>

                    <tr>
                        <td class="td-left text-danger " style="padding-left:20px; width:20%">처리기준일*</td>
                        <td><?= $stop_date ?>
                    </tr>

                </table>
                <br>


                 <form action="./input_stop_page_post.php" method="POST">
<!-- ******************************중도해지 처리******************************************************************** -->
<? if($stop_state=='stop_cus'){?>
<!-- ******************************************** -->
<!-- 1. 고객정산 처리 -->
                <h4><b>2.고객 정산금 처리</h4>
                <table class="table-bordered" style="font-size:12px; width:100%; padding:10px; ">
                    <tr>
                        <td class="td-left" style="padding:5px 20px;">원금금액</td>
                        <td style="padding-left:20px"><b class="text-danger"> <?= number_format($ctr[money]) ?>원 </b></td>
                    </tr>
                    <tr>
                        <td class="td-left1 " style="padding:5px 20px;">지급된 금액*</td>
                        <td>
                            <table class="table-bordered text-center" style="width:100%; font-size:12px;">
                            <tr style="background-color:#abc9b9">
                               <td>지급회차</td>
                               <td>수령인</td>
                               <td>발생금액</td>
                               <td>예정일</td>
                               <td>지급일</td>
                               <td>지급금액</td>
                               <td>공제금액</td>
                               <td>공제방법</td>
                           </tr>
                    <?
                    $paytotal = 0;
                    $i = 1;
                    while ($pay = mysql_fetch_array($result_pay)) { ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $pay[name] ?></td>
                             <td><?= number_format($pay[amount]) ?></td>
                            <td><?= $pay[pay_date] ?></td>
                            <td><?if($pay[pay_date_out]){echo $pay[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                            <td><?if($pay[pay_state]=="지급완료"){
                                 $paytotal += $pay[amount];

                                 echo number_format($pay[amount]);}?></td>
                            <td class="text-danger">-<?if($pay[pay_state]=="지급완료"){ echo number_format($pay[amount]);}?></td>
                            <td class="text-danger">-<?if($pay[pay_state]=="지급완료"){ echo "원금에서차감";}?></td>
                        </tr>
                        <?
                        $i++;

                    }
                    $money_back = $ctr[money] - $paytotal;
                    ?>
                    <tr style="background-color:#ced2d0">
                        <td colspan=5> 지급된 이자합계(공제할 금액)</td>
                        <td><?= number_format($paytotal) ?></td>
                        <td class="text-danger">-<?= number_format($paytotal) ?></td>
                        <td></td>
                    </tr>
                </table>
                </td>
                </tr>
                 <tr style="background-color:#f2e6a7">
                    <td class="td-left bg-gray" style="padding:5px 20px; width:20%;">정산금액(공제금액)</td>

                    <td style="padding-left:20px"><b class="text-danger"> <?= number_format($money_back) ?>원   &nbsp; (공제금액:<?=number_format($paytotal)?>)</b></td>
                </tr>
                </table>

<br>

<!-- 2. 팀장 커미션 정산 -->
                <h4><b>3.팀장 커미션수당 정산처리</h4>
                     <table class="table-bordered" style="font-size:12px; width:100%; padding:10px; ">
                         <tr>
                             <td class="td-left1 " style="padding:5px 20px;">지급된 금액*</td>
                             <td>
                                 <table class="table-bordered text-center" style="width:100%; font-size:12px;">
                                 <tr style="background-color:#abc9b9">
                                    <td>지급회차</td>
                                    <td>수령인</td>
                                    <td>발생금액</td>
                                    <td>예정일</td>
                                    <td>지급일</td>
                                    <td>지급금액</td>
                                    <td>공제금액</td>
                                    <td>공제방법</td>
                                </tr>
                         <?
                         $paytotal_t = 0;
                         $i = 1;
                         while ($pay_t = mysql_fetch_array($result_manager)) { ?>
                             <tr>
                                 <td><?= $i ?></td>
                                 <td><?= $pay_t[name] ?></td>
                                 <td><?= number_format($pay_t[amount]) ?></td>
                                 <td><?= $pay_t[pay_date] ?></td>
                                 <td><?if($pay_t[pay_date_out]){echo $pay_t[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                                 <td><?if($pay_t[pay_state]=="지급완료"){
                                      $paytotal_t += $pay_t[amount]; //지급완료된 것만 커미션 누적
                                      echo number_format($pay_t[amount]);}?></td>
                                 <td class="text-danger">-<?if($pay_t[pay_state]=="지급완료"){ echo number_format($pay_t[amount]);}?></td>
                                   <td class="text-danger">-<?if($pay_t[pay_state]=="지급완료"){ echo "추후수당차감";}?></td>
                             </tr>
                             <?
                             $i++;
                         }
                         ?>
                         <tr style="background-color:#bbc1bd">
                             <td colspan=5> 공제 합계</td>
                             <td><?= number_format($paytotal_t) ?></td>
                             <td class="text-danger">-<?= number_format($paytotal_t) ?></td>
                             <td></td>
                         </tr>
                     </table>
                     </td>
                     </tr>
                     <tr style="background-color:skyblue;">
                         <td class="td-left bg-gray" style="padding:5px 20px; width:20%;">공제금액(반환금액)</td>
                         <td style="padding-left:20px"><b class="text-danger"> <?= number_format($paytotal_t)?> 원  (추후 지급액에서 공제)</b></td>
                     </tr>
                     </table>

<br>
                <h4><b>4.소개팀장 인센티브/ 본부장성과급 정산처리</b></h4>
                     <table class="table-bordered" style="font-size:12px; width:100%; padding:10px; ">
                          <tr class="text-center" style="background-color:#abc9b9">
                              <td>구분</td>
                              <td>수령인</td>
                              <td>예정일</td>
                              <td>발생금액</td>
                              <td>지급일</td>
                              <td>지급금액</td>
                              <td>공제금액</td>
                              <td>공제방법</td>
                          </tr>

                           <tr class="text-center" style="background-color:#f2e6a7">
                             <td class="td-left1 text-left bg-gray" style="padding:5px 20px; width:20%">4.소개팀장(인센티브)</td>
                         <?
                         $pay_i = mysql_fetch_array($result_incentive);
                                 $amount_i=number_format($pay_i[amount]);?>
                                 <td><?= $pay_i[name] ?></td>
                                 <td><?= $pay_i[pay_date] ?></td>
                                 <td><?= $amount_i ?></td>
                                 <td><?if($pay_i[pay_date_out]){echo $pay_i[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                                 <td><?if($pay_i[pay_state]=="지급완료"){
                                 echo $paytotal_i=$pay_i[amount];}?></td>
                                 <td class="text-danger">-<?if($pay_i[pay_state]=="지급완료"){ echo $amount_i;}?></td>
                                 <td class="text-danger"><?if($pay_i[pay_state]=="지급완료"){ echo "추후공제";}?></td>
                             </tr>


                             <tr class="text-center">
                                <td class="td-left1 text-left bg-gray" style="padding:5px 20px;">5.본부장(성과급)</td>
                            <?
                            $pay_top = mysql_fetch_array($result_top);
                                    $amount_top=number_format($pay_top[amount]);?>
                                    <td><?= $pay_top[name] ?></td>
                                    <td><?= $pay_top[pay_date] ?></td>
                                    <td><?= $amount_top ?></td>
                                    <td><?if($pay_top[pay_date_out]){echo $pay_top[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                                    <td><?if($pay_top[pay_state]=="지급완료"){
                                         echo $paytoatal_top=$pay_top[amount];}?></td>
                                    <td class="text-danger">-<?if($pay_top[pay_state]=="지급완료"){ echo $amount_top;}?></td>
                                    <td class="text-danger"><?if($pay_top[pay_state]=="지급완료"){ echo "추후수당공제";}?></td>
                                </tr>
                     </table>

                     <br>

<!-- 처리실행하기 Post로 넘겨서 처리하기 -->
                                     <h4><b>5.<?= $state ?> 처리 실행하기</b></h4>
                                     <div class="bg-danger text-inverse" style="padding:10px;">
                                        실행하시면 계약내용은 [중도해지] 처리되고, 해당계약으로 발생된 모든 수당은 초기화되며, <br>기지급된 수수료는 차감 또는 향후 지급될 수수료에서 정해진 계약대로 공제됩니다. <br>
                                   </div>
                                   <br>
                                     <div style="font-size:12px;">
                                        1. 고객 공제후 지급액 : <b class="text-danger"><?= number_format($money_back)?>원   &nbsp; (공제금액:<?=number_format($paytotal)?>원)</b><br>
                                        2. 팀장 커미션 공제액 :<b class="text-danger"><?=number_format($paytotal_t)?>원</b><br>
                                        3. 소개팀장 인센티브 공제액 :<b class="text-danger"><?=number_format($paytotal_i)?> 원</b><br>
                                        4. 본부장 공제액 : <b class="text-danger"><?=$paytoatal_top?> 원</b><br>
                                        * 총 공제액 : <b class="text-danger"><?=number_format($paytotal+$paytotal_t+$paytotal_i+$paytoatal_top)?> 원</b><br>
                                     </div>

                                     <!-- 고객차감,커미션차감,인센티브차감,본부장차감 -->
                                     <input type="hidden" name="minus_c" value="<?=$paytotal?>">
                                     <input type="hidden" name="minus_t" value="<?=$paytotal_t?>">
                                     <input type="hidden" name="minus_i" value="<?=$paytotal_i?>">
                                     <input type="hidden" name="minus_top" value="<?=$paytoatal_top?>">



 <?}

 if($stop_state=='stop_com'){?>
<!-- ******************************중도상환 처리******************************************************************** -->

<!-- 1. 고객정산 처리 -->
                <h4><b>2.고객 정산금 처리</h4>
                <table class="table-bordered" style="font-size:12px; width:100%; padding:10px; ">
                    <tr>
                        <td class="td-left" style="padding:5px 20px;">원금금액</td>
                        <td style="padding-left:20px"><b class="text-danger"> <?= number_format($ctr[money]) ?>원 </b></td>
                    </tr>
                    <tr>
                        <td class="td-left1 " style="padding:5px 20px;">지급된 금액*</td>
                        <td>
                            <table class="table-bordered text-center" style="width:100%; font-size:12px;">
                            <tr style="background-color:#abc9b9">
                               <td>지급회차</td>
                               <td>수령인</td>
                               <td>발생금액</td>
                               <td>예정일</td>
                               <td>지급일</td>
                               <td>지급금액</td>
                               <td>지급방법</td>
                           </tr>
                    <?
                    $paytotal = 0;
                    $i = 1;
                    while ($pay = mysql_fetch_array($result_pay)) { ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $pay[name] ?></td>
                            <td><?= number_format($pay[amount]) ?></td>
                            <td><?= $pay[pay_date] ?></td>
                            <td><?if($pay[pay_date_out]){echo $pay[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                            <td  class="text-danger"><?if($pay[pay_state]=="지급완료"){
                                 $paytotal += $pay[amount];
                                 echo number_format($pay[amount]);}?></td>
                            <td class="text-info"><?if($pay[pay_state]=="지급완료"){ echo "기지급완료";}?></td>                        </tr>
                        <?
                        $i++;
                        $amount_c= $pay[amount];
                    }
                    $money_final = $ctr[money] + $amount_c;
                    ?>
                    <tr style="background-color:#ced2d0">
                        <td colspan=5> 이자지급 합계</td>
                        <td  class="text-danger"><?= number_format($paytotal) ?></td>
                        <td class="text-danger">+ <?=number_format($amount_c)?>(마지막달 이자지급추가)</td>
                        <td></td>
                    </tr>
                </table>
                </td>
                </tr>
            <tr style="background-color:#f2e6a7">
                    <td class="td-left bg-gray" style="padding:5px 20px; width:20%;">정산금액(지급금액)</td>

                    <td style="padding-left:20px"><b class="text-danger"> <?= number_format($money_final) ?>원   &nbsp;
                         = 원금(<?=number_format($ctr[money])?>) + 마지막달 이자 (<?=number_format($amount_c)?>) </b></td>
                </tr>
                </table>

<br>

<!-- 2. 팀장 커미션 정산 -->
                <h4><b>3.팀장 커미션수당 정산처리</h4>
                     <table class="table-bordered" style="font-size:12px; width:100%; padding:10px; ">
                         <tr>
                             <td class="td-left1 " style="padding:5px 20px;">지급된 금액*</td>
                             <td>
                                 <table class="table-bordered text-center" style="width:100%; font-size:12px;">
                                      <tr style="background-color:#abc9b9">
                                         <td>지급회차</td>
                                         <td>수령인</td>
                                         <td>발생금액</td>
                                         <td>예정일</td>
                                         <td>지급일</td>
                                         <td>지급금액</td>
                                         <td>지급방법</td>
                                     </tr>
                         <?
                         $paytotal_t = 0;
                         $i = 1;
                         while ($pay_t = mysql_fetch_array($result_manager)) { ?>
                             <tr>
                                 <td><?= $i ?></td>
                                 <td><?= $pay_t[name] ?></td>
                                 <td><?= number_format($pay_t[amount]) ?></td>
                                 <td><?= $pay_t[pay_date] ?></td>
                                 <td><?if($pay_t[pay_date_out]){echo $pay_t[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                                 <td><?if($pay_t[pay_state]=="지급완료"){
                                      $paytotal_t += $pay_t[amount]; //커미션 누적
                                      echo number_format($pay_t[amount]);}?></td>
                                   <td class="text-danger"><?if($pay_t[pay_state]=="지급완료"){ echo "기지급완료";}?></td>
                             </tr>
                             <?
                             $i++;
                             $amount_t=$pay_t[amount];
                         }
                         ?>
                         <tr style="background-color:#bbc1bd">
                             <td colspan=5> 커미션 지급 합계</td>
                             <td class="text-danger"><?= number_format($paytotal_t) ?></td>
                             <td class="text-danger">+ <?= number_format($amount_t)?> (마지막달 지급추가)</td>
                             <td></td>
                         </tr>
                     </table>
                     </td>
                     </tr>
                     <tr style="background-color:skyblue;">
                         <td class="td-left bg-gray" style="padding:5px 20px; width:20%;">지급금액</td>
                         <td style="padding-left:20px"><b class="text-danger"> 마지막달 분 <?= number_format($amount_t)?> 원 추가 지급후 이후 지급종료</b></td>
                     </tr>
                     </table>

<br>
                <h4><b>4.소개팀장 인센티브/ 본부장성과급 정산처리</b></h4>
                     <table class="table-bordered" style="font-size:12px; width:100%; padding:10px; ">
                          <tr class="text-center" style="background-color:#abc9b9">
                              <td>구분</td>
                              <td>수령인</td>
                              <td>예정일</td>
                              <td>발생금액</td>
                              <td>지급일</td>
                              <td>지급금액</td>
                              <td>지급방법</td>
                          </tr>

                           <tr class="text-center" style="background-color:#f2e6a7">
                             <td class="td-left1 text-left bg-gray" style="padding:5px 20px; width:20%">4.소개팀장(인센티브)</td>
                         <?
                         $pay_i = mysql_fetch_array($result_incentive);
                                 $amount_i=number_format($pay_i[amount]);?>
                                 <td><?= $pay_i[name] ?></td>
                                 <td><?= $pay_i[pay_date] ?></td>
                                 <td><?= $amount_i ?></td>
                                 <td><?if($pay_i[pay_date_out]){echo $pay_i[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                                 <td><?if($pay_i[pay_state]=="지급완료"){
                                 echo number_format($paytotal_i=$pay_i[amount]);}?></td>
                                 <td class="text-danger"><?if($pay_i[pay_state]=="지급완료"){ echo "기지급완료";}?></td>
                             </tr>


                             <tr class="text-center">
                                <td class="td-left1 text-left bg-gray" style="padding:5px 20px;">5.본부장(성과급)</td>
                            <?
                            $pay_top = mysql_fetch_array($result_top);
                                    $amount_top=number_format($pay_top[amount]);?>
                                    <td><?= $pay_top[name] ?></td>
                                    <td><?= $pay_top[pay_date] ?></td>
                                    <td><?= $amount_top ?></td>
                                    <td><?if($pay_top[pay_date_out]){echo $pay_top[pay_date_out];}else{echo "<font color='blue' size='1.6'>-->이후 삭제</font>";}?></td>
                                    <td><?if($pay_top[pay_state]=="지급완료"){
                                         echo $paytoatal_top=$pay_top[amount];}?></td>
                                    <td class="text-danger"><?if($pay_top[pay_state]=="지급완료"){ echo "추후공제";}?></td>
                                </tr>
                     </table>

                     <br>

<!-- 처리실행하기 Post로 넘겨서 처리하기 -->
                                     <h4><b>5.<?= $state ?> 처리 실행하기</b></h4>

       <div class="bg-success text-inverse" style="padding:10px;">
       실행하시면 계약내용은 [중도상환] 처리되고, 해당계약으로 발생된 수수료 중 상환일 이후 발생분만 삭제되며, <br>상환일 이전 기지급된 수수료는 공제없이 지급됩니다. <br>
  </div>
  <br>
  <div style="font-size:12px;">
       1. 고객 (중도상환) 총 지급액 : <b class="text-danger"><?= number_format($money_final)?>원 (원금+마지막이자)</b><br>
       -고객이자 총지급액 :<b class="text-danger"><?=number_format($cus=$paytotal+$amount_c)?>원</b><br>
       -팀장 커미션 총 지급액 :<b class="text-danger"><?=number_format($team=$paytotal_t+$amount_t)?>원</b><br>
       -소개팀장 인센티브 총 지급액 :<b class="text-danger"><?=number_format($paytotal_i)?> 원</b><br>
       -본부장 총 지급액 : <b class="text-danger"><?=$paytoatal_top?> 원</b><br>
       2. 총 지급된 이자 및 수수료 지급액 : <b class="text-danger"> <?=number_format($cus+$team+$paytotal_i+$paytoatal_top)?> 원</b><br>
     </div>

     <!-- 중도상환후 정산결과 : 고객이자,커미션지급,인센티브지급,본부장지급 -->
     <input type="hidden" name="minus_c" value="<?=$cus?>">
     <input type="hidden" name="minus_t" value="<?=$team?>">
     <input type="hidden" name="minus_i" value="<?=$paytotal_i?>">
     <input type="hidden" name="minus_top" value="<?=$paytoatal_top?>">



 <!-- ******************************************** -->
<?}?>


                      <input type="hidden" name="stop_state" value="<?=$stop_state?>">
                      <input type="hidden" name="ctr_no" value="<?=$ctr_no?>">
                      <input type="hidden" name="name" value="<?=$name?>">
                      <input type="hidden" name="id_manager" value="<?=$ctr[id_manager]?>">
                      <input type="hidden" name="id_incentive" value="<?=$ctr[id_incentive]?>">
                      <input type="hidden" name="id_top" value="<?=$ctr[id_top]?>">

                      <input type="hidden" name="stop_date" value="<?=$stop_date?>"><br>
                      *처리전 메모해 놓을 것<br> <textarea name="memo_x" rows="2" cols="100" value="" placeholder="실행전<페이지 내용전체>를 드래그한후 복사해서 이곳에 붙이시오"></textarea>


                 <div class="text-center" style="padding-top:20px; border-top:2px solid black;">
                      <div class="btn btn-inverse" onclick="window.print();">인쇄하기</div> &nbsp;&nbsp;
                     <input type="submit"  class="btn btn-danger" value="<?=$state?> &nbsp; &nbsp;>>처리 실행하기 "  onclick="return confirm('<?=$state?> 확인내용을 최종 처리하시겠습니까? \n실행후 취소할 수 없습니다 ')" />


                 </div>
                </form>

</div>
</div>
</div>
</div>
</div>


<!-- footer -->
<? include('../_footer.php'); ?>

</html>

<? include('./modal_script.php'); //javascript 모달창 출력 ?>
