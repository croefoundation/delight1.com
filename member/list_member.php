<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if(!$member[user_id])Error_member();
?>


<!-- Top menu -->
<? include('../_header.php'); ?>


<?
$total_mem=0; $total_direct=0; //하위 관리회원수 계산
$query_m="select * from member where id_manager='$member[name]' or id_incentive='$member[name]'";
$result_m=mysql_query($query_m,$connect);
while($data_m= mysql_fetch_array($result_m)){
$total_mem++;
if($member[name]==$data_m[id_manager]){$total_direct++;}
}
?>


<body>

     <div class="p-y-0 " style="background-color:#;">


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
                            <b class="panel-title text-danger" style="font-size:30px">회원등록 조회</b>
                        </div>

                        <div class="row">
                            <div class="col-md-12" style="font-size:13px; margin:0px 0px 10px 0px;">
                                <div class="p-y-1 p-x-1 td-left2"><b>조회자 성명 :</b>
                                <b class="text-purple" style="font-size:14px"><?=$member[name]?>(<?=$member[user_id]?>)</b> </div>
                            </div>
                        </div>


                        <!-- dashboard -->
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="widget-panel widget-style-1 bg-primary">
                                    <i class="fa  fa-star-half-full"></i>
                                    <h5 class="m-0 counter text-white">나의 전체 관리회원수</h5>
                                    <b style="font-size:20px;" class="text-warning"><?=$total_mem?>명</b>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="widget-panel widget-style-1 bg-primary">
                                    <i class="fa  fa-star-half-full"></i>
                                    <h5 class="m-0 counter text-white">나의 직접추천 회원수</h5>
                                    <b style="font-size:20px;" class="text-warning"><?=$total_direct?>명</b>
                                </div>
                            </div>


                            <div class="col-md-3 col-sm-6">
                               <div class="widget-panel widget-style-1 bg-purple">
                                    <i class="fa  fa-star-half-full"></i>
                                    <h5 class="m-0 counter text-white">나의 계약건수</h5>
                                    <b style="font-size:20px;" class="text-warning"><?=number_format($member[total_ctr])?>건</b>
                               </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                               <div class="widget-panel widget-style-1 bg-purple">
                                    <i class="fa  fa-cube"></i>
                                    <h5 class="m-0 counter text-white">총 약정금액</h5>
                                    <b style="font-size:20px;" class="text-warning"><?=number_format($member[total_money])?>원</b>
                               </div>
                            </div>
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
                                                 <option value='1'>등록자로 조회
                                                 <option value='2'>관리팀장으로 조회
                                                  <option value='4'>주소로 조회
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
                                    <table class="table table-bordered" style="font-size:12px;">
                                        <thead class="text-white" style="background-color:#b45c1d">

                                            <tr>
                                                <th class="text-center">NO</th>
                                                <th class="text-center">성명</th>
                                                <th class="text-center">I D</th>
                                                <th class="text-center">생년월일</th>
                                                <th class="text-center">주민번호</th>
                                                <th class="text-center">성별</th>
                                                <th width="5%" class="text-center">이메일</th>
                                                <th class="text-center">전화번호</th>
                                                <th width="10%" class="text-center">주   소</th>
                                                <th class="text-center">직급</th>
                                                <th class="text-center">비밀번호</th>
                                                <th class="text-center">관리팀장</th>
                                                <th class="text-center">본부장</th>

                                                <th class="text-center bg-rw">계약수</th>
                                                <th class="text-center bg-rw">약정총액</th>
                                                <th class="text-center bg-rw">수당총액</th>
                                                <th class="text-center">정보수정</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <!----계약 테이블에서 조회----------->
                                            <?

                                            $where="name";

                                            //검색할 종목을 선택 했을 때.
                                            if($Search_text){
                                                 if($Search_mode==1) $tmp="name";
                                                 if($Search_mode==2) $tmp="id_manager";
                                                 if($Search_mode==4) $tmp="addr_1";

                                                 //전체에서 검색
                                                 if($Search_mode==3){
                                                 $where="(name like '%$Search_text%' or birth like '%$Search_text%' or jumin like '%$Search_text%'
                                                       or sex like '%$Search_text%' or email like '%$Search_text%' or addr_1 like '%$Search_text%'
                                                       or addr_2 like '%$Search_text%' or id_manager like '%$Search_text%' or id_incentive like '%$Search_text%'
                                                 )"; //검색조건
                                                 }else{
                                                 $where="$tmp like '%$Search_text%'";
                                                 }

