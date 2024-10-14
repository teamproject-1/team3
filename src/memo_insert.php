<?php 
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_PATH_DB_LIB);

    try{
        // GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
        $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
        // GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
        $month = isset($_POST['month']) ? $_POST['month'] : date('m');
        // GET으로 넘겨 받은 day값이 있다면 넘겨 받은걸 day변수에 적용하고 없다면 현재 월
        $day = isset($_POST['day']) ? $_POST['day'] : date('d');

        $conn = my_db_conn();
        $arr_prepare = [
            "memo_content" => $_POST["memo_content"]
        ];

        $result = my_memo_insert($conn, $arr_prepare);

        header("Location: /list.php?year=".$year."&month=".$month."&day=".$day);
    }catch(Throwable $th) {
        require_once(MY_PATH_ERROR);
        exit;
    }
?>