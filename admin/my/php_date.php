<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB컨넥트
$member=member();  //회원정보

//if($member[user_id]!="admin")Error("관리자 메뉴입니다.");

include('../_header.php');
?>
<?


//날짜계산 함수
    function computeMonth($year, $month, $day, $addMonths) {
        $month += $addMonths;
        $endDay = getMonthEndDay($year, $month);//ここで、前述した月末日を求める関数を使用します
        if($day > $endDay) $day = $endDay;
        $dt = mktime(0, 0, 0, $month, $day, $year);//正規化
        return date("Y-m-d", $dt);
    }

    function getMonthEndDay($year, $month) {
        $dt = mktime(0, 0, 0, $month + 1, 0, $year);
        return date("d", $dt);
    }

?>
<?

//만기일을 기준으로 오늘날짜와 2개월 이내에 있는 계약을 찾아서 출력하기
//먼저 만기일을 구한다. 내 계약중에 만기일을 구해서..
//우선 날짜 변환을 하고..


$date="2017-07-08";
$date_y=substr($date,0,4);
$date_m=substr($date,5,2);
$date_d=substr($date,8,2);

$dt=computeMonth($date_y,$date_m,$date_d,0);   //날짜 말일적용하여 계산 ..맨끝이 정한 날로부터 얼마나 나중이나ㅑ 젼이냐..
$dt1=computeMonth($date_y+1,$date_m,$date_d,0);   //각항을 더해도 되여..연산으로
echo $dt."<br>";

$today=date("Y-m-d"); //뒤에 속성없으면 오늘을 표시
echo $today."<br>";


$dif= (strtotime($today) - strtotime($date))/86400;
echo $dif."일 차이입니다<br>";

echo "오늘부터 2개월 후 날은".date("Y-m-d",strtotime("+2 month"));  //따움표를 하고 오늘로부터 3일후 ,반드시 콤마를 해야 한데요.
echo "<br>";

echo "오늘을 표현하는 계산수".strtotime("+0 day");  //오늘을 계산할수 있는 숫자로 표시
echo "<br>";


//echo strtotime("+2 month")-strtotime($date);
if(strtotime("+0 day")<strtotime($date) and strtotime($date)<strtotime("+2 month") ) {
echo "yes";
echo intval(strtotime($date));
}else{ echo "no";}

echo "<br>";


//-------------------------------------------------------------

$i=10;//몇개월 후인지 변수
$total_dday=0; $total_non=0; //하위 관리회원수 계산
$query_m ="select * from contract where id_manager='$member[name]' or id_incentive='$member[name]'";
$result_m=mysql_query($query_m,$connect);
while($data_m= mysql_fetch_array($result_m)){
     $date=$data_m[ctr_end];
     $date_y=substr($date,0,4);
     $date_m=substr($date,5,2);
     $date_d=substr($date,8,2);
     $dt=computeMonth($date_y,$date_m,$date_d,0);   //날짜 말일적용하여 계산 ..맨끝이 정한 날로부터 얼마나 나중이나ㅑ 젼이냐.., 포멧은 2017-10-23꼴 (-)
echo $dt;
if(strtotime("+0 day")<strtotime($dt) and strtotime($dt)<strtotime("+$i month") ) {
      //출력은 안되고 참,거짓만 판단 strtotime 함수는 날짜가 반드시 2017-05-20 꼴로 -로 연결되야
echo "구간내 존재<br>";
     $total_dday++;
}else{
     $total_non++;
echo "구간박 존재 <br>";}
}
echo "<br>";
     echo $total_dday."<br>";
     echo $total_non."<br>";


?>




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
