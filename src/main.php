<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_PATH_DB_LIB);

// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
$year = !empty($_GET['year']) ? $_GET['year'] : date('Y');
// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
$month = !empty($_GET['month']) ? $_GET['month'] : date('m');
// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
$day = !empty($_GET['day']) ? $_GET['day'] : date('d');

$date = "$year-$month-01"; // 현재 날짜
$time = strtotime($date); // 현재 날짜의 타임스탬프
$start_week = date('w', $time); // 1. 시작 요일
$total_day = date('t', $time); // 2. 현재 달의 총 날짜
$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차



?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/main.css">
    <title>메인페이지</title>
</head>
<body>
    <main>
        <div class="main_container">
            <div class="main_container_box">
                    <div class="main_box_left">
                        <div class= "main_img_box">
                             <div class="main_img">                                
                             </div>
                        </div>
                    </div>
            <div class="main_box_right">
                    <div class="stick_box">                        
                        <div class="stick">
                            <img src="/img/main_small_img.png" alt="" class=stick_img>                                
                        </div>                       
                    </div>                   
                    <div class="main_box_right_calender">
                <div class="calendar">
                        <div class="month">
                            <!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
                            <?php if ($month == 1) { ?>
                                <!-- 작년 12월 -->
                                <a class="main_month_btn" href="/main.php?year=<?php echo $year-1 ?>&month=12"> < </a>
                                <?php } else { ?>
                                <!-- 이번 년 이전 월 -->
                                <a class="main_month_btn" href="/main.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"> < </a>
                            <?php } ?>
                            <!-- .str_pad 함수 사용해서 x월 표현 -->
                            <h1><?php echo $year."년 ".str_pad($month, 2, "0", STR_PAD_LEFT)."월" ?></h1>
                            <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                            <?php if ($month == 12) { ?>
                                <!-- 내년 1월 -->
                                <a class="main_month_btn" href="/main.php?year=<?php echo $year+1 ?>&month=1"> > </a>
                            <?php } else { ?>
                                <!-- 이번 년 다음 월 -->
                                <a class="main_month_btn" href="/main.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"> > </a>
                            <?php } ?>
                        </div>
                        <!-- 월화수목금토일 -->
                        <div class="weekdays">
                            <div class="color">sun</div>
                            <div>mon</div>
                            <div class="color">tue</div>
                            <div>wed</div>
                            <div class="color">thur</div>
                            <div>fri</div>
                            <div class="color" >sat</div>
                        </div>
                        <div>
                            <div>
                                <!-- 총 주차를 반복합니다. -->
                                <?php for ($d = 1, $i = 0; $i < $total_week; $i++) { ?>                                
                                    <a class="days" href="/list.php?year=<?php echo $year ?>&month=<?php echo $month ?>&day=<?php echo $d ?>">
                                        <!-- 1일부터 7일 (한 주) -->
                                        <?php for ($k = 0; $k < 7; $k++) { ?>
                                            <!-- 토요일 파란색 , 일요일 빨간색 -->
                                            <div class="day main_a_color <?php echo ($k == 0 ? 'sunday' : ($k == 6 ? 'saturday' : '')); ?>">
                                            <!-- 시작 요일부터 마지막 날짜까지만 날짜를 보여주도록 -->
                                                <?php if ( ($d > 1 || $k >= $start_week) && ($total_day >= $d)) { ?>
                                                    <!-- 현재 날짜를 보여주고 1씩 더해줌 -->                               
                                                    <?php echo $d++ ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>