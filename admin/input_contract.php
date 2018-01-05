<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자 메뉴입니다.");
include('./contract_type_rate.php');
?>


<!-- Top menu -->
<? include('../_header.php'); ?>

<script>

/*등록확인 체크란*/
 function id_check(){
     if(!document.form_1.name.value){
       window.alert('등록고객명을 입력하셔야 합니다.');
       document.form_1.name.focus();
       return false;
     }
     var a = document.form_1.name.value;

     window.open('../admin/id_check.php?name='+a,'','width=300, height=100');
     }

     /*계약번호 체크란*/
      function ctr_check(){
          if(!document.form_1.ctr_no.value){
            window.alert('계약번호를 먼저 입력하셔야 합니다.');
            document.form_1.ctr_no.focus();
            return false;
          }
          var b = document.form_1.ctr_no.value;

          window.open('../admin/ctr_check.php?ctr_no='+b,'','width=300, height=100');
          }

</script>



<body>
    <!--My Info-->
    <div class="p-y-0 " style="background-color:#">
        <div class="wraper container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-purple" style="font-size:35px"><b>계약서 등록(관리자용)</b></h2>
                        </div>
                        <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="font-size:13px;">

                                            <tbody>

                                       <form action="../admin/input_contract_post.php" method="post"  name='form_1'>
                                           <input type="hidden" name="id" value="ctr">

                                           <tr>
                                                <td class="td-left" style="padding-left:20px;">계약종류 * </td>
                                                <td>
                                                     <div class="radio-inline">
                                                        <label class="cr-styled">
                                                             <input type="radio" name="newtype" value="신규" checked="">
                                                             <i class="fa"></i>
                                                             신규계약
                                                        </label>
                                                     </div>
                                                    <div class="radio-inline">
                                                        <label class="cr-styled">
                                                            <input type="radio" name="newtype" value="연장">
                                                            <i class="fa"></i>
                                                            재계약(연장)
                                                        </label>
                                                    </div>

                                                </td>
                                            </tr>

                                            <tr>
                                                 <td class="td-left" style="padding-left:20px;">계약서 등록용도 </td>
                                                 <td>
                                                      <div class="radio-inline">
                                                         <label class="cr-styled">
                                                              <input type="radio" name="why" value="정상등록" checked="">
                                                              <i class="fa"></i>
                                                              정상적인 등록
                                                         </label>
                                                      </div>
                                                     <div class="radio-inline">
                                                         <label class="cr-styled text-info">
                                                             <input type="radio" name="why" value="고객용">
                                                             <i class="fa"></i>
                                                             고객확인용(고객은 이자 정상발생) ---> *팀장이상 수당계산 안함
                                                         </label>
                                                     </div>

                                                 </td>
                                             </tr>

                                            <tr>

                                              <td class="td-left" style="padding-left:20px;">계약번호*</td>
                                              <td> <input type=text name=ctr_no class="input-sm" size=20 required=''>
                                                   <!------- 계약번호 중복확인 점검 ----------------------->
                                                  <input type=button value='중복확인'  class="btn btn-danger btn-xs" onclick="ctr_check();">
                                                   <font color=red>* (계약번호 중복여부를 먼저 반드시 확인해주세요!!!)</font></td>

                                            <tr>


                                            <tr>
                                                <td class="td-left " style="padding-left:20px;">계약자 성명*</td>
                                                <td> <input type=text name=name class="input-sm" size=20 required=''>
                                                     <!------- 계약자 등록확인 점검 ----------------------->
                                                    <input type=button value='등록확인'  class="btn btn-danger btn-xs" onclick="id_check();">
                                                     <font color=red>* (회원 등록여부를 먼저 확인해주세요. 동명이인 여부도 확인바랍니다.)</font></td>
                                            </tr>


                                             <tr>
                                                 <td class="td-left1 text-danger" style="padding-left:20px;">계약종류*</td>
                                                 <td>
                                                     <select name="type" class="form-control" required="">
                                                         <option class="text-primary" value="" readonly="">계약종류 선택</option>
                                                         <option value="1년만기/만기지급" class="text-danger">[Type1] 1년만기형(만기지급-연01회이자지급)</option>
                                                         <option value="1년만기/반기지급">[Type2] 1년만기형(반기지급-연02회이자지급)</option>
                                                         <option value="1년만기/분기지급">[Type3] 1년만기형(분기지급-연04회이자지급)</option>
                                                         <option value="1년만기/매월지급">[Type4] 1년만기형(매월지급-연12회이자지급)</option>
                                                         <option value="6개월만기/만기지급">[Type5] 6개월만기형(만기시 1회이자지급)</option>
                                                         <option value="월대차/만기지급">[Type6] 월대차형(월납입 만기시 이자지급)</option>
                                                     </select>
                                                 </td>
                                             </tr>


                                             <tr>
                                             <td rowspan="2" class="td-left1 text-danger" style="padding-left:20px;">약정금액*</td>
                                             <td>
                                                 <span>
                                                     합계 <input type="number" name="money" class="text-danger input-sm" min="0" placeholder=" " value="" required="">
                                                     <label class="btn btn-danger btn-xs"> 원정</label>
                                                 </span>
                                             </td>
                                             </tr>

                                            <tr>

                                                <td>
                                                    <b class="text-danger">기존자금 재계약일 경우</b> = 이월금액 <input type="text" name="money_old" class="input-sm"> + 신규금액<input type="text" name="money_new" class="input-sm" >
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="td-left1 text-danger" style="padding-left:20px;">계약기간*</td>
                                                <td>
                                                   <input type="date" name="ctr_start" class="input-sm" required="">~<input type="date" name="ctr_end" class="input-sm" required="">
                                                </td>
                                            </tr>


                                            <tr>
                                                <td class="td-left1 text-danger" style="padding-left:20px;">고객이율*</td>
                                                <td>
                                                    <input type="text" name="rate_cus" class="input-sm text-danger" size="10" required=""> %

                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="td-left1 text-danger" style="padding-left:20px;">담당팀장</td>
                                                <td>
                                                   <input type="text" name="id_manager" class="input-sm" value="" required="">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="td-left" style="padding-left:20px;">계약일</td>
                                                <td>
                                                   <input type="date" name="ctr_date" class="input-sm" required="">
                                                </td>
                                            </tr>

                                            <tr>
                                               <td class="td-left" style="padding-left:20px;">구 계약번호</td>
                                               <td>
                                                   재계약일경우 : 구계약번호 <input type="text" name="ctr_old" class="input-sm" >
                                               </td>
                                            </tr>

                                            <tr>
                                              <td class="td-left" style="padding-left:20px;">메모</td>
                                              <td>
                                                    <textarea rows="3"  name="memo" id="memo" class="form-control" value="" placeholder=""></textarea>
                                              </td>
                                            </tr>


                                           </tbody>

                                        </table>
                                    </div>
                                    <div class="text-center" style="padding-top:20px;">
                                        <input type="submit"  class="btn btn-danger" value="등록하기" >
                                        <!--onclick="return confirm('입력한 값으로 등록하시겠습니까?')" -->
                                         &nbsp;&nbsp;
                                        <input type="reset"  class="btn btn-default" value="다시쓰기" onclick="location.reload();">
                                    </div>
                               </form>
                        </div>
                    </div>
                </div>

            </div> <!-- End row -->
      </div> <!--end wraper-->
    </div> <!--end contents-->


            <!-- footer -->
            <? include('../_footer.php'); ?>

            </html>

            <!--END--Footer---------------->
