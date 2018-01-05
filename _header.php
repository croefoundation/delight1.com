<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../css/dgc01.css" rel="stylesheet" type="text/css">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/dgc02.css" rel="stylesheet" type="text/css">
    <title> Welcome to Delight & Trust Co., Ltd </title>
</head>


<div>
    <!--HEADER MENU-->
    <!--헤더메뉴-->
    <nav class="navbar navbar-info" style="border-radius:0; background-color:#77bef2; margin-bottom:10px; padding-bottom:0px;">
        <div class="container">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="#"><img src="../img/D_logo_A.png" width="150px;" alt="Logo"></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <!--메뉴1-->
                    <li style="font-weight:bold;"><a href="../member/list.php">나의 계약조회</a></li>

                    <!--메뉴2-서브메뉴-->
                    <li class="dropdown" style="font-weight:bold;">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            개인정보
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?if($member[level]=='C') {?><li><a href="../member/list_pay.php">지급이자 내역</a></li><?}?>
                            <li><a href="../member/myinfo.php">개인정보 조회</a></li>
                            <li><a href="../member/myinfo_edit.php?no=<?=$member[no]?>&id=<?=$member[id]?>">개인정보 수정</a></li>

                        </ul>
                    </li>

 <?if($member[level]!="C"){ ?>

                    <!--메뉴3-서브메뉴-->
                    <li class="dropdown" style="font-weight:bold;">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            메니저 메뉴
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="../member/list_ctr.php">계약관리 조회</a></li>
                            <li><a href="../member/list_pay.php">수수료 조회</a></li>
                            <li><a href="../member/list_member.php">회원 조회</a></li>

                        </ul>
                    </li>

					<?}?>

 <?if($member[level]=="L"){ ?>

                    <!--메뉴4-서브메뉴-->
                    <li class="dropdown" style="font-weight:bold;">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            관리자 메뉴
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="../admin/input_member.php">고객등록</a></li>
                            <li><a href="../admin/input_contract.php">계약서 등록</a></li>
                            <li><a href="../admin/input_month.php">월대차 납부입력</a></li>
                            <li><a href="../admin/input_pay.php">수수료지급 입력</a></li>
                            <li><a href="../admin/input_stop.php">고객해지,회사상환</a></li>
                            <li><a href="../admin/list_com_pay.php">금월수당지급내역</a></li>
                        </ul>
                    </li>

				<?}?>

                    <!--로그인 로그아웃-->
                    <li><a href="../member/logout.php"><span class="glyphicon glyphicon-log-in"></span> LogOut</a></li>
                </ul>
            </div>


        </div>
        <!-- End container -->
    </nav>

		<div class="container text-right">
          <?if($member[user_id]){
        echo "<font color='blue'><b>".$member[name]."(".$member[user_id].") </b> 님 환영합니다.</font> ";
        }else{?>
        <a href="./index.html"><strong>[로그인]</strong></a>
        <?}?>

       </div>

   </div>

    <!--end 헤더메뉴-->




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
