<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if(!$member[user_id])Error_member();
include('../lib/lib.php'); //시간함수외
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

                    <h3 class="title text-danger"><b>계약조건별 검색 </b></h3>

            </div> <!-- End row -->


            <!-- dashboard -->
            <div class="row">
                    <div class="col-md-12">
                    <table class="table-bordered" style="font-size:12px; line-height:25px; width:100%;">
                    <tr>
                         <td class="td-left1 bg-primary" style="padding-left:20px; width:200px;">전체 계약건수</td><td class="text-center td-left text-primary"><?=$tnt?>건</td>
                         <td class="td-left bg-info" style="padding-left:20px; width:200px;">월대차 계약건수</td><td class="text-center td-left text-primary"><?=$tnm?>건</td>
                    </tr>
                    <tr>
                         <td class="td-left1 bg-primary" style="padding-left:20px;">총계약금액(월대차제외)</td><td class="text-center text-primary td-left"><?=number_format($total_money)?>원</td>
                         <td class="td-left bg-info" style="padding-left:20px;">월대차(약정금액)</td><td class="text-center td-left text-primary"><?=number_format($total_month)?>원</td>
                    </tr>
                    <tr>
                         <td class="td-left1 bg-primary" style="padding-left:20px;">고객이자 총액</td><td class="text-center text-primary  td-left"><?=number_format($total_int)?>원</td>
                         <td class="td-left bg-info" style="padding-left:20px;">월대차(입금총액)</td><td class="text-center td-left text-primary"><?=number_format($sum_mm_total)?>원</td>
                    </tr>

                    <tr>
                         <td class="td-left1 bg-gray" style="padding-left:20px; color:white;">신규계약 건수</td><td class="text-center text-primary  td-left1"><?=number_format($tn_new)?>건</td>

                         <td class="td-left1 bg-pink" style="padding-left:20px; color:white;">연장계약 건수</td><td class="text-center text-danger  td-left1"><?=number_format($tn_old)?>건</td>


                    </tr>

                    <tr>
                         <td class="td-left bg-gray" style="padding-left:20px;">신규계약합(월대차제외)</td><td class="text-center td-left1 text-primary"><?=number_format($total_new)?>원</td>

                         <td class="td-left bg-pink" style="padding-left:20px; color:white;">연장계약(총액)</td><td class="text-center td-left1 text-danger"><?=number_format($total_old)?>원</td>

                    </tr>



                    <tr>
                         <td class="td-left2 bg-danger" style="padding-left:20px;">수수료 발생총액</td><td class="text-center text-danger  td-left2"><?=number_format($total_bonus)?>원</td>
                         <td class="td-left2 bg-rw" style="padding-left:20px;">(1)커미션 합계</td><td class="text-center text-danger td-left2"><?=number_format($pay_commission)?>원</td>
                    </tr>
                    <tr>
                         <td class="td-left2  bg-danger" style="padding-left:20px;">-지급된 수수료합 </td><td class="text-center text-danger td-left2"><?=number_format($total_bonus_out)?>원</td>
                         <td class="td-left2  bg-rw" style="padding-left:20px;">(2)인센티브 합계</td><td class="text-center text-danger td-left2"><?=number_format($pay_incentive)?>원</td>
                    </tr>
                    <tr>
                         <td class="td-left2  bg-danger" style="padding-left:20px;">-미지급 수수료합</td><td class="text-center text-danger td-left2"><?=number_format($total_bonus_not)?>원</td>
                         <td class="td-left2  bg-rw" style="padding-left:20px;">(3)본부성과급 합계</td><td class="text-center text-danger td-left2"><?=number_format($pay_bonbu)?>원</td>
                    </tr>
                    </table>
               </div>
               </div>

