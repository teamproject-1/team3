<?php 

    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    $conn = null;
    try{
        // GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
        $year = !empty($_GET['year']) ? $_GET['year'] : date('Y');
        // GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
        $month = !empty($_GET['month']) ? $_GET['month'] : date('m');
        // GET으로 넘겨 받은 day값이 있다면 넘겨 받은걸 day변수에 적용하고 없다면 현재 일
        $day = !empty($_GET['day']) ? $_GET['day'] : date('d');

        $date = "$year-$month-01"; // 현재 월 1일(문자열 2024-10-01)
        $time = strtotime($date); // 현재 월 1일의 타임스탬프
        $start_week = date('w', $time); // 1. 시작 요일  0 은 일요일 , 1은 월요일
        $total_day = date('t', $time); // 2. 현재 달의 총 날짜  주어진 월의 총 일 수 (28 ~ 31)
        $total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차

        $conn = my_db_conn();

        // GET으로 념거받는 year, month, day 값이있다면 넘겨받은걸 적용 넘겨받는값이 없다면 현제 날짜
        $arr_prepare = [
            "year" => $year
            ,"month" => $month
            ,"day" => $day
        ];
        
        //  calender_boards 의 정보 가져오기
        $result1 = my_calendar_select($conn, $arr_prepare);

        // 가져온 $result1 이 비어있으면 insert
        if(empty($result1)) {
            my_calendar_insert($conn, $arr_prepare);
            $result1 = my_calendar_select($conn, $arr_prepare);
        }

        // memo_boards 의 정보 가져오기
        $result2 = my_memo_select($conn);

        // 해당 날짜에 맞는 todolist_boards 의 정보 가져오기
        $result = my_todolist_list_select($conn, $arr_prepare);

        $arr_prepare = [
            "year" => $year
            ,"month" => $month
        ];

        // 해달날짜에 cal_id 의 이너조인으로 content가 있는 date를 가져옴
        $result_set_todolist_on_date =  my_set_todolist_on_date($conn, $arr_prepare);
        $arr_set_todolist_on_date = [];

        foreach($result_set_todolist_on_date as $item) {
            $arr_set_todolist_on_date[] = $item["date"];
        }

    }catch(Throwable $th) {
        require_once(MY_PATH_ERROR);
        exit;
    }

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
                <!-- 테마 정보를 맞아서 0~3 에 해당하는 테마 출력-->
                <!-- 마스킹 테이프 위쪽  -->
                <div class="list_masking_tape_top"
                    <?php if($result1[0]["theme"] === '0') { ?>
                        style="background-image: url(/img/theme/animal_masking.jfif);">
                    <?php } else if($result1[0]["theme"] === '1') { ?>
                        style="background-image: url(/img/theme/plant_masking.jfif);">
                    <?php } else if($result1[0]["theme"] === '2') { ?>
                        style="background-image: url(/img/theme/pixel_masking.png);">
                    <?php } else { ?>
                        style="background-image: url();">
                    <?php } ?>
                </div>
                <!-- 마스킹 테이프 아래쪽 -->
                <div class="list_masking_tape_bottom"
                    <?php if($result1[0]["theme"] === '0') { ?>
                        style="background-image: url(/img/theme/animal_masking.jfif);">
                    <?php } else if($result1[0]["theme"] === '1') { ?>
                        style="background-image: url(/img/theme/plant_masking.jfif);">
                    <?php } else if($result1[0]["theme"] === '2') { ?>
                        style="background-image: url(/img/theme/pixel_masking.png);">
                    <?php } else { ?>
                        style="background-image: url();">
                    <?php } ?>
                </div>
                <!-- 달력출력 -->
                <div class="list_box_left_calender"                 
                    <?php if($result1[0]["theme"] === '0') { ?>
                        style="background-image: url(/img/theme/cal_animal.jfif);">
                    <?php } else if($result1[0]["theme"] === '1') { ?>
                        style="background-image: url(/img/theme/cal_plant.jfif);">
                    <?php } else if($result1[0]["theme"] === '2') { ?>
                        style="background-image: url(/img/theme/cal_pixel.jfif);">
                    <?php } else { ?>
                        style="background-color: #fff;">
                    <?php } ?>

                    <div class="calendar">
                        <div class="month">
                            
                            <!-- 현재가 1월이라 이전 달이 작년 12월인경우 -->
                            <?php if ($month == 1) { ?>
                                <!-- 작년 12월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year-1 ?>&month=12&day=1"> < </a>
                            <?php } else { ?>
                                <!-- 이번 년 이전 월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year ?>&month=<?php echo $month-1 ?>&day=1"> < </a>
                            <?php } ?>

                            <h1><?php echo $year."년 ".str_pad((string)$month, 2, "0", STR_PAD_LEFT)."월" ?></h1>
                            
                            <!-- 현재가 12월이라 다음 달이 내년 1월인경우 -->
                            <?php if ($month == 12) { ?>
                                <!-- 내년 1월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year+1 ?>&month=1&day=1"> > </a>
                            <?php } else { ?>
                                <!-- 이번 년 다음 월 -->
                                <a class="list_month_btn" href="/list.php?year=<?php echo $year ?>&month=<?php echo $month+1 ?>&day=1"> > </a>
                            <?php } ?>
                        </div>

                        <!-- 월화수목금토일 -->
                        <div class="weekdays">
                            <div class="color">sun</div>
                            <div class="color2">mon</div>
                            <div class="color">tue</div>
                            <div class="color2">wed</div>
                            <div class="color">thur</div>
                            <div class="color2">fri</div>
                            <div class="color">sat</div>
                        </div>

                        <div>
                            <div>
                                <!-- 총 주차를 반복합니다. -->
                                <?php for ($n = 1, $i = 0; $i < $total_week; $i++) { ?> 
                                    <div class="days"> 
                                        <!-- 1일부터 7일 (한 주) -->
                                        <?php for ($k = 0; $k < 7; $k++) { ?> 
                                            <a class="list_a_color day"
                                                <?php if(!($i === 0 && $k < $start_week)) {
                                                    if($n <= $total_day) { ?>
                                                    href="/list.php?year=<?php echo $year ?>&month=<?php echo $month ?>&day=<?php echo $n ?>"
                                                    <?php } 
                                                } ?>>
                                                <!-- 시작 요일부터 마지막 날짜까지만 날짜를 보여주도록 -->
                                                <?php if ( ($n > 1 || $k >= $start_week) && ($total_day >= $n) ) { ?>
                                                    <!-- 현재 날짜를 보여주고 1씩 더해줌 -->
                                                    <p <?php if($result1[0]["day"] === $n) { ?> class="list_day_color" <?php } ?>>

                                                        <?php echo $n ?>
                                                    </p>
                                                    <!-- 현재 선택된 날짜를 arr_set_todolist_on_date 에서 찾아서 있을시 true 반환  -->
                                                    <?php if(in_array($year."-".$month."-".$n, $arr_set_todolist_on_date)) { ?><div class="stamp"></div> <?php } ?>
                                                    <?php $n++ ?>
                                                <?php } ?>
                                            </a>
                                        <?php } ?> 
                                    </div> 
                                <?php } ?> 

                            </div>
                        </div>
                      </div>
                </div>


                <!-- 메모 기능 -->
                <div class="list_box_left_memo scrollable">
                    <!-- 폼 포스트 으로보내기 -->
                    <form method="post" action="/memo_insert.php">
                        <div>
                            <input name="memo_content" required class="list_memo_input_text" placeholder="입력하세요.." type="text" maxlength="50">
                            <button type="submit" class="list_memo_insert_btn">확인</button>
                        </div>
                        <input type="hidden" name="year" value="<?php echo $year ?>">
                        <input type="hidden" name="month" value="<?php echo $month ?>">
                        <input type="hidden" name="day" value="<?php echo $day ?>">
                    </form>        
                    <?php
                    foreach($result2 as $item) {
                    ?>
                        <form method="post" action="/memo_delete.php">
                            <div class="list_memo_box">
                                <div class="list_memo_area"><?php echo $item["memo_content"] ?></div>
                                <button class="list_memo_input_btn" type="submit"> <img class="list_btn_img" src="./img/delete_icon.png" alt=""></button>
                            </div>
                            <input type="hidden" name="memo_id" value="<?php echo $item["memo_id"] ?>">
                            <input type="hidden" name="year" value="<?php echo $year ?>">
                            <input type="hidden" name="month" value="<?php echo $month ?>">
                            <input type="hidden" name="day" value="<?php echo $day ?>">
                        </form>
                    <?php
                    }
                    ?>
                </div>

                      
            </div>
            

            <!-- 오른쪽 책 -->
            <div class="main_box_right">
                <div class="main_box_right_sticker">
                    <div>
                        <?php if(!empty($result1[0]["weather"])) { ?>
                            <img class="list_sticker_img" src="<?php echo $result1[0]["weather"] ?>" alt="">
                        <?php } ?>
                    </div>
                    <div class="list_box_right_now"><?php echo $year."-".str_pad((string)$month, 2, "0", STR_PAD_LEFT)."-".str_pad((string)$day, 2, "0", STR_PAD_LEFT) ?> </div>
                    <div>
                        <?php if(!empty($result1[0]["emotion"])) { ?>
                            <img class="list_sticker_img" src="<?php echo $result1[0]["emotion"] ?>" alt="">
                        <?php } ?>
                    </div>
                </div>
                
                <!-- TODOLIST 출력 -->
                <div class="list_box_right_detail scrollable">
                    <?php foreach($result as $item) { ?>
                        <div class="list_box_right_detail_box">
                            <a class="list_box_right_atag" href="detail.php?year=<?php echo $year ?>&month=<?php echo $month ?>&day=<?php echo $day ?>&td_id=<?php echo $item["td_id"] ?>">
                                <span <?php if($item["check_todo"] === 1) { ?>class="check_todo_complete"<?php } else {} ?>><?php echo $item["content"] ?></span>
                            </a>
                            <form action="./todolist_check.php" method="post">
                                <input type="hidden" id="cal_id" name="cal_id" value="<?php echo $item["cal_id"] ?>">
                                <input type="hidden" id="year" name="year" value="<?php echo $item["year"] ?>">
                                <input type="hidden" id="month" name="month" value="<?php echo $item["month"] ?>">
                                <input type="hidden" id="day" name="day" value="<?php echo $item["day"] ?>">
                                <input type="hidden" id="td_id" name="td_id" value="<?php echo $item["td_id"] ?>">
                                <?php if($item["check_todo"] === 0) { ?>
                                    <input type="hidden" id="check_todo" name="check_todo" value="1">
                                    <button type="submit" class="detail_content_checkbox"><img src="./img/checkbox.png" alt="" style="width: 40px; height: 40px;"></button>
                                <?php } else { ?>
                                    <input type="hidden" id="check_todo" name="check_todo" value="0">
                                    <button type="submit" class="detail_content_checkbox"><img src="./img/checkbox_checked.png" alt="" style="width: 40px; height: 40px;"></button>
                                <?php } ?>
                            </form>
                        </div>
                    <?php } ?>
                </div> 
                <!-- TODOLIST 추가 -->
                <form method="POST" action="/todolist_insert.php">
                    <div>
                        <input name="content" required class="list_box_right_input_text" type="text" maxlength="12"> <button type="submit" class="btn_small">추가</button>
                        <div class="list_box_right_underscore"></div>
                        <input type="hidden" name="year" value="<?php echo $year ?>">
                        <input type="hidden" name="month" value="<?php echo $month ?>">
                        <input type="hidden" name="day" value="<?php echo $day ?>">
                    </div>
                </form>

            </div>
                
        </div>
        <!-- 데코 버튼 데코업데이트페이지로 이동 -->
        <a class="list_deco_btn" href="./update_deco.php?year=<?php echo $year ?>&month=<?php echo $month ?>&day=<?php echo $day ?>">데코</a>
           
    </div>
    
    
</body>
</html>