<?php
require_once 'DAO.php';

class Goods{
    public string $goodscode; //商品コード
    public string $goodsname; //商品名
    public int    $price;     //価格
    public string $detail;    //商品詳細
    public int    $groupcode;//商品グループコード
    public bool   $recommend; //おすすめフラグ
    public string $goodsimage;//商品画像
}
//Goodsテーブルアクセス用クラス
class GoodsDAO
{
    //おすすめ商品を取得するメソッド
    public function get_recommend_goods(){
        //DBに接続する
        $dbh=DAO::get_db_connect();

        //Goodsテーブルからおすすめ商品を取得する
        $sql="SELECT *
              FROM Goods
              WHERE recommend=1";
            
        $stmt=$dbh->prepare($sql);

        //SQLを実行する
        $stmt->execute();

        //取得したデータを配列にする
        $data=[];
        while($row=$stmt->fetchObject('Goods')){
            $data[]=$row;
        }
        return $data;
    }
//因数の商品グループの商品を取得する
public function get_goods_by_groupcode(int $groupcode)
{
    //DBに接続する
    $dbh=DAO::get_db_connect();

    $sql="SELECT *
          FROM goods
          WHERE groupcode=:groupcode
          ORDER BY recommend DESC";

$stmt=$dbh->prepare($sql);

//SQLに変数の値を当てはめる
$stmt->bindValue(':groupcode',$groupcode,PDO::PARAM_INT);

//SQLを実行する
$stmt->execute();

//取得したデータをGoodsクラスの配列にする
$data=[];
while($row=$stmt->fetchObject('Goods')){
    $data[]=$row;
}
return $data;
    }
public function get_goods_by_goodscode($goodscode)
{
    //DBに接続する
    $dbh=DAO::get_db_connect();

    $sql="SELECT * FROM Goods WHERE goodscode=:goodscode";

    $stmt=$dbh->prepare($sql);

    //SQLに変数の値を当てはめる
    $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_INT);

    //SQLを実行する
    $stmt->execute();

    //1件分のデータをGoodsクラスのオブジェクトとして取得する
    $goods=$stmt->fetchObject('Goods');
    return $goods;
}
public function get_goods_by_keyword($keyword){

    //DBに接続する
    $dbh=DAO::get_db_connect();

    $sql="select * from goods where goodsname like  :search_term OR detail LIKE :search_term";
    $stmt=$dbh->prepare($sql);

    $searchTerm='%'.$searchTerm.'%';

    //SQLに変数の値を当てはめる
    $stmt->bindValue(':search_term',$searchTerm,PDO::PARAM_STR);

    //SQLを実行する
    $stmt->execute();

    $results=$stmt->fetchALL(PDO::FETCH_ASSOC);
    return $results;
}
}
