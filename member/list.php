<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if(!$member[user_id])Error_member();
include('../lib/lib.php'); //시간함수외
?>

<?
$select=$_GET[select];
$_page=$_GET[_page];
$href=$_GET[href];

$view_total = 10; //한 페이지에 30(설정할것)개 게시글이 보인다.
$href = "&Search_mode=$Search_mode&Search_text=$Search_text";

if(!$_page)($_page=1); //페이지 번호가 지정이 안되었을 경우
$page= ($_page-1)*$view_total;
?>


<!-- Top menu -->
<? include('../_header.php');
   include('../admin/dashboard.php');//dashboard
?>


<body>
     <div class="p-y-0 " style="background-color:#;">
         <div class="wraper container">
              <!-- page title row -->
              <div class="page-title">

                     <h3 class="title text-danger"><b>나의 계약현황 (MY CONTRACT)</b></h3>

              </div> <!-- End row -->


              <div class="row">
                <div class="col-md-12" style="font-size:12px; margin:0px 0px 10px 0px;">
                     <div class="p-y-1 p-x-1 td-left2"><b><?=$member[name]?>(<?=$member[user_id]?>)</b>
                     <b class="text-purple" style="font-size:14px">  / ( <?=date("Y-m-d")?>)</b> </div>
                </div>
              </div>


