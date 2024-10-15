<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_PATH_DB_LIB);

$conn = null;
$weather = null;
$emotion = null;
$theme = null;


try {
    if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
        // GET 처리
        $cal_id = isset($_GET["cal_id"]) ? $_GET["cal_id"] : 0;
        $year = isset($_GET["year"]) ? $_GET["year"] : 0;
        $month = isset($_GET["month"]) ? $_GET["month"] : 0;
        $day = isset($_GET["day"]) ? $_GET["day"] : 0;
        // $cal_id = 1;

        if($cal_id < 1) {
            throw new Exception("파라미터 오류");
        }

        $conn = my_db_conn();

        $arr_prepare = [
            "cal_id" => $cal_id
        ];

        $result = my_board_select_cal_id($conn, $arr_prepare);

    }else {
        $conn = my_db_conn();
        $conn->beginTransaction();
        // POST 처리
        $cal_id = isset($_POST["cal_id"]) ? $_POST["cal_id"] : 0;
        $weather = isset($_POST["weather"]) ? $_POST["weather"] : "";
        $emotion = isset($_POST["emotion"]) ? $_POST["emotion"] : "";
        $theme = isset($_POST["theme"]) ? $_POST["theme"] : "";
        if($cal_id < 1) {
            throw new Exception("파라미터 오류");
        }


        // 날씨 POST 처리시 출력
        if($_POST["weather"] === "weather_sun") {
            $weather = "/img/weather/list_weather_sun.png";
        } else if($_POST["weather"] === "weather_cloud") {
            $weather = "/img/weather/list_weather_cloud.png";
        } else if($_POST["weather"] === "weather_rain") {
            $weather = "/img/weather/list_weather_rain.png";
        } else if($_POST["weather"] === "weather_snow") {
            $weather = "/img/weather/list_weather_snow.png";
        } else if($_POST["weather"] === "weather_null") {
            $weather = "";
        } else {
            throw new Exception("날씨 오류");
        }

        // 감정 POST 처리시 출력
        if($_POST["emotion"] === "emotion_happy") {
            $emotion = "/img/emotion/list_emotion_happy.png";
        } else if($_POST["emotion"] === "emotion_sad") {
            $emotion = "/img/emotion/list_emotion_sad.png";
        } else if($_POST["emotion"] === "emotion_angry") {
            $emotion = "/img/emotion/list_emotion_angry.png";
        } else if($_POST["emotion"] === "emotion_unrest") {
            $emotion = "/img/emotion/list_emotion_unrest.png";
        } else if($_POST["emotion"] === "emotion_tired") {
            $emotion = "/img/emotion/list_emotion_tired.png";
        } else if($_POST["emotion"] === "emotion_calm") {
            $emotion = "/img/emotion/list_emotion_calm.png";
        } else if($_POST["emotion"] === "emotion_null") {
            $emotion = "";
        } else {
            throw new Exception("감정 오류");
        }

         // 테마 $theme 에 값을 담음 (0,1,2,3)
        if($_POST["theme"] === "theme_animal") {
            $theme = "0";
        } else if($_POST["theme"] === "theme_plant") {
            $theme = "1";
        } else if($_POST["theme"] === "theme_pixel") {
            $theme = "2";
        } else if($_POST["theme"] === "theme_null") {
            $theme = "3";
        } else {
            throw new Exception("테마 오류");
        }

        $arr_prepare = [
            "cal_id" => $cal_id
            ,"weather" => $weather
            ,"emotion" => $emotion
            ,"theme" => $theme
        ];
        
        my_board_update_deco($conn, $arr_prepare);

        $conn->commit();
        // commit 하고 돌아갔을때 리스트페이지(날짜, cal_id) 출력 
        header("Location: /list.php?date=".$year."-".$month."-".$day."&cal_id=".$cal_id);
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
    <link rel="stylesheet" href="./css/update_deco.css">
    <title>데코 수정 페이지</title>
</head>
<body>
    <main>
        <div class="main_container">
            <form action="/update_deco.php" method="post">
                <input type="hidden" name="cal_id" value="<?php echo $result["cal_id"] ?>">
                <div class="main_container_box">
                    <div class="main_box_left">
                        <!-- 날씨 데코 -->
                        <div class="update_left_weather">
                            <div class="update_deco_title">날씨</div>
                            <div class="update_deco_content">
                                <input type="radio" id="weather_sun" name="weather" value="weather_sun">
                                <label class="update_deco_image image_sun" for="weather_sun"></label>
                                <p>맑음</p>
                            </div> 

                            <div class="update_deco_content">
                                <input type="radio" id="weather_cloud" name="weather" value="weather_cloud">
                                <label class="update_deco_image image_cloud" for="weather_cloud"></label>
                                <p>흐림</p>
                            </div>

                            <div class="update_deco_content">
                                <input type="radio" id="weather_rain" name="weather" value="weather_rain">
                                <label class="update_deco_image image_rain" for="weather_rain"></label>
                                <p>비</p>
                            </div>

                            <div class="update_deco_content">
                                <input type="radio" id="weather_snow" name="weather" value="weather_snow">
                                <label class="update_deco_image image_snow" for="weather_snow"></label>
                                <p>눈</p>
                            </div>

                            <div class="update_deco_content">
                                <input type="radio" id="weather_null" name="weather" value="weather_null">
                                <label class="update_deco_image image_null" for="weather_null"></label>
                                <p>없음</p>
                            </div>
                        </div>
                        <!-- 테마 데코 -->
                        <div class="update_left_theme">
                            <div class="update_deco_title">테마</div>
                            <div class="update_deco_content">
                                <input type="radio" id="theme_animal" name="theme" value="theme_animal">
                                <label class="update_deco_image image_animal" for="theme_animal"></label>
                                <p>동물</p>
                            </div>

                            <div class="update_deco_content">
                                <input type="radio" id="theme_plant" name="theme" value="theme_plant">
                                <label class="update_deco_image image_plant" for="theme_plant"></label>
                                <p>식물</p>
                            </div>

                            <div class="update_deco_content">
                                <input type="radio" id="theme_pixel" name="theme" value="theme_pixel">
                                <label class="update_deco_image image_pixel" for="theme_pixel"></label>
                                <p>픽셀</p>
                            </div>

                            <div class="update_deco_content">
                                <input type="radio" id="theme_null" name="theme" value="theme_null">
                                <label class="update_deco_image image_null" for="theme_null"></label>
                                <p>없음</p>
                            </div>
                        </div>
                    </div>
                    <div class="main_box_right">
                        <!-- 감정 데코 -->
                        <div class="update_right_emotion">
                            <div class="update_deco_title">감정</div>

                            <div class="emotion_first">
                                <div class="update_deco_content">
                                    <input type="radio" id="emotion_happy" name="emotion" value="emotion_happy">
                                    <label class="update_deco_image image_happy" for="emotion_happy"></label>
                                    <p>기쁨</p>
                                </div>
    
                                <div class="update_deco_content">
                                    <input type="radio" id="emotion_sad" name="emotion" value="emotion_sad">
                                    <label class="update_deco_image image_sad" for="emotion_sad"></label>
                                    <p>슬픔</p>
                                </div>
    
                                <div class="update_deco_content">
                                    <input type="radio" id="emotion_angry" name="emotion" value="emotion_angry">
                                    <label class="update_deco_image image_angry" for="emotion_angry"></label>
                                    <p>화남</p>
                                </div>
                            </div>

                            <div class="emotion_second">
                                <div class="update_deco_content">
                                    <input type="radio" id="emotion_unrest" name="emotion" value="emotion_unrest">
                                    <label class="update_deco_image image_unrest" for="emotion_unrest"></label>
                                    <p>우울</p>
                                </div>

                                <div class="update_deco_content">
                                    <input type="radio" id="emotion_tired" name="emotion" value="emotion_tired">
                                    <label class="update_deco_image image_tired" for="emotion_tired"></label>
                                    <p>피곤</p>
                                </div>

                                <div class="update_deco_content">
                                    <input type="radio" id="emotion_calm" name="emotion" value="emotion_calm">
                                    <label class="update_deco_image image_calm" for="emotion_calm"></label>
                                    <p>평온</p>
                                </div>
                            </div>
                            <div class="emotion_third">
                                <div class="update_deco_content">
                                    <input type="radio" id="emotion_null" name="emotion" value="emotion_null">
                                    <label class="update_deco_image image_null" for="emotion_null"></label>
                                    <p>없음</p>
                                </div>
                            </div>
                        </div>
                        <div class="update_btn">
                            <button class="btn_small" type="submit">확인</button>
                            <a href="/list.php?date=<?php echo $result["year"]."-".$result["month"]."-".$result["day"] ?>&cal_id=<?php echo $result["cal_id"] ?>"><button class="btn_small" type="button">취소</button></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>