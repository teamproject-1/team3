<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");


?>

<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/index.css">
    <title>인덱스페이지</title>
</head>
<body>
    <main>
        <div class="main_container">
            <!-- 책 -->
            <a href=<?php echo"/main.php"?>>
            <div class="index_book">
                <!--책 표지 배경  -->
                <div class="index_container">
                    <!-- 책 컨텐츠 박스 -->
                    <div class="index_content-box">
                        <!-- 책 타이틀 박스 -->
                        <div class="index_title">
                            <!-- 책 타이틀 -->
                            <h1>모두 다.데</h1>
                        </div>
                        <!-- 책  -->
                        <div class="index_img_box">
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </main>
</body>
</html>
