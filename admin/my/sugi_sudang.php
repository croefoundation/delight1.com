<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

//if($member[user_id]!="admin")Error("관리자 메뉴입니다.");
include('../lib/lib.php'); //시간함수외
include('../_header.php');

$Search_text=$_GET[Search_text];
$dy=$_GET[Search_year];
$dm=$_GET[Search_month];
?>

<form action='<?=$PHP_SELE?>'>
   <td height='20' colspan='5' bgcolor='#FFFFFF' align='right'>
        <label class="btn btn-default " >수당지급 월 조회 &nbsp;
        <select name='Search_year' class="input-sm">
            <option value='2017'>2017년
            <option value='2018'>2018년
            <option value='2019'>2019년
            <option value='2020'>2020년
        </select>

        <select name='Search_month' class="input-sm">
            <option value=''>월선택
            <option value='01'>1월
            <option value='02'>2월
            <option value='03'>3월
            <option value='04'>4월
            <option value='05'>5월
            <option value='06'>6월
            <option value='07'>7월
            <option value='08'>8월
            <option value='09'>9월
            <option value='10'>10월
            <option value='11'>11월
            <option value='12'>12월
        </select>
     상세조회
        <input type='text' name='Search_text' size='25' class="input-sm" placeholder="해당날짜/성명등.. ">
        <input class="btn btn-inverse btn-sm" type='submit' value='Search'>
</form>
<table class="table">
     <tr>
     <td>번호</td><td>성명</td><td>수당종류</td><td>수당지급일</td><td>수당금액</td>
</tr>
<?


$where="(pay_name like '%$Search_text%' or pay_date like '%$Search_text%' or name like '%$Search_text%'
      or amount like '%$Search_text%' or ctr_no like '%$Search_text%' or pay_state like '%$Search_text%'
      or period like '%$Search_text%' or ctr_name like '%$Search_text%' or period like '%$Search_text%'
)";

$dd="01";
echo "<br>".$dy."년 ".$dm."월 수당 조회입니다. <br>";
$total=0;$cnt=1;$sum=0;
for ($i=1; $i <32; $i++) {
$di=computeMonth($dy,$dm,$i,0);
$query ="select * from payment where pay_date='$di' and $where order by name asc, pay_type desc";
$result=mysql_query($query,$connect);
while($data= mysql_fetch_array($result)){?>

     <tr><td><?=$cnt?></td><td><?=$data[name]?></td><td><?=$data[pay_name]?></td><td><?=$data[pay_date]?></td><td><?=number_format($data[amount])?></td></tr>

<?
        $total++; $cnt++; $sum+=$data[amount];
}//while문 끝
}

echo "총갯수는=".$total."<br>";
echo "수당합계는는=".number_format($sum)."<br>";




//일단 모든 수당테이블에서.. 지급일을 검색해서
//1.같은 달인경우 모은다..
//2.그중에 같은 날인경우 모은다
//3. 그중에 같은 사람은 모운다..
//4. 그다음 다 합한다.
//날짜를 전과 비교해서 다르면 저장 , 같으면 패스


?>
</table>

등록되었습니다.




<!--
mktime(시,분,초,월,일,년) 유닉스 타임(타임스탬프:1970년을기준으로부터 1초단위숫자)으로 값을 출력합니다.
타임스탬프를 날짜형식으로 볼수 있는 함수가 date 입니다.
date는 날짜가 들어가지 않으면 기본적으로 오늘을 뜻합니다.
-- date("Y-m-d") ==> 오늘 날짜

mktime 으로 얻은 값을 date 함수로 특정 형식으로 출력
date("Y-m-d", mktime(0, 0, 0, 12, 32, 1997)); ==> 1998-01-01

출력의 할때 편리한 점은 1월32일은 2월1 일로 나온다는 것입니다.
그럼 2005년 1월부터 100일 지난 날은 몇일일까요?
응용 date("Y-m-d", mktime(0, 0, 0, 0 , 1, 101, 2005)); ==> 2005년 04월 11일
(1월1일은 포함하면 안되겠죠? 그래서 하루 더 증가~)
출력의 기본입니다.

계산.
기본연산은 strtotime("각종연산") 으로 합니다.
타임스탬프를 리턴합니다.
이말은 date 형으로 출력할 수 있다는 말입니다. ^^

strtotime 은 날짜가 들어가지 않으면 기본적으로 오늘을 뜻합니다.
그리고 이 함수 또한 일수가 넘어가면 다음달로 계산됩니다.

----- strtotime("+3 day") => 오늘에서 3일 후, 물론 달이 넘어가면 1일로 계산됨
이 함수를 개인적으로 좋아하는 이유가 mktime 을 사용할 필요가 없다는 점입니다.
(필요가 있을 경우를 찾아주세요. ㅡ_-+)

date("Y년 m월 d일 h:m:s",mktime(12,12,1,1,2,2005))
---date("Y년 m월 d일 h:m:s",strtotime("2005-01-02 12:12:01"))

이 두 함수는 같은 2005년 01월 02일 12:01:01 을 나타냅니다.
물론 사용하기도 strtotime 이 훨씬 쉽습니다.

그럼 2005년 1월부터 100일 지난 날은 몇일인지 strtotime 을 이용해서 확인해봅시다.
--응용 date("Y-m-d", strtotime("2005-01-01 +100 day")); ==> 2005년 04월 11일
위에서
+100 day 는 +2 month 나 +10 year 와 같이 특정 연산이 가능합니다.
그래서 더욱 멋지게 보입니다. ㅡ_-+

두날짜의 연산은 타임스탬프로 두날짜의 차이값을 얻어서 86400 (60초*60분*24시) 로 나누면 몇일인지 나옵니다.
---intval((strtotime("2005-01-10")-strtotime("2005-01-02"))/86400)    =>    8

이만하면 PHP 에서 웬만한 날짜 계산을 하실 수 있습니다.

------------------------------------
$startDate="2006-05-01";
$endDate="2006-07-01";
구분자를 사용하여 배열로 만듭니다.
$arrStartDate=explode("-",$startDate);
$arrEndDate=explode("-",$endDate);
$startTime=mktime(0,0,0,$arrStartDate[1],$arrStartDate[2],$arrStartDate[0]);
$endTime=mktime(0,0,0,$arrEndDate[1],$arrEndDate[2],$arrEndDate[0]);

echo "두 날짜의 차이는 ";
echo NUMBER_FORMAT(intval(($endTime-$startTime)/86400));
echo "일 입니다";


 -->
