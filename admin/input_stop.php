<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자 메뉴입니다.");
?>


<!-- Top menu -->
<? include('../_header.php'); ?>


<body>

    <div class="p-y-0 " style="background-color:#;">
        <div class="wraper container">

            <!-- Detail list-->

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">


                        <!-----------중도해지,중도상환신청란-------------------->

                        <div class="panel-heading">
                            <b class="panel-title text-danger" style="font-size:30px">중도해지/중도상환 입력(관리자))</b>
                        </div>
                       <div class="panel-body">

                              <b>* 계약자와 계약번호를 입력하세요 </b>
                               <table class="table table-bordered" style="font-size:13px;">
                                    <form action='./input_stop_page.php' method="POST">
                                         <tr>
                                              <td class="td-left" style="padding-left:20px;">신청종류 </td>
                                              <td>
                                                   <div class="radio-inline">
                                                     <label class="cr-styled">
                                                          <input type="radio" name="stop_state" value="stop_cus" checked="">
                                                          <i class="fa"></i>
                                                          중도해지(고객원인)
                                                     </label>
                                                   </div>
                                                  <div class="radio-inline">
                                                     <label class="cr-styled">
                                                         <input type="radio" name="stop_state" value="stop_com">
                                                         <i class="fa"></i>
                                                         중도상환(회사원인)
                                                     </label>
                                                  </div>

                                              </td>
                                         </tr>

                                            <tr>
                                              <td class="td-left text-danger" style="padding-left:20px;">계약번호*</td>
                                              <td> <input type=text name=ctr_no class="input-sm" size=20 required=''></td>
                                            </tr>

                                            <tr>
                                                <td class="td-left " style="padding-left:20px;">계약자 성명*</td>
                                                <td> <input type=text name=name class="input-sm" size=20 required=''></td>
                                            </tr>
                                            <tr>
                                                <td class="td-left text-danger " style="padding-left:20px;">처리기준일*</td>
                                                <td> <input type=date name=stop_date class="input-sm" size=20 required=''>
                                                     <br><b class="text-danger">*중도해지</b> : 처리기준일까지 지급된 금액 공제 <br>
                                                     <b class="text-info">*중도상환</b> : 처리기준일까지만 지급후 상환
                                                     </td>
                                            </tr>

                                  </table>

                                  <div class="text-center" style="padding-top:20px;">
                                      <input type="submit"  class="btn btn-danger" value="신청하기" >
                                      <!--onclick="return confirm('입력한 값으로 등록하시겠습니까?')" -->
                                      &nbsp;&nbsp;
                                      <input type="reset"  class="btn btn-default" value="다시쓰기" onclick="location.reload();">
                                  </div>
                                 </form>




<br>
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
