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
