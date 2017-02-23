<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え


//echo json_encode($_GET["id"]);
//echo json_encode($_GET["id"]);
//var_dump($_GET["data"]);
//var_dump($_GET["id"]);
//exit();

//入力チェック(受信確認処理追加)
//コメントをセッションに入れているので、送られてきていないのでページが必ず戻る
if(
  !isset($_GET["data"]) || $_GET["data"]==""
)
{
  //未記入がある場合、エラー
    echo json_encode("データが渡すことが出来ていません。");
    exit;
}
if(
  !isset($_GET["id"]) || $_GET["id"]==""
)
{
  //未記入がある場合、エラー
    echo json_encode("正しくクリック出来ていません。");
    exit;
}

$spot_id = $_GET["spot_id"];
$menber_id = $_SESSION["menber_id"];


if ( isset($_GET["data2"]) ){
    //スポット編集画面でカテゴリーをその他にした場合
    $suggest = "";
    if ( $_GET["data2"] == "その他" ){
        $suggest = "編集でその他";
    }
    
}










//DB接続
$pdo = db_con();
//カテゴリーを受け取る
//カテゴリー、スポットテーブルから今のデータを削除して、履歴テーブルに挿入,タグテーブルからカウントをマイナス１
//カテゴリーテーブルに新しく受け取ったタグが存在するかどうかを確認、あればカウントをプラス１、なければインサート
//カテゴリー、スポットテーブルにカテゴリーをインサート
switch ($_GET["id"]) {
    case 'spotname':
        //更新履歴テーブルに変更されたデータを挿入する
            //更新履歴テーブルに変更された情報を代入
            $stmt = $pdo->prepare('
            INSERT spot_change_log_table (spot_id, item, content, menber_id, inserted_at)
            SELECT :a1, :a2, spotname, :a3, sysdate() 
            FROM spot_table 
            WHERE spot_id = :a1
            ');
            $stmt->bindValue(':a1', $spot_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $_GET["id"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a3', $menber_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
            //spot_tableのデータをアップデート
            $stmt = $pdo->prepare("
            UPDATE spot_table 
            SET spotname = :a1, updated_at = sysdate()
            WHERE spot_id=:a2

            ");
            $stmt->bindValue(':a1', $_GET["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $spot_id,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
        echo json_encode($_GET["data"]);
        break;
    case 'category':
        //カテゴリーの登録    
        //更新履歴テーブルに変更された情報を代入
        $stmt = $pdo->prepare('
        INSERT spot_change_log_table (spot_id, item, content, menber_id, inserted_at)
        SELECT :a1, :a2, category_name, :a3, sysdate() 
        FROM category_spot_map_table 
        LEFT JOIN categorys_table ON category_spot_map_table.category_id = categorys_table.category_id
        WHERE spot_id = :a1
        ');
        $stmt->bindValue(':a1', $spot_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a2', $_GET["id"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a3', $menber_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方
        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
        //カテゴリーのカウント処理
        $stmt = $pdo->prepare('
        INSERT INTO categorys_table (category_name, usage_count)
        VALUES(:a1, :a2),(:a3, :a4)
        ON DUPLICATE KEY UPDATE usage_count = usage_count + VALUES(usage_count)
        ');
        $stmt->bindValue(':a1', $_GET["data"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a2', -1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a3', $_GET["data2"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a4', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方
        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
        
        //カテゴリーとスポットのの紐付けデータのcategory_id部分をアップデイト
        //編集のタイミングでその他を選んだ場合は、最適なタグの提案はなし。需要があったら。
        $stmt = $pdo->prepare('
        UPDATE category_spot_map_table
        SET category_id = (
            SELECT category_id
            FROM categorys_table
            WHERE category_name = :a1
            ),
            suggest = :a2
        WHERE spot_id = :a3
        ');
        $stmt->bindValue(':a1', $_GET["data2"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a2', $suggest,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a3', $spot_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方
        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
        echo json_encode($_GET["data2"]);
        break;
    case 'url':
        //更新履歴テーブルに変更されたデータを挿入する
            //更新履歴テーブルに変更された情報を代入
            $stmt = $pdo->prepare('
            INSERT spot_change_log_table (spot_id, item, content, menber_id, inserted_at)
            SELECT :a1, :a2, url, :a3, sysdate() 
            FROM spot_table 
            WHERE spot_id = :a1
            ');
            $stmt->bindValue(':a1', $spot_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $_GET["id"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a3', $menber_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
            //spot_tableのデータをアップデート
            $stmt = $pdo->prepare("
            UPDATE spot_table 
            SET url = :a1, updated_at = sysdate()
            WHERE spot_id=:a2
            ");
            $stmt->bindValue(':a1', $_GET["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $spot_id,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
        echo json_encode($_GET["data"]);
        break;
    case 'tel':
        //更新履歴テーブルに変更されたデータを挿入する
            //更新履歴テーブルに変更された情報を代入
            $stmt = $pdo->prepare('
            INSERT spot_change_log_table (spot_id, item, content, menber_id, inserted_at)
            SELECT :a1, :a2, tel, :a3, sysdate() 
            FROM spot_table 
            WHERE spot_id = :a1
            ');
            $stmt->bindValue(':a1', $spot_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $_GET["id"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a3', $menber_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
            //spot_tableのデータをアップデート
            $stmt = $pdo->prepare("
            UPDATE spot_table 
            SET tel = :a1, updated_at = sysdate()
            WHERE spot_id=:a2

            ");
            $stmt->bindValue(':a1', $_GET["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $spot_id,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
        echo json_encode($_GET["data"]);
        break;
    case 'open':
        //更新履歴テーブルに変更されたデータを挿入する
            //更新履歴テーブルに変更された情報を代入
            $stmt = $pdo->prepare('
            INSERT spot_change_log_table (spot_id, item, content, menber_id, inserted_at)
            SELECT :a1, :a2, open, :a3, sysdate() 
            FROM spot_table 
            WHERE spot_id = :a1
            ');
            $stmt->bindValue(':a1', $spot_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $_GET["id"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a3', $menber_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
            //spot_tableのデータをアップデート
            $stmt = $pdo->prepare("
            UPDATE spot_table 
            SET open = :a1, updated_at = sysdate()
            WHERE spot_id=:a2

            ");
            $stmt->bindValue(':a1', $_GET["data"],  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $stmt->bindValue(':a2', $spot_id,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
        echo json_encode($_GET["data"]);
        break;
}
exit();



?>