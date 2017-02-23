<?php
//DB接続関数（PDO）$pdo = db_con();
//function db_con(){
//    try {
//      return new PDO('mysql:dbname=lowcarbolife_mylocabo;charset=utf8;host=mysql328.db.sakura.ne.jp','lowcarbolife','Ncc21ccc');
//    } catch (PDOException $e) {
//      exit('DbConnectError:'.$e->getMessage());
//    }
//}
//ローカルファイルで作業時
function db_con(){
    try {
      return new PDO('mysql:dbname=lowcarbolife_test2;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
      exit('DbConnectError:'.$e->getMessage());
    }
}

//認証OK時の初期値セット
function loginSessionSet($val){ 
    $_SESSION["chk_ssid"] = session_id(); 
    $_SESSION["menber_id"] = $val['menber_id']; 
    $_SESSION["name"] = $val['name']; 
    $_SESSION["email"] = $val['email']; 
    $_SESSION["profileimg"] = $val['profileimg']; 
    $_SESSION["area_num"] = $val['area']; 
    $_SESSION["sex_num"] = $val['sex']; 
    $AS = TOs($val['area'],$val['sex']); 
    $_SESSION["area"] = $AS['area']; 
    $_SESSION["sex"] = $AS['sex']; 
    $_SESSION["introduction"] = $val['introduction']; 
    $_SESSION["review_img"] = 0; 
    
    
}

//スタンス、エリア、性別、判定数値化
function TOn($aval,$seval){
    
    if( $aval == "北海道") $area = 1;
    if( $aval == "青森県") $area = 2;
    if( $aval == "岩手県") $area = 3;
    if( $aval == "宮城県") $area = 4;
    if( $aval == "秋田県") $area = 5;
    if( $aval == "山形県") $area = 6;  
    if( $aval == "福島県") $area = 7;  
    if( $aval == "茨城県") $area = 8;  
    if( $aval == "栃木県") $area = 9;  
    if( $aval == "群馬県") $area = 10;
    if( $aval == "埼玉県") $area = 11;  
    if( $aval == "千葉県") $area = 12;  
    if( $aval == "東京都") $area = 13;  
    if( $aval == "神奈川県") $area = 14;  
    if( $aval == "新潟県") $area = 15;
    if( $aval == "富山県") $area = 16;  
    if( $aval == "石川県") $area = 17;  
    if( $aval == "福井県") $area = 18;  
    if( $aval == "山梨県") $area = 19;  
    if( $aval == "長野県") $area = 20;
    if( $aval == "岐阜県") $area = 21;  
    if( $aval == "静岡県") $area = 22;  
    if( $aval == "愛知県") $area = 23;  
    if( $aval == "三重県") $area = 24;  
    if( $aval == "滋賀県") $area = 25;
    if( $aval == "京都府") $area = 26;  
    if( $aval == "大阪府") $area = 27;  
    if( $aval == "兵庫県") $area = 28;  
    if( $aval == "奈良県") $area = 29;  
    if( $aval == "和歌山県") $area = 30;
    if( $aval == "鳥取県") $area = 31;  
    if( $aval == "島根県") $area = 32;  
    if( $aval == "岡山県") $area = 33;  
    if( $aval == "広島県") $area = 34;  
    if( $aval == "山口県") $area = 35;
    if( $aval == "徳島県") $area = 36;  
    if( $aval == "香川県") $area = 37;  
    if( $aval == "愛媛県") $area = 38;  
    if( $aval == "高知県") $area = 39;  
    if( $aval == "福岡県") $area = 40;
    if( $aval == "佐賀県") $area = 41;  
    if( $aval == "長崎県") $area = 42;  
    if( $aval == "熊本県") $area = 43;  
    if( $aval == "大分県") $area = 44;  
    if( $aval == "宮崎県") $area = 45;
    if( $aval == "鹿児島県") $area =46;
    if( $aval == "沖縄県") $area = 47;
    if( $aval == "未記入") $area = 48;
    
    if( $seval == "男性")$sex = 0;
    if( $seval == "女性")$sex = 1;
    if( $seval == "未記入")$sex = 2;
    
    $as = array(
        'area' => $area,
        'sex' => $sex
    );
    
    return $as;
    
}

//スタンス,エリア、性別、判定文字列化
function TOs($aval,$seval){
    
    if( $aval ==1 ) $area = "北海道";
    if( $aval ==2 ) $area = "青森県";
    if( $aval ==3 ) $area = "岩手県";
    if( $aval ==4 ) $area = "宮城県";
    if( $aval ==5 ) $area = "秋田県";
    if( $aval ==6 ) $area = "山形県";  
    if( $aval ==7 ) $area = "福島県";  
    if( $aval ==8 ) $area = "茨城県";  
    if( $aval ==9 ) $area = "栃木県";  
    if( $aval ==10 ) $area = "群馬県";
    if( $aval ==11 ) $area = "埼玉県";  
    if( $aval ==12 ) $area = "千葉県";  
    if( $aval ==13 ) $area = "東京都";  
    if( $aval ==14 ) $area = "神奈川県";  
    if( $aval ==15 ) $area = "新潟県";
    if( $aval ==16 ) $area = "富山県";  
    if( $aval ==17 ) $area = "石川県";  
    if( $aval ==18 ) $area = "福井県";  
    if( $aval ==19 ) $area = "山梨県";  
    if( $aval ==20 ) $area = "長野県";
    if( $aval ==21 ) $area = "岐阜県";  
    if( $aval ==22 ) $area = "静岡県";  
    if( $aval ==23 ) $area = "愛知県";  
    if( $aval ==24 ) $area = "三重県";  
    if( $aval ==25 ) $area = "滋賀県";
    if( $aval ==26 ) $area = "京都府";  
    if( $aval ==27 ) $area = "大阪府";  
    if( $aval ==28 ) $area = "兵庫県";  
    if( $aval ==29 ) $area = "奈良県";  
    if( $aval ==30 ) $area = "和歌山県";
    if( $aval ==31 ) $area = "鳥取県";  
    if( $aval ==32 ) $area = "島根県";  
    if( $aval ==33 ) $area = "岡山県";  
    if( $aval ==34 ) $area = "広島県";  
    if( $aval ==35 ) $area = "山口県";
    if( $aval ==36 ) $area = "徳島県";  
    if( $aval ==37 ) $area = "香川県";  
    if( $aval ==38 ) $area = "愛媛県";  
    if( $aval ==39 ) $area = "高知県";  
    if( $aval ==40 ) $area = "福岡県";
    if( $aval ==41 ) $area = "佐賀県";  
    if( $aval ==42 ) $area = "長崎県";  
    if( $aval ==43 ) $area = "熊本県";  
    if( $aval ==44 ) $area = "大分県";  
    if( $aval ==45 ) $area = "宮崎県";
    if( $aval ==46 ) $area ="鹿児島県";
    if( $aval ==47 ) $area = "沖縄県";
    if( $aval ==48 ) $area = "未選択";
    
    if( $seval == 0)$sex = "男性";
    if( $seval == 1)$sex = "女性";
    if( $seval == 2)$sex = "未選択";
    
    $as = array(
        'area' => $area,
        'sex' => $sex
    );
    
    return $as;
    
    
}


function change_time($val){
    if( $val =="0" ) $time = "未入力";
    if( $val =="1" ) $time = "朝";
    if( $val =="2" ) $time = "昼";
    if( $val =="3" ) $time = "夜";
    if( $val =="4" ) $time = "その他";
        
        return $time;
}
function change_money($val){
    if( $val == "0")$money = "未入力";
    if( $val == "1")$money = "~¥999";
    if( $val == "2")$money = "¥1,000~¥1,999";
    if( $val == "3")$money = "¥2,000~¥2,999";
    if( $val == "4")$money = "¥3,000~¥3,999";
    if( $val == "5")$money = "¥4,000~¥4,999";
    if( $val == "6")$money = "¥5,000~¥5,999";
    if( $val == "7")$money = "¥6,000~¥6,999";
    if( $val == "8")$money = "¥7,000~¥7,999";
    if( $val == "9")$money = "¥8,000~¥8,999";
    if( $val == "10")$money = "¥9,000~¥9,999";
    if( $val == "11")$money = "¥10,000~¥14,999";
    if( $val == "12")$money = "¥15,000~¥19,999";
    if( $val == "13")$money = "¥20,000~¥29,999";
    if( $val == "14")$money = "¥30,000~";
    
    return $money;
    
}


//セッションチェック用関数
function sessionCheck(){
  if(isset($_SESSION["chk_ssid"]) != session_id()){
//      echo "ログインしてください";
      header("Location: ../index.php?login=no" );
//      include(__DIR__."/mypage/index.php");
//      var_dump( __DIR__."/mypage/index.php");
      exit();
  }else{
     session_regenerate_id(true);
     $_SESSION["chk_ssid"] = session_id();
//      include("login-header.php");
      
//      if( $_SESSION["review_img"] != 0 ){
//              //口コミ投稿で画像アップロードだけされて、レビュー登録されていない画像を削除
//              $pdo = db_con();
//              $uploadimg = $_SESSION["review_img"];
//              foreach ($uploadimg as $image_name) {
//                    $stmt = $pdo->prepare("
//                    DELETE FROM images_table 
//                    WHERE image_name=:a1
//                    ");
//                    $stmt ->bindValue(':a1',$image_name,PDO::PARAM_STR);
//                    $status = $stmt->execute();//セキュリティにいい書き方
//                    if($status==false){
//                        db_error($stmt);
//                    }
//                    unlink("../upload/".$image_name);
//                    unlink("../upload/s/".$image_name);
//                } 
//      }
      
      
  }
}

//HTML XSS対策
/**
* XSS
* @Param:  $str(string) 表示する文字列
* @Return: (string)     サニタイジングした文字列
*/
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

//SQL処理エラー
function db_error($stmt){    //$stmtの情報を外から持ってくる
      $error = $stmt->errorInfo();
      exit("QueryError:".$error[2]);
}

/**
* FileUniqRenam
* ファイル名変更
* session_start();してる必要がある！
*/
//ファイル選択が１つの場合
function fileUniqRenameSingle($file_name){
  $extension = pathinfo($file_name, PATHINFO_EXTENSION); //拡張子取得
  $uniq_name = date("YmdHis").session_id().".".$extension; //ユニークファイル名作成
  return $uniq_name;
}
//ファイル選択が複数可能な場合
function fileUniqRename($file_name,$val){
  $extension = pathinfo($file_name, PATHINFO_EXTENSION); //拡張子取得
  $uniq_name = date("YmdHis").session_id()."0".$val.".".$extension; //ユニークファイル名作成
//  $uniq_name = rand( ).session_id()."0".$val.".".$extension; //ユニークファイル名作成本番環境だとdata()がつかえない
  return $uniq_name;
}
?>
