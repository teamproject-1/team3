<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");

function my_db_conn() {
    $option = [
        PDO::ATTR_EMULATE_PREPARES      =>false
        ,PDO::ATTR_ERRMODE              =>PDO::ERRMODE_EXCEPTION
        ,PDO::ATTR_DEFAULT_FETCH_MODE   =>PDO::FETCH_ASSOC
    ];

    return new PDO(MY_MARIADB_DSN, MY_MARIADB_USER, MY_MARIADB_PASSWORD, $option);
}

// 함수 작성
// 캘린더 id 가져오기
function my_board_select_cal_id($conn, array $arr_param) {
    $sql =
    " SELECT "
    ."      * "
    ." FROM "
    ."      calendar_boards "
    ." WHERE "
    ."      cal_id =:cal_id "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

     return $stmt->fetch();
}
// update_deco 함수
function my_board_update_deco($conn, array $arr_param) {
    $sql =
    " UPDATE deco_boards "
    ."      weather =:weather "
    ."      emotion =:emotion "
    ."      theme_tape =:theme_tape "
    ."      theme_sticker1 =:theme_sticker1 "
    ."      theme_sticker2 =:theme_sticker2 "
    ."      theme_sticker3 =:theme_sticker3 "
    // 데코 생성일,수정일도 deco_boards에 만들어야 하나..?
    ." WHERE "
    ."      cal_id =:cal_id "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

    $result_cnt = $stmt->rowCount();

    if($result_cnt !== 1) {
        throw new Exception("update_deco 갯수 오류");
    }
    return true;
}