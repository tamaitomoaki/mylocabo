<?php
include("../function.php");

//入力チェック(受信確認処理追加)
if( (!isset($_POST["email"]) || $_POST["email"]=="") &&
    (!isset($_POST["password"]) || $_POST["password"]=="") ){
    echo 3;
}else{
    if( !isset($_POST["email"]) || $_POST["email"]=="" ){
    echo 1;
    }
    if( !isset($_POST["password"]) || $_POST["password"]=="" ){
        echo 2;
    }
}

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    // 入力したユーザIDを格納
    $email = $_POST["email"];
    // 3. エラー処理
    try {
        $pdo = db_con();
        $stmt = $pdo->prepare("
        SELECT email, password
        FROM menber_table
        WHERE email = :a1;
        ");
        $stmt->bindValue(':a1', $email,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方

        $password = $_POST["password"];
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //パスワードが一致しているかどうか
            if ($password == $row['password']) {
                echo 0;//パスワード一致。
            } else {
                // 認証失敗
                echo 4;
            }
        } else {
            // 4. 認証成功なら、セッションIDを新規に発行する
            // 該当データなし
            echo 5;
        }
    } catch (PDOException $e) {
        echo 6;//データベースエラー
    }
}

?>