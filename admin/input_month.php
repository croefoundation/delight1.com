<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자 메뉴입니다.");

//검색 키값설정
$key="월대차/만기지급";


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


<!-- Top menu -->
<? include('../_header.php'); ?>


<body>


<div class="wraper container">

            <!-- Detail list-->

            <div class="row">

               <div class="panel panel-default">

                        <!-- 검색 항목-->
                        <?
                        $Search_text=$_GET[Search_text];
                        $Search_mode=$_GET[Search_mode];

                        $_page=$_GET[_page];
                        $view_total = 30; //한 페이지에 30(설정할것)개 게시글이 보인다.
                        if(!$_page)($_page=1); //페이지 번호가 지정이 안되었을 경우
                        $page= ($_page-1)*$view_total;
                        ?>


                        <!-----------게시판 출력-------------------->

                        <div class="panel-heading">
                         <h3 class="title text-danger"><b>월대차 납입현황 입력 (관리자용)</b></h3>
                        </div>

                        <!--list-table-->
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">

                                 <div class="text-right">   <!---게시물 검색--->
                                     <form action='<?=$PHP_SELE?>'>
                                         <td height='20' colspan='5' bgcolor='#FFFFFF' align='right'>
                                             <label class="btn btn-default " >검색조건 &nbsp;
                                             <select name='Search_mode' class="input-sm">
                                                 <option value='3'>전체검색에서
                                                 <option value='1'>계약종류 조회
                                                 <option value='2'>약정이율(%)조회
                                             </select>

                                             <input type='text' name='Search_text' size='15' class="input-sm">
                                             <input class="btn btn-inverse btn-sm" type='submit' value='Search'>
                                             &nbsp;
                                             <input class="btn btn-default btn-sm" type='reset' value='Reset' onclick='location.reload();'>
                                             &nbsp;
                                         </td>
                                     </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table  table-bordered" style="font-size:11px;">
                                        <thead class="text-white" style="background-color:#808080">

                                            <tr>
                                                <th class="text-center">NO</th>
                                                <th class="text-center">계약번호</th>
                                                <th class="text-center">계약자</th>
                                                <th class="text-center">계약종류</th>
                                                <th class="text-center">약정금액</th>
                                                <th class="text-center">약정이율</th>
                                                <th class="text-center">약정기간</th>
                                                <th class="text-center">이자총액</th>
                                                <th class="text-center">담당팀장</th>
                                                <th class="text-center">납입회차/12회</th>
                                                <th class="text-center">납입(누적)액</th>
                                                <th class="text-center">월대차입력</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <!----계약 테이블에서 조회----------->
                                            <?

                                            $where="name";

                                            //검색할 종목을 선택 했을 때.
                                            if($Search_text){
                                            if($Search_mode==1) $tmp="type";
                                            if($Search_mode==2) $tmp="rate_cus";

                                            //전체에서 검색
                                            if($Search_mode==3){
                                            $where="(name like '%$Search_text%' or type like '%$Search_text%' or ctr_end like '%$Search_text%'
                                                    or ctr_no like '%$Search_text%' or money like '%$Search_text%' or id_manager like '%$Search_text%'
                                                    or rate_cus like '%$Search_text%' or rate_manager like '%$Search_text%' or ctr_date like '%$Search_text%'
                                            )"; //검색조건
                                            }else{
                                                 $where="$tmp like '%$Search_text%'";
                                                 }
                                                 
                                                 $query_count = "select count(*) from contract where $where and type='$key'";
                                                 $query= "select * from contract where $where and type='$key' order by no desc limit $page, $view_total"; //desc 내림차순   ASC 오름차순
                                                 $query_t = "select * from contract where $where and type='$key'";
                                                 }

                                              //검색하지 않을경우 기본적으로  월대차 리스트 출력

                                            else{

                                            $query_count ="select count(*) from contract where type='$key'";
                                            $query ="select * from contract where type='$key' order by no desc limit $page, $view_total";
                                            $query_t ="select * from contract where type='$key'";

                                          } //검색하지 않는직급별 검색조건 끝

                                        //게시물 총갯수 파악
                                            mysql_query("set names utf8");  //언어셋 utf8
                                            $result1= mysql_query($query_count, $connect);
                                            $temp= mysql_fetch_array($result1);
                                            $totals= $temp[0];

                                        // 조건에 맞는 게시물 쿼리
                                            $cnt=(($_page-1)*$view_total)+1; //매 페이지수 시작할때 NO번호시작+++
                                            $mm_total=0;
                                            $result=mysql_query($query,$connect);
                                            while($data = mysql_fetch_array($result)){ ?>

                                            <tr>
                                                <td class="text-center"><?=$cnt?></td>
                                                <td class="text-center"><input type="button" name="view1" value="<?=$data[ctr_no]?>" id="<?=$data[no]?>" class="btn btn-primary btn-xs view_data1" /></td>
                                                <td class="text-center"><?=$data[name]?></td>
                                                <td class="text-center"><?=$data[type]?></td>
                                                <td class="text-center"><?=number_format($data[money])?>원정</td>

                                                <td class="text-center"><?=$data[rate_cus]?>%</td>
                                                <td class="text-center"><?=$data[ctr_start]?> ~ <?=$data[ctr_end]?></td>
                                                <td class="text-center"><?=number_format($money_int=$data[money]*$data[rate_cus]/100)?>원</td>

                                                <td class="text-center"><?=$data[id_manager]?></td>

                                                     <? $mm_stotal=0; $mr=0;   //납입금 합계 구하기
                                                     for ($m=1; $m<13 ; $m++) {
                                                          $mmth="mm".$m;    //매월 적립한 금액-납입한 달 속성
                                                       $mm_stotal+=$data[$mmth];  //적립금 누적계산
                                                       if($data[$mmth]){$mr++;};
                                                       }
                                                     ?>
                                                <td class="text-center text-info td-left" style="font-size:12px;"><?=$mr?>회차/12</td>
                                                <td class="text-center text-info td-left" style="font-size:12px;"><?=number_format($mm_stotal)?> </td>
                                                <td class="text-center td-left">
                                                <a href="../admin/input_month_page.php?no=<?=$data[no]?>&id=<?=$data[id]?>"> <button type="button" class="badge badge-danger">납입입력</button></a>
                                           </tr>
                                            <?
                                            $cnt++; $mm_total+=$mm_stotal;
                                            } ?>


                                            <?
                                            $total_money=0;
                                            $total_int=0;

                                            $tnt=0;
                                            $result_t=mysql_query($query_t,$connect);
                                            while($data_t = mysql_fetch_array($result_t)){
                                            $total_money+=$data_t[money];
                                            $total_int+=$data_t[sum_cus];
                                            $tnt++;
                                            }
                                            ?>

                                            <!--total cell-->
                                            <tr class="td-left2">
                                                <td class="text-center"></td>
                                                <td class="text-center">총 계약건수 :</td>
                                                <td class="text-center text-danger"><?=$tnt?>건</td>
                                                <td class="text-right">계약총액 :</td>
                                                <td class="text-center text-danger"><?=number_format($total_money)?>원정</td>
                                                <td class="text-center"></td>
                                                <td class="text-right">이자총액 :</td>
                                                <td class="text-center text-danger"><?=number_format($total_int)?>원</td>
                                                <td class="text-center"></td>
                                                <td class="text-center td-left1">납입금 총액</td>
                                                <td class="text-center text-danger td-left1"><?=number_format($mm_total)?>원</td>
                                                <td class="text-center td-left1"></td>
                                            </tr>


                                            <tr style="font-size:14px;">
                                                <td colspan="12" class="text-center"><?include ('../member/list_page.php');?></td>
                                            </tr>


                                            </tbody>
                                    </table>

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





