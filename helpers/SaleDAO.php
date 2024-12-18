<?php
require_once './helpers/DAO.php';
require_once './helpers/CartDAO.php';
require_once './helpers/SaleDetailDAO.php';

class SaleDAO {
    private function get_saleno() {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT IDENT_CURRENT('Sale') AS saleno";

        // クエリを実行して結果を取得
        $stmt = $dbh->query($sql);
        $row = $stmt->fetchObject();

        // 取得した saleno を返す
        return $row->saleno;
    }

    // DBに購入データを追加する
    public function insert(int $memberid, array $cart_list) {
        // DBに接続
        $dbh = DAO::get_db_connect();

        // Saleテーブルに購入情報を追加するSQL
        $sql = "INSERT INTO sale(saledate, memberid) VALUES (:saledate, :memberid)";

        $stmt = $dbh->prepare($sql);

        // 現在時刻を取得する
        $saledate = date('Y-m-d H:i:s'); // 修正: date関数の使い方

        // SQLに変数の値を当てはめる
        $stmt->bindValue(':saledate', $saledate, PDO::PARAM_STR);
        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);

        // SQL実行
        $stmt->execute();

        // salenoを取得
        $saleno = $this->get_saleno();

        $saleDetailDAO = new SaleDetailDAO();

        // カートの商品をSaleDetailテーブルに追加する
        foreach ($cart_list as $cart) {
            $saleDetail = new SaleDetail();

            $saleDetail->saleno = $saleno;
            $saleDetail->goodscode = $cart->goodscode;
            $saleDetail->num = $cart->num; // 修正: numの設定

            $saleDetailDAO->insert($saleDetail, $dbh);
        }
    }
}
?>
