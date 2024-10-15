<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_PATH_DB_LIB);

$conn = null;
try{
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $month = isset($_POST['month']) ? $_POST['month'] : date('m');
    $day = isset($_POST['day']) ? $_POST['day'] : date('d');
    $cal_id = isset($_POST["cal_id"]) ? $_POST["cal_id"] : 0;
    $td_id = isset($_POST["td_id"]) ? $_POST["td_id"] : 0;
    $check_todo = isset($_POST["check_todo"]) ? $_POST["check_todo"] : 0;

    // PDO Instance
    $conn = my_db_conn();

    // Transaction Start
    $conn->beginTransaction();

    $arr_prepare = [
        "cal_id" => $cal_id
        ,"td_id" => $td_id
        ,"check_todo" => $check_todo
    ];

    my_todolist_update($conn, $arr_prepare);
    $conn->commit();

    header("Location: /list.php?year=".$year."&month=".$month."&day=".$day);
    exit;

} catch(Throwable $th) {
    if(!is_null($conn) && $conn->inTransaction()){
        $conn->rollBack();
    }
    require_once(MY_PATH_ERROR);
    exit;
}
