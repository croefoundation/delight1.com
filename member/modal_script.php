
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


<!-- //modal-view pay/ -->
 <div id="payModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content p-0 b-0">
                <div class="panel panel-color panel-success">
                    <div class="panel-heading">
                     <? if ($data[type]=="월대차/만기지급") { ?>
                    <b style="font-size:20px">월대차 납부 확인</b> <?}else {?>
                         <b style="font-size:20px">약정이자 지급내역</b> <?}?>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">CLOSE</button>

                    </div>
                    <div class="modal-body" id="pay_detail" style="font-size:12px; padding:20px">

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

                 <div class="btn btn-danger" onclick="window.print();">인쇄하기</div> &nbsp;&nbsp;
                 <button type="button" class="btn btn-default" data-dismiss="modal">돌아가기</button>

                </div>
           </div>
      </div>
 </div>
