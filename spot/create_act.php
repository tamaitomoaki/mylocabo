<?php
session_start();
include("../function.php");
sessionCheck();//セッションの入れ替え

//入力チェック(受信確認処理追加)
if(
  !isset($_POST["spotname"]) || $_POST["spotname"]=="" ||
  !isset($_POST["category"]) || $_POST["category"]=="" ||
  !isset($_POST["address"]) || $_POST["address"]=="" ||
  !isset($_POST["lat"]) || $_POST["lat"]=="" ||
  !isset($_POST["lng"]) || $_POST["lng"]=="" 
){
//  exit('ParamError');
    header("Location: index.php?erro1");
    exit;
}

//取得データを変数へ
$spotname = $_POST["spotname"];
$category_name = $_POST["category"];
$address = $_POST["address"];
$lat = $_POST["lat"];
$lng = $_POST["lng"];
$menber_id = $_SESSION["menber_id"];
//url,tel,openの取得、任意
$url = $_POST["url"];
$tel = $_POST["tel"];
$open = $_POST["open"];
//category_nameでその他を選んだ人が提案したカテゴリーを取得
$suggest = $_POST["suggest"];
//DB接続
$pdo = db_con();

//３．データ登録SQL作成　
    $stmt = $pdo->prepare("INSERT INTO spot_table(spot_id, spotname, address, lat, lng, url, tel, open, menber_id, created_at)VALUES(NULL, :a1,:a2,:a3,:a4,:a5,:a6,:a7,:a8, sysdate())");
    $stmt->bindValue(':a1', $spotname,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $address,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a3', $lat,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a4', $lng,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a5', $url,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a6', $tel,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a7', $open,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a8', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $status = $stmt->execute();   //セキュリティにいい書き方
    //SQL処理エラー
    if($status==false){
    //4,データ登録処理後
        db_error($stmt);
    }
    $spot_id = $pdo->lastInsertId('spot_id');


//カテゴリーの登録
//選択されたカテゴリーのカウントをアップする
    $stmt = $pdo->prepare('
    UPDATE categorys_table
    SET usage_count = usage_count + 1
    WHERE category_name = :a1
    ');
    $stmt->bindValue(':a1', $category_name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $status = $stmt->execute();   //セキュリティにいい書き方
    if($status==false){
    //4,データ登録処理後
        db_error($stmt);
    }
//category_spot_map_tableでspot_idとcategory_idを紐付けする登録
//カテゴリーでその他を選んだ人で入力があった場合、suggest欄に値を入れる
    $stmt = $pdo->prepare("
    INSERT INTO category_spot_map_table
    (category_spot_map_id, spot_id, category_id, suggest)
    SELECT NULL, :a1, category_id, :a3
    FROM categorys_table
    WHERE category_name = :a2
    ");
    $stmt->bindValue(':a1', $spot_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $category_name,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a3', $suggest,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $status = $stmt->execute();   //セキュリティにいい書き方
    //SQL処理エラー
    if($status==false){
    //4,データ登録処理後
        db_error($stmt);
    }

    header("Location: create-after.php?spot_id=".$spot_id );
    exit;
?>