//직급별로 검색 제한설정****************
                                                 if($member[level]=='B'){
                                                      $query_count = "select count(*) from member where $where and (id_manager='$member[name]' or user_id='$member[user_id]' or id_incentive='$member[name]')";
                                                      $query= "select * from member where $where and (id_manager='$member[name]' or user_id='$member[user_id]' or id_incentive='$member[name]') order by no desc limit $page, $view_total"; //desc 내림차순   ASC 오름차순
                                                      $query_t = "select * from member where $where and (id_manager='$member[name]' or user_id='$member[user_id]' or id_incentive='$member[name]')'";


                                           //회원테이블 전체에서 로그인한 팀장이름을 1.담당팀장 2.소개팀장으로 갖고 있는 자들의 이름을 찾아서..저장해놔

                                                   }//xl팀장일경우 end

                                                if($member[level]=='L'){
                                                     $query_count = "select count(*) from member where $where and id='mem'";
                                                     $query= "select * from member where $where  and id='mem' order by no desc limit $page, $view_total"; //desc 내림차순   ASC 오름차순
                                                     $query_t = "select * from member where $where and id='mem'";


                                                } //회사 관리자일경우
                                                //직급별 검색조건 끝

                                            } else{

          //검색하지 않을경우 기본적으로 직급에 따라 리스트 출력조건을 달리 설정할 것

          if($member[level]=='B'){
          $query_count ="select count(*) from member where id_manager='$member[name]' or id_incentive='$member[name]'";
          $query ="select * from member where id_manager='$member[name]' or id_incentive='$member[name]'  order by no desc limit $page, $view_total";
          $query_t ="select * from member where id_manager='$member[name]' or id_incentive='$member[name]'";
          }

          if($member[level]=='L'){
          $query_count ="select count(*) from member where id='mem'";
          $query ="select * from member where id='mem' order by no desc limit $page, $view_total";
          $query_t ="select * from member where id='mem'";
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
                                                <td class="text-center"><?=$data[user_id]?></td>
                                                <td class="text-center"><?=$data[birth]?></td>
                                                <td class="text-center"><?=$data[jumin]?></td>
                                                <td class="text-center"><?=$data[sex]?></td>
                                                <td class="text-center"><?=$data[email]?></td>
                                                <td class="text-center"><?=$data[tel]?></td>
                                                <td class="text-left"><?=$data[addr_1]?> <br> <?=$data[addr_2]?> </td>
                                                <td class="text-center"><?=$data[level]?></td>
                                                <td class="text-center"><?=$data[pw]?></td>
                                                <td class="text-center"><?=$data[id_manager]?></td>
                                                <td class="text-center"><?=$data[id_top]?></td>
                                                <td class="text-center"><?=$data[total_ctr]?></td>

                                                <td class="text-center"><?=number_format($data[total_money])?></td>
                                                <td class="text-center"><?=number_format($data[total_bonus])?></td>

                                             <td class="text-center">
                                             <a href="../member/myinfo_edit.php?no=<?=$data[no]?>&id=<?=$member[id]?>&idx=mmm"> <button type="button" class="btn-xm btn-danger "> 수정</button></a>
                                             </td>

                                           </tr>
                                            <?
                                            $cnt++;

                                  }  // While문 게시판출력 종료	?>



                                            <!--total cell-->
                                            <tr class="td-left2">
                                                <td colspan="1" class="text-center"></td>
                                                <td colspan="2" class="text-center">총 관리 회원수 :</td>
                                                <td class="text-center text-danger"><?=$totals?>건</td>
                                                <td class="text-right"></td>
                                                <td class="text-center"></td>
                                                <td class="text-right"></td>
                                                <td class="text-center text-danger"></td>
                                                <td class="text-center"></td>
                                                <td colspan="10" class="text-center"></td>

                                            </tr>

                                            <tr style="font-size:14px;">
                                                <td colspan="50" class="text-center"><?include ('../member/list_page.php');?></td>
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

<br><br><br><br>

<!-- footer -->
<? include('../_footer.php'); ?>

</html>
