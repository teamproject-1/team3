<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_PATH_DB_LIB);

$conn = null;
$theme = null;

try {
    // GET 처리
    if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
        $year = isset($_GET["year"]) ? $_GET["year"] : 0;
        $month = isset($_GET["month"]) ? $_GET["month"] : 0;
        $day = isset($_GET["day"]) ? $_GET["day"] : 0;
        $td_id = isset($_GET["td_id"]) ? $_GET["td_id"] : 0;


        if(($year < 1) || ($month < 1) || ($day < 1)) {
            throw new Exception("파라미터 오류");
        }
        if($td_id < 1) {
            throw new Exception("파라미터 오류");
        }

        $conn = my_db_conn();

        $arr_prepare1 = [
            "year" => $year
            ,"month" => $month
            ,"day" => $day 
        ];

        $result_cal = my_board_select_cal_id($conn, $arr_prepare1);

        $arr_prepare2 = [
            "year" => $year
            ,"month" => $month
            ,"day" => $day
            ,"td_id" => $td_id
        ];

        $result_todo = my_todolist_select_cal_id($conn, $arr_prepare2);

    }else {
         // POST 처리
        //  $cal_id = isset($_POST["cal_id"]) ? $_POST["cal_id"] : 0;
         $td_id = isset($_POST["td_id"]) ? $_POST["td_id"] : 0;
         $content = isset($_POST["content"]) ? $_POST["content"] : "";
         $year = isset($_POST["year"]) ? $_POST["year"] : 0;
         $month = isset($_POST["month"]) ? $_POST["month"] : 0;
         $day = isset($_POST["day"]) ? $_POST["day"] : 0;

         if($content === "") {
            throw new Exception("파라미터 오류");
         }

         $conn = my_db_conn();

         $conn->beginTransaction();
         
         $arr_prepare = [
            "year" => $year
            ,"month" => $month
            ,"day" => $day 
        ];

        $result_cal_id = my_board_select_cal_id($conn, $arr_prepare);

        $arr_prepare = [

            "cal_id" =>$result_cal_id["cal_id"]
            ,"td_id" =>$td_id
            ,"content" =>$content
        ];

         my_board_update($conn, $arr_prepare);

        $conn->commit();

        header("Location: /detail.php?year=".$year."&month=".$month."&day=".$day."&td_id=".$td_id);
        exit;
    }
}catch(Throwable $th){
    if(!is_null($conn) && $conn->inTransaction()){
        $conn->rollBack();
    }
    require_once(MY_PATH_ERROR);
    exit;
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/update.css">
    <title>수정 페이지</title>
</head>
<body>
    <main>
        <div class="main_container">
            <form action="/update.php" method="post">
                <input type="hidden" name="td_id" value="<?php echo $result_todo["td_id"] ?>">
                <input type="hidden" name="year" value="<?php echo $result_cal["year"] ?>">
                <input type="hidden" name="month" value="<?php echo $result_cal["month"] ?>">
                <input type="hidden" name="day" value="<?php echo $result_cal["day"] ?>">
                <div class="main_container_box">
                    <div class="main_box_left"></div>
                    <div class="detail_container_box">
                        <!-- 위쪽 마스킹 테이프 -->
                        <div class="detail_top_tape" 
                            <?php if($result_cal["theme"] === '0') { ?>
                                style="background-image: url(/img/theme/animal_masking.jfif);">
                            <?php } else if($result_cal["theme"] === '1') { ?>
                                style="background-image: url(/img/theme/plant_masking.jfif);">
                            <?php } else if($result_cal["theme"] === '2') { ?>
                                style="background-image: url(/img/theme/pixel_masking.png);">
                            <?php } else { ?>
                                style="background-image: url();">
                            <?php } ?>
                        </div>
                        <div class="detail_back_green">
                        <?php if($result_cal["theme"] === '0') { ?>
                            <img src="./img/theme/animal_sticker1.png" alt="" class="detail_sticker1">
                            <img src="./img/theme/animal_sticker2.png" alt="" class="detail_sticker2">
                            <img src="./img/theme/animal_sticker3.png" alt="" class="detail_sticker3">
                        <?php } else if($result_cal["theme"] === '1') { ?>
                            <img src="./img/theme/plant_sticker1.png" alt="" class="detail_sticker1">
                            <img src="./img/theme/plant_sticker2.png" alt="" class="detail_sticker2">
                            <img src="./img/theme/plant_sticker3.png" alt="" class="detail_sticker3">
                        <?php } else if($result_cal["theme"] === '2') { ?>
                            <img src="./img/theme/pixel_sticker1.png" alt="" class="detail_sticker1">
                            <img src="./img/theme/pixel_sticker2.png" alt="" class="detail_sticker2">
                            <img src="./img/theme/pixel_sticker3.png" alt="" class="detail_sticker3">
                        <?php } else {} ?>
                            <div class="detail_back_white">
                                <!-- 연도-월-일 -->
                                <div class="detail_content_date"><?php echo $result_cal["year"]."-".str_pad((string)$result_cal["month"], 2, "0", STR_PAD_LEFT)."-".str_pad((string)$result_cal["day"], 2, "0", STR_PAD_LEFT) ?></div>
                                <div>
                                    <div class="detail_content_todo">
                                        <!-- todo 내용 -->
                                        <div style="padding-top: 5px;">
                                            <input type="text" name="content" id="content" value="<?php echo $result_todo["content"] ?>" required>
                                        </div>
                                    </div>
                                    <!-- todo 생성일 -->
                                    <div class="detail_content_timestamp"><?php echo $result_todo["todo_created_at"] ?></div>
                                </div>
                                <div>
                                    <button type="submit" class="btn_small">확인</button>
                                    <a href="/detail.php?year=<?php echo $result_cal["year"] ?>&month=<?php echo $result_cal["month"] ?>&day=<?php echo $result_cal["day"] ?>&td_id=<?php echo $result_todo["td_id"] ?>"><button type="button" class="btn_small">취소</button></a>
                                </div>
                            </div>
                        </div>
                        <!-- 아래쪽 마스킹 테이프 -->
                        <div class="detail_top_tape" 
                            <?php if($result_cal["theme"] === '0') { ?>
                                style="background-image: url(/img/theme/animal_masking.jfif);">
                            <?php } else if($result_cal["theme"] === '1') { ?>
                                style="background-image: url(/img/theme/plant_masking.jfif);">
                            <?php } else if($result_cal["theme"] === '2') { ?>
                                style="background-image: url(/img/theme/pixel_masking.png);">
                            <?php } else { ?>
                                style="background-image: url();">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="main_box_right"></div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>