<?php 

    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    // GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    // GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
    $month = isset($_GET['month']) ? $_GET['month'] : date('m');

    $date = "$year-$month-01"; // 현재 날짜
    $time = strtotime($date); // 현재 날짜의 타임스탬프
    $start_week = date('w', $time); // 1. 시작 요일
    $total_day = date('t', $time); // 2. 현재 달의 총 날짜
    $total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차


    try{
        $conn = my_db_conn();

        if(strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {
            $arr_prepare = [
                "memo_content" => $_POST["memo_content"]
            ];

            $result = my_memo_insert($conn, $arr_prepare);
        }
    }catch(Throwable $th) {
        require_once(MY_PATH_ERROR);
        exit;
    }

    $result2 = my_memo_select($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/list.css">
    <title>리스트페이지</title>
</head>
<body>

    <div class="main_container">
        <div class="main_container_box">
            <!-- 왼쪽책 -->
            <div class="main_box_left">
                <!-- 마스킹테이프 마진줬음 -->
                <div class="list_masking_tape_1"></div>
                <div class="list_masking_tape_2"></div>
                <!-- 달력출력 -->
                <div class="list_box_left_calender">
                    <div class="calendar">
                        <div class="month">
                            
                            <!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
                            <?php if ($month == 1): ?>
                                <!-- 작년 12월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year-1 ?>&month=12"> < </a>
                            <?php else: ?>
                                <!-- 이번 년 이전 월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>"> < </a>
                            <?php endif ?>

                            <h1><?php echo "$year 년 $month 월" ?></h1>
                            
                            <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                            <?php if ($month == 12): ?>
                                <!-- 내년 1월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year+1 ?>&month=1"> > </a>
                            <?php else: ?>
                                <!-- 이번 년 다음 월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>"> > </a>
                            <?php endif ?>
                        </div>

                        <!-- 월화수목금토일 -->
                        <div class="weekdays">
                            <div class="color">sun</div>
                            <div>mon</div>
                            <div class="color">tue</div>
                            <div>wed</div>
                            <div class="color">thur</div>
                            <div>fri</div>
                            <div class="color">sat</div>
                        </div>

                        <div>
                            <div>
                                <!-- 총 주차를 반복합니다. -->
                                <?php for ($day = 1, $i = 0; $i < $total_week; $i++): ?> 
                                    <div class="days"> 
                                        <!-- 1일부터 7일 (한 주) -->
                                        <?php for ($k = 0; $k < 7; $k++): ?> 
                                            <div class="day"> 
                                                <!-- 시작 요일부터 마지막 날짜까지만 날짜를 보여주도록 -->
                                                <?php if ( ($day > 1 || $k >= $start_week) && ($total_day >= $day) ): ?>
                                                    <!-- 현재 날짜를 보여주고 1씩 더해줌 -->
                                                    <a class="list_a_color" href="/list.php?year=<?php echo $year ?>&month=<?php echo $month ?>&day=<?php echo $day ?>"><?php echo $day++ ?></a>
                                                <?php endif ?>
                                            </div> 
                                        <?php endfor; ?> 
                                    </div> 
                                <?php endfor; ?> 

                            </div>
                        </div>
                      </div>
                </div>


                <!-- 메모 기능 -->
                <div class="list_box_left_memo">
                    <!-- 폼 포스트 으로보내기 -->
                    <form method="post" action="/list.php">
                        <div>
                            <input name="memo_content" required class="list_memo_input_text" placeholder="입력하세요.." type="text">
                            <button type="submit" class="list_memo_insert_btn">확인</button>
                        </div>
                    </form>        
                    <?php
                    foreach($result2 as $item) {
                    ?>
                        <div class="list_memo_box">
                            <div class="list_memo_area"><?php echo $item["memo_content"] ?></div>
                            <button class="list_memo_input_btn" type="button"> <img class="list_btn_img" src="./img/delete_icon.png" alt=""></button>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                      
            </div>
            

            <!-- 오른쪽 책 -->
            <div class="main_box_right">
                <div class="main_box_right_sticker">
                    <div><img class="list_sticker_img" src="./img/weather/list_weather_sun.png" alt=""></div>
                    <div class="list_box_right_now">2024-10-15</div>
                    <div><img class="list_sticker_img" src="./img/emotion/list_emotion_happy.png" alt=""></div>
                </div>
                <!-- 어떻게 하는지 모르겠지만? 전기수분들이 한거에서 A테그로 체크이미지 넣어서 하는것 따라함 -->
                <div class="list_box_right_detail">
                    <div class="list_box_right_detail_box">
                        <span>TO DO LIST</span>
                        <a href=""><img class="check_box__img_size" src="./img/checkbox.png" alt=""></a>
                    </div>
                    <div class="list_box_right_detail_box">
                        <span>TO DO LIST</span>
                        <a href=""><img class="check_box__img_size" src="./img/checkbox.png" alt=""></a>
                    </div>
                    <div class="list_box_right_detail_box">
                        <span>TO DO LIST</span>
                        <a href=""><img class="check_box__img_size" src="./img/checkbox.png" alt=""></a>
                    </div>
                    <div class="list_box_right_detail_box">
                        <span>TO DO LIST</span>
                        <a href=""><img class="check_box__img_size" src="./img/checkbox.png" alt=""></a>
                    </div>
                    <div class="list_box_right_detail_box">
                        <span>TO DO LIST</span>
                        <a href=""><img class="check_box__img_size" src="./img/checkbox.png" alt=""></a>
                    </div>
                </div>
              
                <div>
                    <input class="list_box_right_input_text" type="text"> <button type="button" class="btn_small">추가</button>
                    <div class="list_box_right_underscore"></div>
                </div>

            </div>
                
        </div>
        <a class="list_deco_btn" href="./update_deco.html">데코</a>
           
    </div>
    
    
</body>
</html>