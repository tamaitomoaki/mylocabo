<?php
session_start();
include("../function.php");

//入力チェック(受信確認処理追加)
//コメントをセッションに入れているので、送られてきていないのでページが必ず戻る
if(
  !isset($_POST["data"]) || $_POST["data"]==""
)
{
    echo json_encode("データが渡すことが出来ていません。");
    exit;
}
if(
  !isset($_POST["id"]) || $_POST["id"]==""
)
{
    echo json_encode("正しくクリック出来ていません。");
    exit;
}

$menber_id = $_SESSION["menber_id"];


//DB接続
$pdo = db_con();

switch ($_POST["id"]) {
    case 'name':
        //menber_tableのname部分をUPDATAで情報更新
            $stmt = $pdo->prepare("
            UPDATE menber_table 
            SET name = :a1, updated_at = sysdate()
            WHERE menber_id = :a2
            ");
            $stmt->bindValue(':a1', $_POST["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
        $_SESSION["name"] = $_POST["data"];
        echo json_encode(h($_POST["data"]));
        break;
    case 'area':
        //menber_tableのname部分をUPDATAで情報更新
            $stmt = $pdo->prepare("
            UPDATE menber_table 
            SET area = :a1, updated_at = sysdate()
            WHERE menber_id = :a2
            ");
            $stmt->bindValue(':a1', $_POST["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
            $_SESSION["area_num"] = $_POST["data"];
            $AS = TOs($_SESSION["area_num"], $_SESSION["sex_num"]); 
            $_SESSION["area"] = $AS['area'];
            $result = [$_SESSION["area"], $_SESSION["area_num"]];
        echo json_encode($result);
        break;
    case 'sex':
        //menber_tableのname部分をUPDATAで情報更新
            $stmt = $pdo->prepare("
            UPDATE menber_table 
            SET sex = :a1, updated_at = sysdate()
            WHERE menber_id = :a2
            ");
            $stmt->bindValue(':a1', $_POST["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
            $_SESSION["sex_num"] = $_POST["data"];
            $AS = TOs($_SESSION["area_num"], $_SESSION["sex_num"]); 
            $_SESSION["sex"] = $AS['sex'];
            $result = [$_SESSION["sex"], $_SESSION["sex_num"]];
        echo json_encode($result);
        break;
    case 'introduction':
        //menber_tableのname部分をUPDATAで情報更新
            $stmt = $pdo->prepare("
            UPDATE menber_table 
            SET introduction = :a1, updated_at = sysdate()
            WHERE menber_id = :a2
            ");
            $stmt->bindValue(':a1', $_POST["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
        $_SESSION["introduction"] = $_POST["data"];
        echo json_encode(nl2br(h($_POST["data"])));
        break;
}
exit();

?>