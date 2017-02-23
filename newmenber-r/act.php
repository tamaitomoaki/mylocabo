<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え

//2. DB接続します(エラー処理追加)　定番の表現　変更点はDbConnectErrorくらい
$pdo = db_con();


if(empty($_GET)) {
	header("Location: index.php");
	exit();
}else{
	//GETデータを変数に入れる
	$token = isset($_GET["token"]) ? $_GET["token"] : NULL;
	//メール入力判定
	if ($token == ''){
		$errors['token'] = "もう一度登録をやりなおして下さい。";
	}else{
			//例外処理を投げる（スロー）ようにする
//			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//未登録者・仮登録日から24時間以内
            $stmt = $pdo->prepare('
            SELECT name, email, password, profileimg, area, sex, introduction
            FROM pre_menber_table 
            WHERE token=:a1 AND date > now() - interval 24 hour
            ');
            $stmt->bindValue(':a1', $token,   PDO::PARAM_INT);
            $res = $stmt->execute();
            //SQL実行時にエラーがある場合
            if($res==false){
              db_error($stmt."ok");
            }
			
			//レコード件数取得
			$row_count = $stmt->rowCount();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if( $row_count ==1){
				$val = $stmt->fetch(); //レコード取得
                $name = $val["name"];
                $email = $val["email"];
                $password = $val["password"];
                $profileimg = $val["profileimg"];
                $area = $val["area"];
                $sex = $val["sex"];
                $introduction = $val["introduction"];
                
                //３．仮登録テーブルから会員テーブルへデータ移行SQL作成　
                $stmt = $pdo->prepare("
                INSERT INTO menber_table
                (menber_id, name, email, password, profileimg, area, sex, introduction, created_at)
                VALUES
                (NULL, :a1, :a2, :a3, :a4, :a6, :a7, :a8, sysdate())
                ");
                $stmt->bindValue(':a1', $name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
                $stmt->bindValue(':a2', $email,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
                $stmt->bindValue(':a3', $password,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
                $stmt->bindValue(':a4', $profileimg,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
                $stmt->bindValue(':a6', $area,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
                $stmt->bindValue(':a7', $sex,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
                $stmt->bindValue(':a8', $introduction,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
                $status = $stmt->execute();   //セキュリティにいい書き方

                if($status==false){
                //4,データ登録処理後
                 //   db_error($stmt);
                    $error = $stmt->errorInfo();
                    exit("QueryError:".$error[2]);
                }
                $menber_id = $pdo->lastInsertId('menber_id');
                
                //２．仮登録データベース削除
                $stmt = $pdo->prepare('
                DELETE FROM pre_menber_table WHERE token = :a1;
                ');
                $stmt->bindValue(':a1', $token,   PDO::PARAM_INT);
                $res = $stmt->execute();
                //SQL実行時にエラーがある場合
                if($res==false){
                  db_error($stmt);
                }

                //２．登録が終わったらすぐにデータ参照SQL作成
                $stmt = $pdo->prepare('
                SELECT menber_id, name, email, password, profileimg, area, sex, introduction 
                FROM menber_table 
                WHERE menber_id=:a1');
                $stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);
                $res = $stmt->execute();

                //SQL実行時にエラーがある場合
                if($res==false){
                  db_error($stmt);
                }

                //３．抽出データ数を取得
                //$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
                $val = $stmt->fetch(); //1レコードだけ取得する方法

                //４. 該当レコードがあればSESSIONに値を代入
                if( $val["menber_id"] != "" ){
                  loginSessionSet($val);
                  header("Location: ../mypage/profile-edit.php");
                }else{
                  //logout処理を経由して全画面へ
                  header("Location: index.php");
                }

                exit();


                //    //４．データ登録処理後
                //if($status==false){
                //    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示） 
                //    db_error($stmt);
                //}else{
                //  //５．index.phpへリダイレクト
                //  header("Location: index.php");
                //  exit;
                //}
				
			}else{ 
                //２．仮登録データベース削除
                $stmt = $pdo->prepare('
                DELETE FROM pre_menber_table WHERE token = :a1;
                ');
                $stmt->bindValue(':a1', $token,   PDO::PARAM_INT);
                $res = $stmt->execute();
                //SQL実行時にエラーがある場合
                if($res==false){
                  db_error($stmt);
                }
                
				$errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
                header("Location: ./timeout.php");
			}
			
			//データベース接続切断
			$dbh = null;
	}
}


?>
