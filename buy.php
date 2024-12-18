<?php
require_once './helpers/MemberDAO.php';
require_once './helpers/CartDAO.php';
require_once './helpers/SaleDAO.php';

//セッションを開始する
session_start();

if (!isset($_SESSION['member'])) {
    header('Location: login.php');
    exit;
}

// POSTリクエストかどうかを確認する
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}
//ログイン中の会員データを取得
$member=$_SESSION['member'];

//会員のカートデータを取得
$cartDAO=new CartDAO();
$cart_list=$cartDAO->get_cart_by_memberid($member->memberid);

//カートの商品をSaleテーブルに登録する
$saleDAO=new SaleDAO();
$saleDAO->insert($member->memberid,$cart_list);

//会員のカートデータをすべて削除
$cartDAO->delete_by_memberid($member->memberid);
?>
<header>
    <?php include "header2.php"?>
</header>
        購入が完了しました。<br><br>
     <a href=./buy.php>トップページへ</a>