<!-- //고객용 노출화면 ------------------------->
<?if($member[level]=="C") {?>
             <!-- dashboard -->
             <div class="row">
                <div class="col-md-3 col-sm-6">
                     <div class="widget-panel widget-style-1 bg-primary">
                        <i class="fa  fa-star-half-full"></i>
                        <h5 class="m-0 counter text-white">나의 총계약건수</h5>
                        <b style="font-size:20px;" class="text-warning"><?=number_format($member[total_ctr])?>건</b>
                     </div>
                </div>
                <div class="col-md-3 col-sm-6">
                     <div class="widget-panel widget-style-1 bg-success">
                        <i class="fa  fa-cube"></i>
                        <h5 class="m-0 counter text-white">총 약정금액(월대차 제외)</h5>
                        <b style="font-size:20px;" class="text-warning"><?=number_format($member[total_money])?>원</b>
                     </div>
                </div>

                <div class="col-md-3 col-sm-6">
                     <div class="widget-panel widget-style-1 bg-gray">
                        <i class="fa  fa-usd"></i>
                        <h5 class="m-0 counter text-white"><?if($member[level]=="C"){?>고객 발생이자 합계<?}else{?>수수료 발생총액<?}?></h5>
                        <b style="font-size:20px;" class="text-warning"><?=number_format($member[total_bonus])?>원</b>
                     </div>
                </div>
                <div class="col-md-3 col-sm-6">
                     <div class="widget-panel widget-style-1 bg-danger">
                        <i class="fa  fa-usd"></i>
                        <h5 class="m-0 counter text-white">만기예정 계약확인(<?=$d?>개월내)</h5>
                        <b style="font-size:20px;" class="text-warning"><?=number_format($total_dday)?>건</b>
                     </div>
                </div>
             </div>


             <!-- customer box-->
             <div class="row">
                 <div class="col-md-12">
                     <div class="panel panel-default">

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                 <div class="table-responsive">
                                     <table class="table table-bordered" style="font-size:12px;">
                                         <tr>
                                             <td class="td-left" style="width:30%;">계약자 ID</td>
                                             <td><b><?=$member[name]?>(<?=$member[user_id]?>)</b></td>
                                         </tr>

                                         <tr>
                                             <td class="td-left">계약자 주 소</td>
                                             <td><?=$member[addr_1]?> <?=$member[addr_2]?></td>
                                         </tr>
                                         <tr>
                                             <td class="td-left">연락처</td>
                                             <td><?=$member[tel]?></td>
                                         </tr>

                                         <!--팀장조회--->
                                         <?
                                         $qt= "select * from member where name='$member[id_manager]' ";
                                         mysql_query("set names ust8",$connect);
                                         $rt= mysql_query($qt, $connect);
                                         $mt= mysql_fetch_array($rt);
                                         ?>
                                         <tr>
                                             <? if($member[level]=='C'){ ?>
                                             <td class="td-left">담당 관리자</td>
                                             <td>
                                                 <?=$member[id_manager]?> (<?=$mt[tel]?> )
                                             </td>
                                             <?} else {?>
                                             <td class="td-left">본인 소개팀장</td>
                                             <td>
                                                 <?=$member[id_incentive]?>
                                             </td>
                                             <?}?>

                                         </tr>
                                     </table>
                                 </div>
                            </div>
                        </div>

                        </br></br>
       <!-- dashboard끝 -->

<!-- //팀장,본부장 노출화면 시작------------------------->
<?}else{?>
     <!-- dashboard -->
     <div class="row">
        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-inverse">
                 <i class="fa  fa-star-half-full"></i>
                 <h5 class="m-0 counter text-white">조회자 성명</h5>
                 <b style="font-size:20px;" class="text-warning"><?=$member[name]?>(<?=$member[user_id]?>)</b>
             </div>
        </div>


        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-inverse">
                 <i class="fa  fa-cube"></i>
                 <h5 class="m-0 counter text-white">현재직급</h5>
                 <b style="font-size:20px;" class="text-warning">
                      <?if($member[level]=="B"){echo "팀장";}
                 if($member[level]=="L"){echo "회사";}  ?></b>
             </div>
        </div>

        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-purple">
                 <i class="fa  fa-usd"></i>
                 <h5 class="m-0 counter text-white">전체관리 고객수(하위팀장포함)</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($total_mem)?>명</b>
             </div>
        </div>
        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-purple">
                 <i class="fa  fa-usd"></i>
                 <h5 class="m-0 counter text-white">직접추천 회원수</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($total_direct)?>명</b>
             </div>
        </div>

        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-primary">
                 <i class="fa  fa-star-half-full"></i>
                 <h5 class="m-0 counter text-white">나의 총계약건수</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($member[total_ctr])?>건</b>
             </div>
        </div>

        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-primary">
                 <i class="fa  fa-cube"></i>
                 <h5 class="m-0 counter text-white">총 계약금액(월대차제외)</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($member[total_money])?>원</b>
             </div>
        </div>


        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-info">
               <i class="fa  fa-usd"></i>
               <h5 class="m-0 counter text-white">월대차(약정금액)</h5>
               <b style="font-size:20px;" class="text-warning"><?=number_format($total_month)?>원</b>
             </div>
        </div>

        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-info">
                 <i class="fa  fa-cube"></i>
                 <h5 class="m-0 counter text-white">월대차(입금금액)</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($sum_mm_total)?></b>
             </div>
        </div>

        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-success">
                 <i class="fa  fa-cube"></i>
                 <h5 class="m-0 counter text-white">고객이자 발생총액</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($total_int)?>원</b>
             </div>
        </div>


        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-gray">
                 <i class="fa  fa-usd"></i>
                 <h5 class="m-0 counter text-white">수수료 발생총액</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($total_bonus)?>원</b>
             </div>
        </div>

        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-gray">
                 <i class="fa  fa-usd"></i>
                 <h5 class="m-0 counter text-white">수수료 지급액</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($total_bonus_out)?>원</b>
             </div>
        </div>

        <div class="col-md-3 col-sm-6">
             <div class="widget-panel widget-style-1 bg-danger">
                 <i class="fa  fa-usd"></i>
                 <h5 class="m-0 counter text-white">만기예정 계약(<?=$d?>개월내)</h5>
                 <b style="font-size:20px;" class="text-warning"><?=number_format($total_dday)?>건</b>
             </div>
        </div>

</div>
<!-- 팀장,본부장 노출화면 끝 -->


<?}?>
            <!-- Detail list-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">



                        <!-----------게시판 출력-------------------->

                        <div class="panel-heading">
                            <h2 class="panel-title text-">계약 내역(Detail List)</h2>
                        </div>

                        <!--list-table-->


                       <div class="text-right">   <!---게시물 검색--->
                                     <form action='<?=$PHP_SELE?>'>

                                          <div style="padding:10px; background-color:#d5ebee;">
                                          <div class="radio-inline">
                                              <label class="cr-styled">
                                                  <input type="radio" name="select" value="" checked="">
                                                  <i class="fa"></i>
                                                  전체
                                              </label>
                                         </div>

                                         <div class="radio-inline">
                                             <label class="cr-styled">
                                                 <input type="radio" name="select" value="old">
                                                 <i class="fa"></i>
                                                 만기계약 기준
                                             </label>
                                         </div>

                                         <div class="radio-inline">
                                             <label class="cr-styled">
                                                 <input type="radio" name="select" value="month">
                                                 <i class="fa"></i>
                                                 월대차 계약
                                             </label>
                                        </div> &nbsp; &nbsp;
                                        <input class="btn btn-inverse btn-sm" type='submit' value='Search'>
                                   </div>

                                     </form>
                                </div>




                                <div class="table-responsive">
                                    <table class="table  table-hover" style="font-size:12px;">
                                        <thead class="text-white" style="background-color:#808080">

                                            <tr>
                                                <th class="text-center">NO</th>
                                                <th class="text-center">계약번호</th>
                                                <th class="text-center">계약자</th>
                                                <th class="text-center">계약종류</th>
                                                <th class="text-center">계약구분</th>
                                                <th class="text-center">약정금액</th>
                                                <th class="text-center">약정이율</th>
                                                <th class="text-center">약정기간</th>
                                                <th class="text-center">이자총액</th>
                                                <th class="text-center">담당팀장</th>
                                                <th class="text-center">계약현황</th>
                                                <th class="text-center">지급현황</th>
                                                <th class="text-center">계약상태</th>
                                            </tr>
                                        </thead>
                                        <tbody>

<?


