<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    $conn = null;
    try{
        // $cal_id = isset($_GET["cal_id"]) ? $_GET["cal_id"] : 0;  // 달력 id
        // $td_id = isset($_GET["td_id"]) ? $_GET["td_id"] : 0;  // 투두리스트 id

        $cal_id = 1;
        $td_id = 1;

        if($cal_id < 1) {
            throw new Exception("파라미터 오류");
        }
        if($td_id < 1) {
            throw new Exception("파라미터 오류");
        }

        $conn = my_db_conn();

        $arr_prepare1 = [
            "cal_id" => $cal_id
        ];

        $result_cal = my_board_select_cal_id($conn, $arr_prepare1);

        $arr_prepare2 = [
            "cal_id" => $cal_id
            ,"td_id" => $td_id
        ];

        $result_todo = my_todolist_select_cal_id($conn, $arr_prepare2);

    } catch(Throwable $th) {
        require_once(MY_PATH_ERROR);  // 에러 페이지
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/detail.css">
    <title>상세 페이지</title>
</head>
<body>
    <main>
        <div class="main_container">
            <div class="main_container_box">
                <div class="main_box_left"></div>
                <div class="detail_container_box">
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
                        <a href="./delete.php">
                            <button type="button" class="btn_small detail_delete">
                                <img src="./img/delete_icon.png" alt="" class="detail_delete_img">
                                <img src="./img/delete_hover_icon.png" alt="" class="detail_delete_hover">
                            </button>
                        </a>
                        <div class="detail_back_white">
                            <div class="detail_content_date"><?php echo $result_cal["year"]."-".$result_cal["month"]."-".$result_cal["day"] ?></div>
                            <div>
                                <div class="detail_content_todo">
                                    <form action="./detail.php" method="post">
                                        <div>
                                            <button type="submit" style="background-image: url(./img/checkbox.png);" class="detail_content_checkbox"></button>
                                        </div>
                                    </form>
                                    <div style="padding-top: 5px;"><?php echo $result_todo["content"] ?></div>
                                </div>
                                <div class="detail_content_timestamp"><?php echo $result_todo["todo_created_at"] ?></div>
                            </div>
                            <div>
                                <a href="./update.php"><button type="button" class="btn_small">수정</button></a>
                                <a href="./list.php"><button type="button" class="btn_small">취소</button></a>
                            </div>
                        </div>
                    </div>
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