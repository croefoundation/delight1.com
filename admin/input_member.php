<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error_member("관리자 메뉴입니다.");

?>


<!-- Top menu -->
<? include('../_header.php'); ?>


<script>

/*등록확인 체크란*/
 function name_check(){
     if(!document.form_1.name.value){
       window.alert('등록고객명을 입력하셔야 합니다.');
       document.form_1.name.focus();
       return false;
     }
     var a = document.form_1.name.value;

     window.open('../admin/name_check.php?name='+a,'','width=300, height=100');
     }

     /*아이디 중복 체크란*/
      function memid_check(){
          if(!document.form_1.user_id.value){
            window.alert('계약번호를 먼저 입력하셔야 합니다.');
            document.form_1.user_id.focus();
            return false;
          }
          var b = document.form_1.user_id.value;

          window.open('../admin/memid_check.php?user_id='+b,'','width=300, height=100');
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
                            <h2 class="panel-title text-danger" style="font-size:35px"><b>회원 신규등록(관리자용)</b></h2>
                        </div>
                        <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="font-size:13px;">

                                            <tbody>

                                       <form action="../admin/input_member_post.php" method="post" name='form_1'>
                                           <input type="hidden" name="id" value="mem">

                                           <tr>
                                                <td class="td-left1" style="padding-left:20px;">등록종류 </td>
                                                <td>
                                                     <div class="radio-inline">
                                                        <label class="cr-styled">
                                                             <input type="radio" id="radio1" name="level" value="C" checked="">
                                                             <i class="fa"></i>
                                                             고객(Team Manager)
                                                        </label>
                                                     </div>
                                                    <div class="radio-inline">
                                                        <label class="cr-styled">
                                                            <input type="radio" id="radio2" name="level" value="B">
                                                            <i class="fa"></i>
                                                            팀장(Team Manager)
                                                        </label>
                                                    </div>
                                                    <div class="radio-inline">
                                                        <label class="cr-styled">
                                                            <input type="radio" id="radio3" name="level" value="A">
                                                            <i class="fa"></i>
                                                            본부장(Top Manager)
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">성  명 *</td>
                                                <td>
                                                    <input type="text" name="name" class="input-sm" required="">
                                                    <!------- 계약자 등록확인 점검 ----------------------->
                                                   <input type=button value='등록확인'  class="btn btn-gray btn-xs" onclick="name_check();">
                                                    <font color=red>* (회원 등록여부, 동명이인 여부확인)</font></td>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="td-left1 text-danger" style="padding-left:20px;">아이디 설정*</td>
                                                <td>
                                                   <input type="text" name="user_id" class="input-sm" required="">
                                                   <!------- 계약번호 중복확인 점검 ----------------------->
                                                  <input type=button value='중복확인'  class="btn btn-danger btn-xs" onclick="memid_check();">
                                                   <font color=red>* (아이디 중복여부를 먼저 반드시 확인해주세요!!!)</font></td>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">주민번호*</td>
                                                <td>
                                                    <input type="text" name="jumin" class="input-sm" size="40" placeholder="(형식) 880706-2045317 ">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">생년월일 *</td>
                                                <td>
                                                    <input type="date" name="birth" class="input-sm" value="" size="40" placeholder="(형식) 1988-05-09 " required="">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">성  별 * </td>
                                                <td>
                                                    <div class="radio-inline">
                                                        <label class="cr-styled">
                                                            <input type="radio" name="sex" value="남">
                                                            <i class="fa"></i>남
                                                        </label>
                                                    </div>
                                                    <div class="radio-inline">
                                                        <label class="cr-styled">
                                                            <input type="radio" name="sex" value="여">
                                                            <i class="fa"></i>여
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">E-mail *</td>
                                                <td>
                                                    <input type="text" name="email"  class="input-sm" value="" placeholder="(형식) abc@company.com" size="40">
                                            </tr>

                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">전  화 *</td>
                                                <td>
                                                     <input type="text" name="tel"  class="input-sm text-danger" value="" placeholder="(형식) 010-2548-9853">
                                                </td>
                                            </tr>

                                             <tr>
                                                <td class="td-left1" style="padding-left:20px;">주  소*</td>
                                                <td><input type="text" name="addr_1"  class="input-sm" value="" placeholder="기본주소" size="60"><br>
                                                    <input type="text" name="addr_2"  class="input-sm" value="" placeholder="상세주소" size="50">
                                            </tr>

                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">계좌번호</td>
                                                <td>
                                                 <input type="text" name="account"  class="input-sm" value="" placeholder="(형식)국민은행 635-31-27458-142 홍길동 " size="60"><br>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="td-left1" style="padding-left:20px;">담당팀장</td>
                                                <td>
                                                   <input type="text" name="id_manager" class="input-sm" value=""><br>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td class="td-left1 text-pink" style="padding-left:20px;">비밀번호 설정 *</td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="pw" class="input-sm" value="" required="">
                                                        <span class="input-group-addon">   <div style="width: 60px;">확인 </div></span>
                                                        <input type="text" name="pw1"  class="input-sm" value="">
                                                    </div>

                                                </td>
                                            </tr>

                                           </tbody>

                                        </table>
                                    </div>
                                    <div class="text-center" style="padding-top:20px;">
                                        <input type="submit"  class="btn btn-danger" value="등록하기" onclick="return confirm('입력한 값으로 등록하시겠습니까?')"> &nbsp;&nbsp;
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
