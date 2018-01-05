<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

//if($member[user_id]!="admin")Error("관리자 메뉴입니다.");
include('../lib/lib.php'); //시간함수외
include('../_header.php');

$Search_text=$_GET[Search_text];
$dy=$_GET[Search_year];
$dm=$_GET[Search_month];

// echo $dtitle= $dy."-".$dm;
// $dtitle;//조회 검색어
?>

<body>

    <div class="p-y-0 " style="background-color:#;">
        <div class="wraper container">

            <!-- Detail list-->

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">



                                                 <!-- 검색 항목-->
                                                 <?
                                                 $Search_text=$_GET[Search_text];

                                                 $dy=$_GET[Search_year];
                                                 $dm=$_GET[Search_month];

                                                 $_page=$_GET[_page];
                                                 $href=$_GET[href];

                                                 $view_total = 3000; //한 페이지에 30(설정할것)개 게시글이 보인다.
                                                 $href = "&Search_year=$dy&Search_month=$dm&Search_text=$Search_text";

                                                 if(!$_page)($_page=1); //페이지 번호가 지정이 안되었을 경우
                                                 $page= ($_page-1)*$view_total;
                                                 ?>


                        <!-----------게시판 출력-------------------->

                        <div class="panel-heading">
                            <b class="panel-title text-danger" style="font-size:30px">금월 수당지급 정산내역</b>
                        </div>

                        <div class="row">
                            <div class="col-md-12" style="font-size:13px; margin:0px 0px 10px 0px;">
                                <div class="p-y-1 p-x-1 td-left1"><b>조회자 성명 :</b>
                                <b class="text-purple" style="font-size:14px"><?=$member[name]?>(<?=$member[user_id]?>)</b> </div>
                            </div>
                        </div>

                                 <!--list-table-->
                       <div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">

 <div class="text-left">   <!---게시물 검색--->

 <form action='<?=$PHP_SELE?>'>
    <td class="text-center td-left" height='20' bgcolor='#4d4a4c' align='left'>
         <label class="btn btn-default">수당지급 월 조회 &nbsp;
         <select name='Search_year' class="input-sm">
             <option value='2017'>2017년
             <option value='2018'>2018년
             <option value='2019'>2019년
             <option value='2020'>2020년
         </select>

         <select name='Search_month' class="input-sm">
             <option value=''>월선택
             <option value='01'>1월
             <option value='02'>2월
             <option value='03'>3월
             <option value='04'>4월
             <option value='05'>5월
             <option value='06'>6월
             <option value='07'>7월
             <option value='08'>8월
             <option value='09'>9월
             <option value='10'>10월
             <option value='11'>11월
             <option value='12'>12월
         </select>
      상세조회
         <input type='text' name='Search_text' size='25' class="input-sm" placeholder="해당날짜/성명등.. ">
         <input class="btn btn-inverse btn-sm" type='submit' value='Search'>
 </form>
</div>

<?
if(!$dy or !$dm){$dy=date("Y");
$dm=date("m");}
?>

<b><font color='red' size='4.5'><?=$dy?></font></b>년  <font color='red' size='4.5'><b><?=$dm?>월</b></font> 수수료 정산 내역입니다.<br>

<div class="table-responsive">
                              <table class="table-bordered" style="font-size:11px; line-height:10px; width:100%;">
                                                                <thead class="text-white" style="line-height:30px; background-color:#706d6b">

                                                                    <tr>
                                                                        <th class="text-center">NO</th>
                                                                        <th class="text-center">지급대상자</th>
                                                                        <th class="text-center">수수료 지급종류</th>
                                                                        <th class="text-center">지급예정일</th>
                                                                        <th class="text-center">수수료금액</th>
                                                                        <th class="text-center">발생회차</th>
                                                                        <th class="text-center bg-gray">발생계약확인</th>
                                                                        <th class="text-center bg-gray">계약자/팀장</th>
                                                                        <th class="text-center bg-rw">지급일</th>
                                                                        <th class="text-center bg-rw">지급여부</th>
                                                                         <th class="text-center bg-rw">공제여부</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>



<?

$query_mem= "select * from member where id='mem' order by name asc";
mysql_query("set names ust8", $connect);
$result_mem= mysql_query($query_mem, $connect);

 //1. 회원정보테이블을 조회해서 하나씩 사람들을 끌어내..
while($member = mysql_fetch_array($result_mem)){

     $where="(pay_name like '%$Search_text%' or pay_date like '%$Search_text%' or name like '%$Search_text%'
                  or amount like '%$Search_text%' or ctr_no like '%$Search_text%' or pay_state like '%$Search_text%'
                  or period like '%$Search_text%' or ctr_name like '%$Search_text%' or ctr_name like '%$Search_text%'
           )"; //검색조건

     if($Search_text){
      $query ="select * from payment where $where and user_id='$member[user_id]' order by pay_date asc "; //같은 날에 있는 것들만 이름순으로 소팅이 되는데
     }else{
     $query ="select * from payment where user_id='$member[user_id]' order by pay_date asc "; //같은 날에 있는 것들만 이름순으로 소팅이 되는데
           }

     $result=mysql_query($query,$connect);

     $cnt=1; //각줄번호
     $totals=0; //줄누적
     $spay=0; //각 수당합
     $sum=0;  //수당누적