<script>
$(document).ready(function(){
     $('.view_data1').click(function(){               //view_data를 받아서
          var contract_no = $(this).attr("id");
          $.ajax({
               url:"../member/modal_view_contract.php",
               method:"post",
               data:{contract_no:contract_no},
               success:function(data){
                    $('#contract_detail').html(data);  //modal_body 내용
                    $('#contractModal').modal("show");     //modal명
               }
          });
     });
});
</script>



<script>
$(document).ready(function(){
     $('.view_data2').click(function(){               //view_data를 받아서
          var pay_no = $(this).attr("id");
          $.ajax({
               url:"../member/modal_view_pay.php",
               method:"post",
               data:{pay_no:pay_no},
               success:function(data){
                    $('#pay_detail').html(data);  //modal_body 내용
                    $('#payModal').modal("show");     //modal명
               }
          });
     });
});
</script>



<script>
$(document).ready(function(){
     $('.view_data3').click(function(){               //view_data를 받아서
          var m_no = $(this).attr("id");
          $.ajax({
               url:"../admin/input_month_form.php",
               method:"post",
               data:{m_no:m_no},
               success:function(data){
                    $('#m_detail').html(data);  //modal_body 내용
                    $('#mModal').modal("show");     //modal명
               }
          });
     });
});
</script>




<!-- //modal-month input -->
 <div id="mModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content p-0 b-0">
                <div class="panel panel-color panel-danger">
                    <div class="panel-heading">
                       <b style="font-size:20px">월대차 납부입력(관리자)</b> <button type="button" class="close" data-dismiss="modal" aria-hidden="true">CLOSE</button>

                    </div>
                    <div class="modal-body" id="m_detail" style="font-size:12px;">

                    </div>

                   </div><!-- End panel -->

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

<!-- //modal-view pay/ -->
 <div id="payModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content p-0 b-0">
                <div class="panel panel-color panel-warning">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">CLOSE</button>

                    </div>
                    <div class="modal-body" id="pay_detail" style="font-size:12px;">

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">돌아가기</button>
                    </div>

                   </div><!-- End panel -->

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>



<!-- //modal-view contract/ -->
 <div id="contractModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
           <div class="modal-content">

                <div class="modal-body" id="contract_detail" style="background-image:url(../img/contract01.png); background-repeat:no-repeat;  background-position:top center" >
                </div>
                <div class="modal-footer">

                 <button type="button" class="btn btn-danger"> 인쇄</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal">돌아가기</button>

                </div>
           </div>
      </div>
 </div>
