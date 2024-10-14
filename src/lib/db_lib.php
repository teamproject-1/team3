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
    ." SET "
    ."      weather =:weather "
    ."      ,emotion =:emotion "
    ."      ,theme =:theme "
    ."      ,deco_created_at =NOW() "
    ."      ,deco_updated_at =NOW() "
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

// 메모 인서트 
function my_memo_insert($conn, $arr_param) {
    $sql =
    " INSERT INTO calendar_boards ( "
    ." memo_content "
    ." ) "
    ." VALUES ( "
    ." :memo_content "
    ." ) "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

    $result_cnt = $stmt->rowCount();
    
    if($result_cnt !== 1) {
        throw new Exception("insert count 이상");
    }

    return true;
}

// function my_memo_select($conn, $arr_param) {
//     $sql =
//     " SELECT "
//     ." memo_content "
//     ." from "
//     ." calendar_boards "
//     ." WHERE "
//     ." id = :id "
//     ;

//     $stmt = $conn->prepare($sql);
//     $result_flg = $stmt->execute($arr_param);

//     if(!$result_flg) {
//         throw new Exception("쿼리 실행 실패");
//     }

//     return $stmt->fetchAll();
// }