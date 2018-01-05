<? header("content_type:text/html; charset=UTF-8");

include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보


$contract_no=$_POST[contract_no];

//////계약정보///////
$querym= "select * from contract where no='$contract_no'";
mysql_query("set names ust8",$connect);
$resultm= mysql_query($querym, $connect);
$modal= mysql_fetch_array($resultm);

//계약자 정보//
$query_c= "select * from member where name='$modal[name]' ";
mysql_query("set names ust8",$connect);
$result_c= mysql_query($query_c, $connect);
$member_c= mysql_fetch_array($result_c);

?>


                        <div style="padding-top:75px; padding-left:170px; font-size:13px;">
                            <p style="padding-top:5px;"><?=$modal[ctr_no]?> <font color="red">( <?=$modal[newtype]?> )</font></p>
                            <p><?=$modal[name]?></p>
                            <p><?=$member_c[birth]?></p>
                            <p style="font-size:12px;"><?=$member_c[addr_1]?></p>
                            <p style="font-size:12px;"><? if($member_c[addr_2]){
                                    echo $member_c[addr_2];
                                         } else {echo "<br/>";
                                                 }?></p>

                            <p class="text-danger"><?=$modal[type]?></p>
                            <p class="text-danger"><?=number_format($modal[money])?>원정</p>
                            <p class="text-danger"><?=$modal[rate_cus]?> %</p>
                            <p class="text-danger"><?=$modal[ctr_start]?>~<?=$modal[ctr_end]?></p>
                            <p class="text-danger"><?=number_format($modal[sum_cus])?>원</p>
                            <p style="padding-top:5px;"><?=$modal[ctr_date]?></p>
                            <p><?=$member_c[account]?></p>


                            <p style="padding-top:52px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             <?php  $today=date("Y/m/d"); echo $today; ?> </p>
                            <br /><br /> <br /><br />
                        </div>
 <p style="padding-left:110px;">담당팀장 : <? echo $member_c[id_manager]."(".$member_c[tel].")";?></p>
