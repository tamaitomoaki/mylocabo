<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え

//入力チェック(受信確認処理追加)
$alertarray = array();
if(
  !isset($_POST["time"]) || $_POST["time"]==""  
){
    $alertarray[] = 'time';
}
if(
  !isset($_POST["money"]) || $_POST["money"]=="" 
){
    $alertarray[] = 'money';
}
if(
  !isset($_POST["comment"]) || $_POST["comment"]==""
){
    $alertarray[] = 'comment';
}
if(
  !isset($_POST["point"]) || $_POST["point"]==""
){
    $alertarray[] = 'point';
}
    echo json_encode($alertarray);
    exit;
?>