<br>


            <!-- Detail list-->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">




                        <!-- 검색 항목-->
                        <?
                        $Search_text=$_GET[Search_text];
                        $Search_mode=$_GET[Search_mode];

                        $_page=$_GET[_page];
                        $href=$_GET[href];

                        if($member[level]=="L") {
                            $view_total = 50; //한 페이지에 30(설정할것)개 게시글이 보인다.
                       }else{$view_total =10;}

                        $href = "&Search_mode=$Search_mode&Search_text=$Search_text";

                        if(!$_page)($_page=1); //페이지 번호가 지정이 안되었을 경우
                        $page= ($_page-1)*$view_total;
                        ?>

                        <!-----------게시판 출력-------------------->

                        <div class="panel-heading">
                            <h2 class="panel-title text-">계약 내역(Detail List)</h2>
                        </div>

                        <!--list-table-->
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">

                                 <div class="text-right">   <!---게시물 검색--->
                                     <form action='<?=$PHP_SELE?>'>
                                         <td height='20' colspan='5' bgcolor='#FFFFFF' align='right'>
                                             <label class="btn btn-default " >검색조건 &nbsp;
                                             <select name='Search_mode' class="input-sm">
                                                 <option value='0'>전체검색에서
                                                 <option value='1'>계약자 조회
                                                 <option value='2'>팀장별 조회
                                                 <option value='3'>계약종류
                                                 <option value='4'>시작일로 조회
                                                 <option value='5'>종료일로 조회
                                                 <option value='6'>신규/연장 조회
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
                                       if($member[level]=='C'){
                                       $query_count = "select count(*) from contract where user_id='$member[user_id]'";
                                       $query = "select * from contract where user_id='$member[user_id]' order by no desc limit $page, $view_total";
                                       $query_t = "select * from contract where user_id='$member[user_id]'";
                                       }

                                       if($member[level]=='B'){
                                       $query_count ="select count(*) from contract where id_manager='$member[name]' or id_incentive='$member[name]' or user_id='$member[user_id]' ";
                                       $query ="select * from contract where id_manager='$member[name]' or id_incentive='$member[name]' or user_id='$member[user_id]' order by no desc limit $page, $view_total";
                                       $query_t ="select * from contract where id_manager='$member[name]' or id_incentive='$member[name]' or user_id='$member[user_id]'";
                                       }

                                       if($member[level]=='L'){
                                       $query_count ="select count(*) from contract where id='ctr'";
                                       $query ="select * from contract where id='ctr' order by no desc limit $page, $view_total";
                                       $query_t ="select * from contract where id='ctr'";
                                       }
                                       ?>

                                            <!----계약 테이블에서 조회----------->
                                            <?
                                            //$where="name";

                              //검색할 종목을 선택 했을 때.*********************************
                                  if($Search_text){
                                                      if($Search_mode==1) $tmp="name";
                                                      if($Search_mode==2) $tmp="id_manager";
                                                      if($Search_mode==3) $tmp="type";
                                                      if($Search_mode==4) $tmp="ctr_start";
                                                      if($Search_mode==5) $tmp="ctr_end";
                                                      if($Search_mode==6) $tmp="newtype";

                                                      //전체에서 검색
                                                      if($Search_mode==0){
                                         $where="(name like '%$Search_text%' or type like '%$Search_text%' or ctr_end like '%$Search_text%'
                                                              or ctr_no like '%$Search_text%' or money like '%$Search_text%' or id_manager like '%$Search_text%' or newtype like '%$Search_text%' or state like '%$Search_text%'
                                                              or rate_cus like '%$Search_text%' or rate_manager like '%$Search_text%' or ctr_date like '%$Search_text%' )"; //검색조건
                                                             }else{
                                                            $where="$tmp like '%$Search_text%'";
                                                      }  //검색어 선택

                                         if($member[level]=='C'){
                                           $query_count = "select count(*) from contract where $where and user_id='$member[user_id]'";
                                           $query = "select * from contract where $where and user_id='$member[user_id]' order by no desc limit $page, $view_total";
                                           $query_t = "select * from contract where $where and user_id='$member[user_id]'";
                                           }

                                         if($member[level]=='B'){
                                            $query_count = "select count(*) from contract where $where and (id_manager='$member[name]' or id_incentive='$member[name]' or user_id='$member[user_id]') ";
                                            $query ="select * from contract where $where and (id_manager='$member[name]' or id_incentive='$member[name]' or user_id='$member[user_id]')  order by no desc limit $page, $view_total";
                                            $query_t = "select * from contract where $where and (id_manager='$member[name]' or id_incentive='$member[name]' or user_id='$member[user_id]')";
                                            }

                                            if($member[level]=='L'){
                                            $query_count ="select count(*) from contract where $where and id='ctr'"; //조건에 맞는 갯수세게
                                            $query ="select * from contract where $where and id='ctr' order by no desc limit $page, $view_total";
                                            $query_t ="select * from contract where $where and id='ctr'";//조건에 맞는 쿼리를 저장
                                            }



                                       }  //검색어 있을경우 여기까지 쿼리 조건을 찾아서 출력준비 ************************





/////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        //게시물 총갯수 파악//페이지 결정을 위해
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
                                                <td class="text-center"><?=number_format($data[sum_cus])?>원</td>

<!-- $money_int=$data[money]*$data[rate_cus]/100 -->
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
                                            //따라서 $query_t를 둔 것이다.
                                            $total_sum=0;
                                            $total_int=0;
                                            $tnt=0;
                                            $result_t=mysql_query($query_t,$connect);
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
