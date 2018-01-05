<? header("content_type:text/html; charset=UTF-8");

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자 메뉴입니다.");
include('../lib/lib.php'); //시간,날짜변환외
include('../_header.php');

$no=$_GET[no];
$id=$_GET[id];

//////계약정보, 월대차정보///////
$queryp= "select * from contract where no='$no'";
mysql_query("set names ust8",$connect);
$resultp= mysql_query($queryp, $connect);
$modal= mysql_fetch_array($resultp);

 ?>


<body>
    <!--My Info-->
    <div class="p-y-0 " style="background-color:#">
        <div class="wraper container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-title text-info"> <font color='red'><?=$modal[name]?>(<?=$modal[user_id]?>)</font> [ 월대차 납입현황 입력 ]</h1>
                        </div>
                        <div class="panel-body">


                        <b>계약번호 :</b> <b class="text-danger"><?=$modal[ctr_no]?></b><br />
                        <b>계약종류 :</b> <b class="text-danger"><?=$modal[type]?></b>  &nbsp; &nbsp; &nbsp;
                        <b>계약자명 :</b> <b class="text-info"><?=$modal[name]?>(<?=$modal[user_id]?>)</b> <br/>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="font-size:11px;">
                                <thead class="text-white" style="background-color:#808080">

                                    <tr>
                                        <th class="text-center">납부회차</th>
                                        <th class="text-center">납부예정일</th>
                                        <th class="text-center">납부금액</th>
                                        <th class="text-center">납입한 날</th>
                                        <th class="text-center">납입한 금액</th>
                                        <th class="text-center">납부여부</th>

                                    </tr>
                                </thead>

                                <tbody>

                                     <?
                                     $data=$modal[ctr_start];
                                     $date_y=substr($data,0,4);
                                     $date_m=substr($data,5,2);
                                     $date_d=substr($data,8,2);

                                     $moneym=$modal[money]/12 ;  //월납부금
                                   ?>

<form action="../admin/input_month_post.php" method="post">

                                   <input type="hidden" name="no" value="<?=$no?>">
                                   <input type="hidden" name="id" value="<?=$id?>">

                                   <?
                                   for ($i=1; $i < 13; $i++) {  ?>
                                     <tr>
                                      <td class="text-center"><?=$i?>회차</td>
                                      <?$dt=computeMonth($date_y,$date_m,$date_d,$i-1); $m="m".$i; $mm="mm".$i;?>
                                      <td class="text-center"><?=$dt?></td>
                                      <td class="text-center"><?=number_format($moneym)?></td>
                                      <?if(!$modal[$m] and !$modal[$mm]){?>
                                      <td class="text-center"><input type="date" name="<?=$m?>" value="" size="10px" /></td>
                                      <td class="text-center"><input type="text" name="<?=$mm?>" value="" size="10px" class="text-danger"/></td>
                                      <td class="text-center text-info">입금예정</td>
                                      <?}else{?>
                                      <td class="text-center text-danger"><?=$modal[$m]?><input type="hidden" name="<?=$m?>" value="<?=$modal[$m]?>" size="10px"/></td>
                                      <td class="text-center text-danger"><?=number_format($modal[$mm])?><input type="hidden" name="<?=$mm?>" value="<?=$modal[$mm]?>" size="10px" /></td>
                                      <td class="text-center"><span class="badge badge-danger">납입완료</span></td>
                                      <?}?>
                                    </tr>
                                    <?} //for문 종료?>

                                    <? $sum_mm=0; $mr=0;  //납입금 합계, 납입회차 구하기
                                    for ($m=1; $m<13 ; $m++) {
                                         $mmth="mm".$m;    //매월 적립한 금액-납입한 달 속성
                                      $sum_mm+=$modal[$mmth];  //적립금 누적계산
                                      if($modal[$mmth]){$mr++;};
                                      }
                                    ?>

                               <input type="hidden" name="sum_mm" value="<?=$sum_mm?>">
<? $query="update contract set sum_mm='$sum_mm' where no='$no'";
   mysql_query($query,$connect);?>


                                    <!--total cell-->
                                    <tr class="text-white" style="font-size:11px; background-color:#5f929d">
                                       <td class="text-center"></td>
                                       <td class="text-center">*납부할 총액 =</td>
                                       <td class="text-left"><?=number_format($modal[money])?></td>
                                       <td class="text-right text-warning">>>현재누적금액 =</td>
                                       <td class="text-center text-warning"><?=number_format($sum_mm)?>원</td>
                                       <td class="text-center"><?=$mr?>회차/12</td>
                                    </tr>

                         </tbody>

                         </table>
                        </div>

                        <div class="text-center m-y-1">
                        <input type="submit" value="DB저장입력>>" class="btn btn-danger" onclick="return confirm('입력한 값으로 수정하시겠습니까?')">
                         &nbsp;&nbsp;
                         <input type="button" class="btn btn-default" value="월대차현황" onclick="location.href='../admin/input_month.php'">
                        </div>
                   </form>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- footer -->
<? include('../_footer.php'); ?>

</html>

<!--END--Footer---------------->
