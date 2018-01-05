<?ob_start();?>
<html>

<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if(!$member[user_id])Error_member();

$no=$_GET[no];
$id=$_GET[id];
$idx=$_GET[idx];

$queryi="select * from member where no='$no' and id='$id'";
mysql_query("set names UTF8",$connect);
$resulti=mysql_query($queryi,$connect);
$info=mysql_fetch_array($resulti);

?>


<!-- Top menu -->
<? include('../_header.php'); ?>



<body>
    <!--My Info-->
    <div class="p-y-0 " style="background-color:#">
        <div class="wraper container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-danger">개인정보 수정(CHANGE INFORMATION)</h2>
                        </div>
                        <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="font-size:13px;">

                                            <tbody>

                                                <tr>
                                              <form name="myinfo" action="../member/myinfo_edit_post.php" method="post">
                                                   <input type="hidden" name="id" value="<?=$info[id]?>">
                                                   <input type="hidden" name="no" value="<?=$info[no]?>">
                                                    <input type="hidden" name="idx" value="<?=$idx?>">

                                                    <td class="td-left1" style="padding-left:20px; width:20%;">ID</td>
                                                    <td><?=$info[user_id]?></td>

                                                </tr>

                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">성  명</td>
                                                    <td><?=$info[name]?></td>
                                                </tr>

                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">생년월일</td>
                                                    <td><input class="text-center text-info input-sm" type='text' name="birth" value="<?=$info[birth]?>"> <font size="1.5pt" color="097309"> (예)1980.10.25 형식입력</font></td>
                                                </tr>
                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">성별</td>
                                                    <td><input  class="text-center text-info input-sm" type='text' name="sex" value="<?=$info[sex]?>"><font size="1.5pt" color="#097309">(예)남/여 선택입력</font></td>
                                                </tr>
                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">이메일(E-Mail)</td>
                                                    <td><input  class="text-center text-info input-sm" type='text' name="email" value="<?=$info[email]?>"><font size="1.5pt" color="097309">(예) aaa@bbb.com 형식입력</font></td>
                                                </tr>
                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">전화번호</td>
                                                    <td><input class="text-center text-info input-sm" type='text' name="tel" value="<?=$info[tel]?>"><font size="1.5pt" color="097309">(예)010-1234-5678 형식입력</font></td>
                                                </tr>
                                                  <tr>
                                                    <td class="td-left1" style="padding-left:20px;">주  소</td>
                                                    <td>기본주소 : <input type='text' name="addr_1" value="<?=$info[addr_1]?>" size='70px;' class="text-info input-sm"><br>
                                                        상세주소 : <input type='text' name="addr_2" value="<?=$info[addr_2]?>" size='70px;' class="text-info input-sm"></td>
                                                </tr>

                                                <?
                                               $qt= "select * from member where name='$info[id_manager]' ";
                                               mysql_query("set names ust8",$connect);
                                               $rt= mysql_query($qt, $connect);
                                               $mt= mysql_fetch_array($rt);
                                               ?>
                                               <tr>
                                                   <? if($info[level]=='C'){ ?>
                                                   <td class="td-left1" style="padding-left:20px;">담당 관리자</td>
                                                   <td>
                                                       성 명 : <?=$info[id_manager]?> (<?=$mt[tel]?> )
                                                   </td>
                                                   <?} else {?>
                                                   <td class="td-left1" style="padding-left:20px;">본인 소개팀장</td>
                                                   <td>
                                                       <?=$info[id_incentive]?>
                                                   </td>
                                                   <?}?>
                                              </tr>


                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">계좌번호</td>
                                                    <td><input class="text-info input-sm" type='text' name="account" value="<?=$info[account]?>" size="50px;" ><font size="1.5pt" color="097309">(예)국민은행 1234-5678-099 홍길동  형식입력</font></td>
                                                </tr>


                                                <tr>
                                                    <td class="td-left2" style="padding-left:20px;">**비밀번호 입력</td>
                                                    <td class="td-left2"><input class="text-red input-sm" type='password' name="pw" size="30px" ><font size="1.8pt" color="red"> *정보를 변경하시려면 비밀번호를 입력하셔야 합니다.</font></td>
                                                </tr>



                                           </tbody>

                                        </table>
                                    </div>
                                    <div class="text-center" style="padding-top:20px;">
                                        <input type="submit"  class="btn btn-danger" value="수정하기" onclick="return confirm('입력한 값으로 수정하시겠습니까?')"> &nbsp;&nbsp;
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
