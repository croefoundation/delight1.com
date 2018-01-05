<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

if($member[user_id]!="admin")Error("관리자 메뉴입니다.");

include('../_header.php');
?>

<?//여기서는 회원을 조회해서 하나씩 그 회원의 계약수와 총약정금액을 계산해서 회원테이블에 저장하기로 한다.***************************?>
<?
//0.회원 테이블 조회-를 우선한다.----------
$query_m= "select * from member where id='mem'";
mysql_query("set names ust8", $connect);
$result_m= mysql_query($query_m, $connect);
while($dm = mysql_fetch_array($result_m)){ //while문 시작--------------------------------

if($dm[user_id]){   //회원 하나를 불러올때 마다

//1.그회원 계약서 테이블을 불러오고
     $query_r= "select count(*) from contract where name='$dm[name]' "; //갯수 우선 세고
     mysql_query("set names ust8", $connect);
     $result_r= mysql_query($query_r, $connect);
     $dr= mysql_fetch_array($result_r);
     $total_ctr=$dr[0];

     $query_rr= "select * from contract where name='$dm[name]' "; //계약서 내용 불러와서
     mysql_query("set names ust8", $connect);
     $result_rr= mysql_query($query_rr, $connect);

     $tnt=0;   //개수도 세고.. 위에 있지만
     $total_money=0;  //약정금액합의 합을 계산
     while($drr = mysql_fetch_array($result_rr)){    //조건에 맞는 한
     $total_money+=$drr[money];      // 합치고
     $tnt++;
     }

//     echo $dm[name]."님의 계약수".$tnt."  약정금액 합은 ".$total_money."<br>";


      //계약수와 약정금액을 회원테이블에 업데이트 시키고
     $query_m="update member set total_ctr='$total_ctr', total_money='$total_money' where name='$dm[name]'";
     mysql_query($query_m,$connect);


//2.수당 테이블을 불러오고
     $query_p= "select * from payment where name='$dm[name]' ";
     mysql_query("set names ust8", $connect);
     $result_p= mysql_query($query_p, $connect);
     //$dp= mysql_fetch_array($result_p);

     $tnt=0;   //개수도 세고.. 위에 있지만
     $total_bonus=0;  //보너스액합의 합을 계산
     while($dp = mysql_fetch_array($result_p)){    //조건에 맞는 한
     $total_bonus+=$dp[amount];      //보너스를 합치고
     $tnt++;
     }

echo $dm[name]."님의 계약수".$tnt."  약정금액 합은 ".$total_money."  수당금액 합은 ".$total_bonus."<br>";

     //bonus를 회원테이블에 업데이트 시키고
     $query_pi="update member set total_bonus='$total_bonus' where name='$dm[name]'";
     mysql_query($query_pi,$connect);


}  //if end
}  //while end





     ?>






등록되었습니다.