$cnt_old=0;   //연장건수
$spay_old=0; //각 연장수당의 합
$sum_old=0; //연장수당합 전체누적

     while($data= mysql_fetch_array($result)){   //그 사람 수당을 불러와서 날짜 조건에 맞는자 체크한다.
 // 그 사람의 1일부터 31일까지 그달 수당이 있는 것을 찾아낸다.
     $dd=getMonthEndDay($dy, $dm); //마지막 날짜를 구해서
    for ($i=1; $i <$dd+1; $i++) {
        $di=computeMonth($dy,$dm,$i,0);  //매일 날짜로 변환 비교대상
          if($data[pay_date]==$di) { ?>

               <tr style="background-color:#fff">
                  <td class="text-center"><?=$cnt?></td>
                  <td class="text-center"><?=$data[name]?></td>
                  <td class="text-center"><?=$data[pay_name]?></td>
                  <td class="text-center"><?=$data[pay_date]?></td>
                  <td class="text-center"><?=number_format($data[amount])?>원</td>
                  <td class="text-center"><?=$data[period]?></td>

                       <? //계약서 조회 모달 불러오기위해
                       $query_r= "select * from contract where ctr_no='$data[ctr_no]' ";
                       mysql_query("set names ust8", $connect);
                       $result_r= mysql_query($query_r, $connect);
                       $dr= mysql_fetch_array($result_r); ?>

               <td  class="text-center"><input type="button" name="view1"  style="font-size:9px;" value="<?=$data[ctr_no]?>" id="<?=$dr[no]?>" class="btn btn-gray btn-xs view_data1" /> <font color="red">/<?=$dr[newtype]?></font></td>

                  <td class="text-center"><?=$dr[name]?>/<?=$dr[id_manager]?></td>
                  <td class="text-center"><?=$data[pay_date_out]?></td>
                  <td class="text-center">
                      <?if($data[pay_state]=="지급완료"){?> <span class="badge badge-danger">지급완료</span> <?} elseif($data[pay_state]=="공제"){?> <span class="badge badge-warning">공제</span> <? } else{?> <span class="text-info"><?=$data[pay_state]?></span> <?}?>
                 </td>
                 <td  class="text-danger" style="font-size:11px; padding:0px;">
                      <?=$data[banpum_ctr]?><br><?=$data[banpum_process]?><br>
                      <?=$data[gongje_process]?>
                 </td>
             </tr>

<?   $cnt++; //줄을 증가시키고
     $spay+=$data[amount]; //합을 누적시키고
     if($dr[newtype]=="연장"){$cnt_old++; $spay_old+=$data[amount];} //연장수당 계산
} //여기까지 if조건에 맞는 한줄 찌고

}//for문의 끝 1~31일까지 돌리고



}//while문 수당테이블 끝
     $cnt1=$cnt-1;  //+1증가된 건수라 실제 건수로 할려면 -1
     $totals1+=$cnt1;  //줄 건수를 누적하고,

     $sum1+=$spay;   //각자 수당합도 누적하고.
     $totals2+=$cnt_old;  //연장-줄 건수를 누적하고
     $sum2+=$spay_old;   //연장-각자 수당합도 누적하고.
?>

<?
if($spay>0) {
       ?>
<tr class="td-left text-center text-danger" style="line-height:15px;">
<td></td>
<td style="font-size:11px;"><?=$member[name]?></td>
<td class="text-right" style="font-size:10px; color:black;"></td><td style="font-size:11px">개인소계(<?=$cnt1?>건):</td>
<td class="text-danger text-center"><?=number_format($spay)?></td>
<td class="text-right" style="font-size:10px; color:black;">1.연장소계:(<?=$cnt_old?>건)</td>
<td class="text-left" style="font-size:11px; color:blue;">=<?=number_format($spay_old)?></td>
<td class="text-right" style="font-size:10px; color:black;">2.신규계약:(<?=($cnt1-$cnt_old)?>건)</td>
<td class="text-left" style="font-size:11px; color:green;">=<?=number_format($spay-$spay_old)?></td>
<td colspan="10"></td>
</tr>

<?

}

}//while문 회원테이블 끝
$totals+=$totals1;   //전체 건수
$sum+=$sum1; //전체 보너스합

$totals_old+=$totals2;   //연장-전체 건수
$sum_old+=$sum2; //연장-전체 보너스합
?>


<tr class="td-left2" style="font-size:12px; line-height:30px;">
    <td class="text-center"></td>
    <td class="text-center">총 발생건수 :</td>
    <td class="text-center text-danger"><?=$totals?>건</td>
    <td class="text-right"><?if($member[level]=="C"){?>고객이자 합계<?}else{?>지급 발생총액<?}?></td>
    <td class="text-center text-danger"><?=number_format($sum)?>원</td>

    <td class="text-right" style="font-size:11px; color:black;">연장합계:(<?=$totals_old?>건)</td>
    <td class="text-left" style="font-size:12px; color:red;">=<?=number_format($sum_old)?></td>
    <td class="text-right" style="font-size:11px; color:black;">신규합계:(<?=($totals-$totals_old)?>건)</td>
    <td class="text-left" style="font-size:12px; color:red;">=<?=number_format($sum-$sum_old)?></td>


    <td colspan="10" class="text-center"></td>

</tr>


<tr style="font-size:14px; line-height:30px;">
    <td colspan="15" class="text-center"><?include ('../member/list_page.php');?></td>
</tr>


</tbody>
</table>

</div>
</div>
</div>

</div>
</div>
</div>

</div>
</div>
</body>


<!-- footer -->
<? include('../_footer.php'); ?>

</html>

<? include('../member/modal_script.php'); //javascript 모달창 출력 ?>
