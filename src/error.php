<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/error.css">
    <title>에러 페이지</title>
</head>
<body>
    <main>
        <div class="main_container">
            <div class="main_container_box">
                <div class="main_box_left"></div>
                <div class="detail_container_box">
                    <div class="detail_back_green">
                        <div class="detail_back_white">
                            <div class="error_content">
                                <div>
                                    <img src="../img/error_icon.png" alt="">
                                </div>
                                <div class="error_content_font">
                                    <h1>죄송합니다.</h1>
                                    <p>요청하신 페이지에서 에러가 발생했습니다.</p>
                                    <p>메인 페이지로 돌아가 주세요.</p>
                                </div>
                            </div>
                            <a href="/main.php"><button type="button" class="btn_small error_btn_error">메인 페이지로 이동</button></a>
                        </div>
                    </div>
                </div>
                <div class="main_box_right"></div>
            </div>
        </div>
    </main>
</body>
</html>