if(!$select){
     echo "<b style='color:red'>전체 정렬</b>";
     $txt="(id_manager='$member[name]' or id_incentive='$member[name]' or id_top='$member[name]' or name='$member[name]')";
     $query_count ="select count(*) from contract where $txt";  //전체 페이지수 구하기용
     $query ="select * from contract where $txt  order by no desc limit $page, $view_total";  //게시판 출력용
     $query_page ="select * from contract where $txt"; //전체 합계용

}elseif ($select=="old"){
     echo "<b style='color:red'>정렬 : 만기계약 순서</b>";
     $txt="(id_manager='$member[name]' or id_incentive='$member[name]' or id_top='$member[name]' or name='$member[name]')";

     $query_count ="select count(*) from contract where $txt";  //전체 페이지수 구하기용
     $query ="select * from contract where $txt  order by ctr_end asc limit $page, $view_total";  //게시판 출력용
     $query_page ="select * from contract where $txt"; //전체 합계용

}elseif ($select=="month"){
     echo "<b style='color:red'>정렬 :월대차 계약</b>";

     $txt="(id_manager='$member[name]' or id_incentive='$member[name]' or id_top='$member[name]' or name='$member[name]')";

     $query_count ="select count(*) from contract where type='월대차/만기지급' and $txt";  //전체 페이지수 구하기용
     $query ="select * from contract where type='월대차/만기지급' and $txt  order by ctr_end asc limit $page, $view_total";  //게시판 출력용
     $query_page ="select * from contract where type='월대차/만기지급' and  $txt"; //전체 합계용

}



/////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        //게시물 총갯수 파악-- 전체 페이지수 결정을 위해 게시물 수를 세어야 한다.
                                            mysql_query("set names utf8");  //언어셋 utf8
                                            $result1= mysql_query($query_count, $connect);
                                            $temp= mysql_fetch_array($result1);
                                            $totals= $temp[0];

                                        // 조건에 맞는 게시물 쿼리
                                            $cnt=(($_page-1)*$view_total)+1; //매 페이지수 시작할때 NO번호시작
                                            $result=mysql_query($query,$connect);
                                            while($data = mysql_fetch_array($result)){ ?>

                                            <tr>
                                                <td class="text-center"><?=$cnt?></td>
                                                <td class="text-center"><?=$data[ctr_no]?></td>
                                                <td class="text-center"><?=$data[name]?></td>
                                                <td class="text-center"><?=$data[type]?></td>
                                                <td class="text-center"><?=$data[newtype]?></td>
                                                <td class="text-center"><?=number_format($data[money])?>원정</td>

                                                <td class="text-center"><?=$data[rate_cus]?>%</td>
                                                <td class="text-center"><?=$data[ctr_start]?> ~ <?=$data[ctr_end]?></td>
                                                <td class="text-center"><?=number_format($money_int=$data[money]*$data[rate_cus]/100)?>원</td>

                                                <td class="text-center"><?=$data[id_manager]?></td>
                                                <td class="text-center"> <input type="button" name="view1" value="계약조회" id="<?=$data[no]?>" class="btn btn-danger btn-xs view_data1" /></td>
                                                <? if ($data[type]=="월대차/만기지급") { ?>
                                                   <td class="text-center"> <input type="button" name="view2" value="납부조회" id="<?=$data[no]?>" class="btn btn-inverse btn-xs view_data2" /></td>
                                                <? } else { ?>
                                                <td class="text-center"> <input type="button" name="view2" value="지급조회" id="<?=$data[no]?>" class="btn btn-info btn-xs view_data2" /></td>
                                                <?}?>

                                                <td class="text-center"><?=$data[state]?></td>

                                           </tr>
                                            <?
                                            $cnt++;
                                            }	?>

                                          <!-- /////////게시물 게시판 출력끝 -->


                                           <?  //페이징을 설정하면 한페이지 단위로만 합계가 계산되므로 전체 출력에 대한 합계를 별도로 구해야 한다.
                                           //따라서 $query_page를 둔 것이다.
                                           $total_sum=0;
                                           $total_int=0;
                                           $tnt=0;
                                           $result_t=mysql_query($query_page,$connect);
                                           while($data_t = mysql_fetch_array($result_t)){
                                           $total_sum+=$data_t[money];
                                           $total_int+=$data_t[sum_cus];
                                           $tnt++;
                                           }
                                           ?>

                                           <!--total cell-->
                                           <tr class="td-left2">
                                               <td class="text-center"></td>
                                               <td class="text-center">총 계약건수 :</td>
                                               <td class="text-center text-danger"><?=$tnt?>건</td>
                                               <td class="text-center"></td>
                                               <td class="text-right">계약총액 :</td>
                                               <td class="text-center text-danger"><?=number_format($total_sum)?>원정</td>
                                               <td class="text-center"></td>
                                               <td class="text-right">이자총액 :</td>
                                               <td class="text-center text-danger"><?=number_format($total_int)?>원</td>
                                               <td class="text-center"></td>
                                               <td class="text-center"></td>
                                               <td class="text-center"></td>
                                                 <td class="text-center"></td>
                                           </tr>


                                           <tr style="font-size:14px;">
                                               <td colspan="11" class="text-center"><?include ('../member/list_page.php');?></td>
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

<? include('./modal_script.php'); //javascript 모달창 출력 ?>
