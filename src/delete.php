<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    $conn = null;
    try{
        if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
            $year = isset($_GET["year"]) ? $_GET["year"] : 0;  // 달력 year
            $month = isset($_GET["month"]) ? $_GET["month"] : 0;  // 달력 month
            $day = isset($_GET["day"]) ? $_GET["day"] : 0;  // 달력 day
            $td_id = isset($_GET["td_id"]) ? $_GET["td_id"] : 0;  // 투두리스트 id

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
        } else {
            $year = isset($_POST["year"]) ? $_POST["year"] : 0;
            $month = isset($_POST["month"]) ? $_POST["month"] : 0;
            $day = isset($_POST["day"]) ? $_POST["day"] : 0;
            $td_id = isset($_POST["td_id"]) ? $_POST["td_id"] : 0;

            // PDO Instance
            $conn = my_db_conn();

            // Transaction Start
            $conn->beginTransaction();

            $arr_prepare1 = [
                "year" => $year
                ,"month" => $month
                ,"day" => $day
            ];

            $result_cal = my_board_select_cal_id($conn, $arr_prepare1);

            $arr_prepare2 = [
                "cal_id" => $result_cal["cal_id"]
                ,"td_id" => $td_id
            ];

            my_todolist_delete($conn, $arr_prepare2);
            $conn->commit();

            header("Location: /list.php?year=".$year."&month=".$month."&day=".$day);
            exit;
        }
        

    } catch(Throwable $th) {
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
    <link rel="stylesheet" href="./css/delete.css">
    <title>삭제 페이지</title>
</head>
<body>
    <main>
        <div class="main_container">
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
                        <!-- 스티커 -->
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
                            <div class="delete_comment">
                                <p>삭제하시면 영구적으로 사라집니다.</p>
                                <p>정말로 삭제 하시겠습니까?</p>
                            </div>
                            <div class="detail_content_date"><?php echo $result_cal["year"]."-".str_pad((string)$result_cal["month"], 2, "0", STR_PAD_LEFT)."-".str_pad((string)$result_cal["day"], 2, "0", STR_PAD_LEFT) ?></div>
                            <div>
                                <div class="detail_content_todo">
                                    <div style="padding-top: 5px;"><?php echo $result_todo["content"] ?></div>
                                </div>
                                <div class="detail_content_timestamp"><?php echo $result_todo["todo_created_at"] ?></div>
                            </div>
                            <form action="./delete.php" method="post">
                                <div>
                                    <input type="hidden" id="year" name="year" value="<?php echo $result_cal["year"] ?>">
                                    <input type="hidden" id="month" name="month" value="<?php echo $result_cal["month"] ?>">
                                    <input type="hidden" id="day" name="day" value="<?php echo $result_cal["day"] ?>">
                                    <input type="hidden" id="td_id" name="td_id" value="<?php echo $result_todo["td_id"] ?>">
                                    <button type="submit" class="btn_small">삭제</button>
                                    <a href="./detail.php?year=<?php echo $result_cal["year"]."&month=".$result_cal["month"]."&day=".$result_cal["day"] ?>&td_id=<?php echo $result_todo["td_id"] ?>"><button type="button" class="btn_small">취소</button></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 아래쪽 마스킹 테이프 -->
                    <div class="detail_bottom_tape"
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
        </div>
    </main>
</body>
</html>