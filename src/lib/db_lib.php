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
    ."      year =:year "
    ." AND  month =:month "
    ." AND  day =:day "

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
    " UPDATE calendar_boards "
    ." SET "
    ."      weather =:weather "
    ."      ,emotion =:emotion "
    ."      ,theme =:theme "
    ."      ,updated_at =NOW() "
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

// update 함수
function my_board_update($conn, array $arr_param) {
    $sql =
    " UPDATE todolist_boards "
    ." SET "
    ."      content =:content "
    ."      ,todo_updated_at =NOW() "
    ." WHERE "
    ."      cal_id =:cal_id "
    ." AND  td_id =:td_id "

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
    " INSERT INTO memo_boards ( "
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

// 메모 셀렉트
function my_memo_select($conn) {
    $sql =
    " SELECT "
    .       " * "
    ." FROM "
    .       " memo_boards "
    ." WHERE "
    .       " memo_deleted_at IS NULL  "
    ." order by "
    ." memo_id desc "
    ;

    $stmt = $conn->query($sql);

    return $stmt->fetchAll();
}

// 메모 업데이트
function my_memo_delete($conn, $arr_param) {
    $sql =
        " UPDATE memo_boards "
        ." set "
        ." memo_updated_at = now() "
        ." ,memo_deleted_at = now() "
        ." WHERE "
        ." memo_id = :memo_id "
    ; 
    
    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

    if($stmt->rowCount() !== 1) {
        throw new Exception("Delete Count 이상");
    }

    return true;

}


function my_todolist_select_cal_id($conn, array $arr_param) {
    $sql =
    " SELECT "
    ."      * "
    ." FROM "
    ."      todolist_boards "
    ." WHERE "
    ."      cal_id = :cal_id "
    ." AND  td_id = :td_id "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

     return $stmt->fetch();
}

// todolist_update 함수
function my_todolist_update($conn, array $arr_param) {
    $sql =
    " UPDATE todolist_boards "
    ." SET "
    ."      check_todo = :check_todo "
    ." WHERE "
    ."      cal_id = :cal_id "
    ." AND  td_id = :td_id "
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

// todolist_delete 함수
function my_todolist_delete($conn, array $arr_param) {
    $sql =
    " UPDATE todolist_boards "
    ." SET "
    ."      todo_updated_at = NOW() "
    ."      ,todo_deleted_at = NOW() "
    ." WHERE "
    ."      cal_id = :cal_id "
    ." AND  td_id = :td_id "
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

// todolist_select 함수
function my_todolist_list_select($conn, $arr_param) {
    $sql =
    " SELECT "
    ." * "
    ." FROM "
    ."      calendar_boards "
    ." JOIN "
    ."      todolist_boards "
    ." ON "
    ."      calendar_boards.cal_id = todolist_boards.cal_id "
    ." WHERE "
    ."      todolist_boards.todo_deleted_at is null "
    ."      AND calendar_boards.year = :year "
    ."      AND calendar_boards.month = :month "
    ."      AND calendar_boards.day = :day "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

    return $stmt->fetchAll();
}

// todo list 추가 함수
function todolist_content_insert ($conn, $arr_param) {
    $sql =
    " INSERT INTO todolist_boards ( "
    ." cal_id "
    ." ,content "
    ." ) "
    ." VALUES ( "
    ." :cal_id "
    ." ,:content "
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

// todo list 함수 cal_id 가져오기
function calendar_boards_select_cal_id ($conn, $arr_param) {
    $sql =
    " SELECT "
    ."      cal_id "
    ." FROM "
    ."      calendar_boards "
    ." WHERE "
    ."     year = :year "
    ." AND month = :month "
    ." AND day = :day " 
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

    return $stmt->fetch();
}

// calendar_board_select 함수
function my_calendar_select($conn, array $arr_param) {
    $sql =
    " SELECT "
    ."      * "
    ." FROM "
    ."      calendar_boards "
    ." WHERE "
    ."      year = :year "
    ." AND  month = :month "
    ." AND  day = :day "
    ;

    $stmt = $conn->prepare($sql);
    $result_flg = $stmt->execute($arr_param);

    if(!$result_flg) {
        throw new Exception("쿼리 실행 실패");
    }

    return $stmt->fetchAll();
}

// calendar_board_insert 함수
function my_calendar_insert($conn, array $arr_param) {
    $sql =
    " INSERT INTO calendar_boards ( "
    ."      year "
    ."      ,month "
    ."      ,day "
    ." ) "
    ." VALUES ( "
    ."      :year "
    ."      ,:month "
    ."      ,:day "
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