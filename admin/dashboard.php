
<?

/// 수당테이블에서 payment ////////////////////////////////////////////////////

//1. 직급별 쿼리조건

if($member[level]=='L'){  //회사를 제외한 나머지는 자기수당만
          $query_pt ="select * from payment where id='pay'";
     }else{
          $query_pt = "select * from payment where user_id='$member[user_id]'";
     }


//1-1.총발생수당, 총지급수당 구해서 회원테이블에 저장하기
          $result_pt=mysql_query($query_pt,$connect);

          $total_bonus=0; //발생수당
          $total_bonus_out=0; //지급완료된 수당누적
          $pay_cus=0; //고객지급이자(팀장-자기이자)
          $pay_commission=0; //커미션수당의 합
          $pay_incentive=0; //인센티브의 합
          $pay_bonbu=0; //본부장수당의 합

          while($data_pt = mysql_fetch_array($result_pt)){
               $total_bonus+=$data_pt[amount]; //총발생수당의 합
               if($data_pt[pay_type]=='pay_c'){$pay_cus+=$data_pt[amount];}
               if($data_pt[pay_type]=='pay_t'){$pay_commission+=$data_pt[amount];}
               if($data_pt[pay_type]=='pay_i'){$pay_incentive+=$data_pt[amount];}
               if($data_pt[pay_type]=='pay_top'){$pay_bonbu+=$data_pt[amount];}

               if($data_pt[pay_state]=="지급완료"){
               $total_bonus_out+=$data_pt[amount];}  //지급완료된 수당의 합
          }

          $total_bonus_not =$total_bonus - $total_bonus_out; // 총발생 미지급수당

          // echo "총발생수당=".$total_bonus."<br>";
          // echo "총지급수당=".$total_bonus_out."<br>";
          // echo "총미지급수당=".$total_bonus_not."<br>";
          // echo "총커미션수수료=".$pay_commission."<br>";




/// 2. 계약 테이블에서 contract ////////////////////////////////////////////////////

//2-1). 직급별 쿼리 조건부여

if($member[level]=='B'){
          //전체 계약수
          $query_t ="select * from contract where id_manager='$member[name]' or id_incentive='$member[name]' or id_top='$member[name]' or user_id='$member[user_id]'";
          }

if($member[level]=='C'){
          $query_t = "select * from contract where user_id='$member[user_id]'";
          }

if($member[level]=='L'){
          $query_t ="select * from contract where id='ctr'";
          }


///2-2).계약건수합, 계약 약정금액 총합,//전체계약 약정금.이자 계약건수  조회

$total_money_t=0; //약정금 총액
$total_month=0;//월대차금액 합계
$total_month=0;//월대차금액 합계
$total_new=0; //신규계약 합계
$total_old=0; //구계약 연장합계

$total_int=0; //약정금이자총액
$total_month_int=0; //월대차 이자총액
$tnt=0; //계약건수
$tnm=0;//월대차건수
$tn_new=0; //신규계약 건수
$tn_old=0;//구계약 연장건수

$sum_mm_total=0; //월대차 납부금 합

$result_t=mysql_query($query_t,$connect);
while($data_t = mysql_fetch_array($result_t)){
     $total_money_t+=$data_t[money];

     if($data_t[type]=="월대차/만기지급"){
          $total_month+=$data_t[money];    //월대차 약정금의 합
          $total_month_int+=$data_t[sum_cus];//월대차 이자의 합
          $sum_mm_total+=$data_t[sum_mm]; //월대차 납입금의 합
          $tnm++;
     }

     if($data_t[newtype]=="연장"){
          $total_old+=$data_t[money];    //연장계약의 합
          $tn_old++;   //연장건수
     }

     $total_int+=$data_t[sum_cus];
     $tnt++;
}

$total_money = ($total_money_t)-$total_month; //월대차를 제외한 전체 약정금의 합
$total_int_net =($total_int)-$total_month_int; //월대차 이자를 제외한 이자의 합
$total_new=$total_money-$total_old; //신규계약의 합=전체계약-연장계약
$tn_new=$tnt-$tn_old; //신규건수=전체건수-연장건수

//본부장제도 -현재직급//본부장 잔여금액
$bonbu=2000000000;
if($total_money<$bonbu){
$now_level="팀장";//현재직급
$bonbu_vol=$bonbu-$total_money;//본부장 부족실적
}else{
$now_level="본부장";//현재직급
}


// echo "총 계약건수=".$tnt."<br>";
// echo "총 월대차건수=".$tnm."<br>";
// echo "총 계약금액=".$total_money."<br>";
// echo "총 월대차금액=".$total_month."<br>";
// echo "총 발생이자=".$total_int."<br>";
// echo "현재 직급은=".$now_level."<br>";
// echo "본부장까지 남은실적은=".$bonbu_vol."<br>";





//매출,계약수,보너스 합계 <회원 정보>업데이트//////////////////////////////////////
$query="update member set
total_money='$total_money',
total_month='$total_month',
total_ctr='$tnt',
total_bonus='$total_bonus',
total_bonus_out='$total_bonus_out'

where user_id='$member[user_id]' ";
mysql_query("set names utf8", $connect);
mysql_query($query, $connect);

mysql_close;



/// 3. 회원 테이블에서 MeMber ////////////////////////////////////////////////////

///3-1.회원수 관리회원/직접 추천회원

$total_mem=0; //하위 관리회원수 합계
$total_direct=0; //하위 직접추천 회원수 계산

$query_m="select * from member where id_manager='$member[name]' or id_incentive='$member[name]' or id_top='$member[name]' or user_id='$member[user_id]'";
$result_m=mysql_query($query_m,$connect);
while($data_m= mysql_fetch_array($result_m)){
$total_mem++;
if($member[name]==$data_m[id_manager]){
     $total_direct++;}
}//while문 종료

// echo "총관리 회원수=".$total_mem."<br>";
// echo "직접추천 회원수=".$total_direct."<br>";


?>



<?
///////만기일을 기준으로 오늘날짜와  몇개월 이내에 있는 계약을 찾아서 출력하기
$total_dday=0; $total_non=0;
$d=2;//몇개월 후 인지 변수 생성설정**************************************
$query_m ="select * from contract where id_manager='$member[name]' or id_incentive='$member[name]'";
$result_m=mysql_query($query_m,$connect);
while($data_m= mysql_fetch_array($result_m)){
     $date=$data_m[ctr_end];
     $date_y=substr($date,0,4);
     $date_m=substr($date,5,2);
     $date_d=substr($date,8,2);
     $dt=computeMonth($date_y,$date_m,$date_d,0);   //날짜 말일적용하여 계산 ..맨끝이 정한 날로부터 얼마나 나중이나ㅑ 젼이냐.., 포멧은 2017-10-23꼴 (-)

if(strtotime("+0 day")<strtotime($dt) and strtotime($dt)<strtotime("+$d month") ) {
     $total_dday++;
//이곳에 만기일 임박 리스트출력


}else{
     $total_non++;
}
}
//echo $d."개월 이내 마감예정 계약수=".$total_dday."<br>";

?>
