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
                            <b class="panel-title text-danger" style="font-size:30px">수수료 조회</b>
                        </div>




                        <!-- dashboard -->
                        <div class="row">
                                <div class="col-md-12">
                                <table class="table-bordered" style="font-size:12px; line-height:25px; width:100%;">

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

                        <!--list-table-->
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                 <!---게시물 검색--->
                                 <div>

                                     <form action='<?=$PHP_SELE?>'>

                                             <label class="btn btn-default" style="width:100%;">게시판정렬 : &nbsp;
                                                  <span class="text-danger text-left" style="font-size:13px"><?if(!$Search_text){echo "전체";}else {echo $Search_text;}?>
                                                       &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             <select name='Search_mode' class="input-sm ">
                                                 <option value='3'>전체검색에서
                                                 <option value='4'>지급대상자 조회
                                                 <option value='1'>수수료종류 조회
                                                 <option value='2'>지급예정일 조회

                                             </select>

                                             <input type='text' name='Search_text' size='15' class="input-sm">
                                             <input class="btn btn-inverse btn-sm" type='submit' value='Search'>
                                             &nbsp;
                                             <input class="btn btn-default btn-sm" type='reset' value='Reset' onclick='location.reload();'>
                                             &nbsp;
                                        </span>
                                     </form>
                                </div>



                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size:12px;">
                                        <thead class="text-white" style="background-color:#b45c1d">

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

                                            <!----계약 테이블에서 조회----------->
                                            <?

                                            $where="name";
           //검색할 종목을 선택 했을 때. *********************************************************
                                            if($Search_text){
                                                 if($Search_mode==1) $tmp="pay_name";
                                                 if($Search_mode==2) $tmp="pay_date";
                                                 if($Search_mode==4) $tmp="name";

                                                 //전체에서 검색
                          if($Search_mode==3){
                                          $where="(pay_name like '%$Search_text%' or pay_date like '%$Search_text%' or name like '%$Search_text%'
                                                       or amount like '%$Search_text%' or ctr_no like '%$Search_text%' or pay_state like '%$Search_text%'
                                                       or period like '%$Search_text%' or ctr_name like '%$Search_text%' or ctr_name like '%$Search_text%'
                                                 )"; //검색조건
                                                 }else{
                                                 $where="$tmp like '%$Search_text%'";
                                                 }

                                          if($member[level]=='C'){
                                                 $query_count = "select count(*) from payment where $where and user_id='$member[user_id]'";
                                                 $query= "select * from payment where $where and user_id='$member[user_id]' order by pay_date asc, period asc limit $page, $view_total"; //desc 내림차순   ASC 오름차순
                                                 $query_t = "select * from payment where $where and user_id='$member[user_id]'";
                                                 }


                                                 $B_cond="user_id='$member[user_id]' or id_manager='$member[name]' or id_incentive='$member[name]' or id_top='$member[name]'"; //팀장 조회조건

                                         if($member[level]=='B'){

                                              //[회원테이블 전체]에서 로그인한 자신의 팀장이름을 1.담당팀장 2.소개팀장으로 갖고 있는 자들의 이름을 찾아서..저장해놔(하위회원)
                                             $qt= "select * from member where $B_cond";
                                                   mysql_query("set names ust8",$connect);
                                             $rt= mysql_query($qt, $connect);
                                              while($member_p= mysql_fetch_array($rt)){
                                                      $query_count = "select count(*) from payment  where $where and user_id='$member_p[user_id]'";
                                                       $query= "select * from payment where $where and user_id='$member_p[user_id]' order by pay_date asc, period asc limit $page, $view_total"; //desc 내림차순   ASC 오름차순
                                                       $query_t = "select * from payment where $where and user_id='$member_p[user_id]'";
                                                  }


                                                    }//xl팀장일경우 end

                                                 if($member[level]=='L'){
                                                      $query_count = "select count(*) from payment where $where and id='pay'";
                                                      $query= "select * from payment where $where  and id='pay' order by pay_date asc, period asc limit $page, $view_total"; //desc 내림차순   ASC 오름차순
                                                      $query_t = "select * from payment where $where and id='pay'";


                                                      }


                                            } else{

          //검색하지 않을경우 기본적으로 직급에 따라 리스트 출력조건을 달리 설정할 것

                                            if($member[level]=='C'){
                                            $query_count = "select count(*) from payment where user_id='$member[user_id]'";
                                            $query = "select * from payment where user_id='$member[user_id]' order by pay_date asc, period asc limit $page, $view_total";
                                            $query_t = "select * from payment where user_id='$member[user_id]'";
                                            }

                                           if($member[level]=='B'){
                                           $query_count ="select count(*) from payment where user_id='$member[user_id]'";
                                           $query = "select * from payment where user_id='$member[user_id]' order by pay_date asc, period asc limit $page, $view_total";
                                           $query_t = "select * from payment where user_id='$member[user_id]'";
                                           }

                                            if($member[level]=='L'){
                                            $query_count ="select count(*) from payment where id='pay'";
                                            $query ="select * from payment where id='pay' order by pay_date asc, period asc  limit $page, $view_total";
                                            $query_t ="select * from payment where id='pay'";
                                            }
           } //검색하지 않는직급별 검색조건 끝

                                        //게시물 총갯수 파악
                                            mysql_query("set names utf8");  //언어셋 utf8
                                            $result1= mysql_query($query_count, $connect);
                                            $temp= mysql_fetch_array($result1);
                                            $totals= $temp[0];

                                        // 조건에 맞는 게시물 쿼리
                                            $cnt=(($_page-1)*$view_total)+1; //매 페이지수 시작할때 NO번호시작+++
                                            $result=mysql_query($query,$connect);
                                            while($data = mysql_fetch_array($result)){ ?>

                                            <tr>
                                                <td class="text-center"><?=$cnt?></td>
                                                <td class="text-center"><?=$data[name]?></td>
                                                <td class="text-center"><?=$data[pay_name]?></td>
                                                <td class="text-center"><?=$data[pay_date]?></td>
                                                <td class="text-center"><?=number_format($data[amount])?>원</td>
                                                <td class="text-center"><?=$data[period]?></td>


                                                    <? //계약서 조회 모달 불러오기위해 계약서 테이블에서
                                                    $query_r= "select * from contract where ctr_no='$data[ctr_no]' ";
                                                    mysql_query("set names ust8", $connect);
                                                    $result_r= mysql_query($query_r, $connect);
                                                    $dr= mysql_fetch_array($result_r); ?>

                                             <td class="text-center"><input type="button" name="view1" value="<?=$data[ctr_no]?>" id="<?=$dr[no]?>" class="btn btn-primary btn-xs view_data1" /> /<font color="red"><?=$dr[newtype]?></font></td>

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
                                            <?
                                            $cnt++;

                                  }  // While문 게시판출력 종료	?>

                                            <?
                                            $tnt=0;
                                            $total_bonus=0;  //보너스합
                                            $result_t=mysql_query($query_t,$connect); //쿼리를 조회하여
                                            while($data_t = mysql_fetch_array($result_t)){    //조건에 맞는 한
                                            $total_bonus+=$data_t[amount];      //보너스를 합치고
                                            $tnt++;
                                            }
                                            ?>


                                            <!--total cell-->
                                            <tr class="td-left2">
                                                <td class="text-center"></td>
                                                <td class="text-center">총 발생건수 :</td>
                                                <td class="text-center text-danger"><?=$tnt?>건</td>
                                                <td class="text-right"><?if($member[level]=="C"){?>고객이자 합계<?}else{?>수수료 발생총액<?}?></td>
                                                <td class="text-center text-danger"><?=number_format($total_bonus)?>원</td>
                                                <td class="text-center"></td>
                                                <td class="text-right"></td>
                                                <td class="text-center text-danger"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                            </tr>


                                            <tr style="font-size:14px;">
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


<? include('./modal_script.php'); //javascript 모달창 출력 ?>
