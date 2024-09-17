<?php
require_once('functions.php');

//new.phpファイルから受け取った値を引数にしています。
getRefererPath($_POST);
savePostedData($_POST);
header('Location: ./index.php');
?>