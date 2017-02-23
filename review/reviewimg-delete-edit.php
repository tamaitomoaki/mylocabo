<?php
session_start();

include("../function.php");
sessionCheck();//セッションの入れ替え


$delete_image = substr($_GET["image_name"],12);//削除したい画像

echo json_encode($delete_image);
exit;



?>
