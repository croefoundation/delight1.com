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


<body>
    <!--My Info-->
    <div class="p-y-0 " style="background-color:#">
        <div class="wraper container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title">나의 정보(MY INFORMATION)</h2>
                        </div>
                        <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="font-size:13px;">

                                            <tbody>

                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px; width:20%;">ID</td>
                                                    <td><?=$member[user_id]?></td>

                                                </tr>

                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">성  명</td>
                                                    <td><?=$member[name]?></td>
                                                </tr>

                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">생년월일</td>
                                                    <td><?=$member[birth]?></td>
                                                </tr>
                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">성별</td>
                                                    <td><?=$member[sex]?></td>
                                                </tr>
                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">이메일(E-Mail)</td>
                                                    <td><?=$member[email]?></td>
                                                </tr>
                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">전화번호</td>
                                                    <td><?=$member[tel]?></td>
                                                </tr>
                                                  <tr>
                                                    <td class="td-left1" style="padding-left:20px;">주  소</td>
                                                    <td><?=$member[addr_1]?><br> <?=$member[addr_2]?></td>
                                                </tr>

                                                <?
                                               $qt= "select * from member where name='$member[id_manager]' ";
                                               mysql_query("set names ust8",$connect);
                                               $rt= mysql_query($qt, $connect);
                                               $mt= mysql_fetch_array($rt);
                                               ?>
                                               <tr>
                                                   <? if($member[level]=='C'){ ?>
                                                   <td class="td-left1" style="padding-left:20px;">담당 관리자</td>
                                                   <td>
                                                       성 명 : <?=$member[id_manager]?> (<?=$mt[tel]?> )
                                                   </td>
                                                   <?} else {?>
                                                   <td class="td-left1" style="padding-left:20px;">본인 소개팀장</td>
                                                   <td>
                                                       <?=$member[id_incentive]?> (<?=$mt[tel]?> )
                                                   </td>
                                                   <?}?>
                                              </tr>


                                                <tr>
                                                    <td class="td-left1" style="padding-left:20px;">계좌번호</td>
                                                    <td><?=$member[account]?></td>
                                                </tr>



                                            </tbody>

                                        </table>
                                    </div>
                                    <div style="padding-top:20px;" class="text-center">
                                        <a href="./myinfo_edit.php?no=<?=$member[no]?>&id=<?=$member[id]?>"> <button type="button" class="btn btn-danger "> 정보수정</button></a> &nbsp;&nbsp;
                                        <a href="./list.php"> <button type="button" class="btn btn-inverse"> 나의 계약조회</button></a>
                                    </div>
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
