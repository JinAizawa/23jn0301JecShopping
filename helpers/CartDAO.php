<?php
require_once 'DAO.php';

class Cart{
    public int     $memberid;
    public string  $goodscode;
    public string  $goodsname;
    public int     $price;
    public string  $detail;
    public string  $goodsimage;
    public int     $num;
}
class CartDAO{
        
    //会員のカートデータを取得する
    public function get_cart_by_memberid(int $memberid){
        //DBに接続する
        $dbh=DAO::get_db_connect();

        $sql="select memberid,cart.goodscode,goodsname,price,detail,goodsimage,num
        from  Cart inner join goods
        on goods.goodscode=Cart.goodscode where memberid=:memberid;";

        $stmt=$dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);

        //SQLを実行する
        $stmt->execute();

        //取得したデータをCartクラスの配列にする
        $data=[];
        while($row=$stmt->fetchObject('Cart')){
            $data[]=$row;
        }
        return $data;
    }
    //指定した商品がカートテーブルに存在するか確認する
    public function cart_exists(int $memberid,string $goodscode){
        //DBに接続する
        $dbh=DAO::get_db_connect();

        $sql="select * from cart where memberid=:memberid and goodscode=:goodscode";

        $stmt=$dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
        $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_INT);

        //SQLを実行する
        $stmt->execute();

        if($stmt->fetch()!==false){
            return true; //カートに商品が存在する
        }else{
            return false;//カートに商品が存在しない
        }
    }
    public function insert(int $memberid,string $goodscode,int $num){
        $dbh=DAO::get_db_connect();

        //カートテーブルに商品がないとき
        if(!$this->cart_exists($memberid,$goodscode)){
            //カートテーブルに商品を登録する
            $sql="INSERT INTO cart(memberid, goodscode, num)
            VALUES (:memberid, :goodscode,:num)";

            $stmt=$dbh->prepare($sql);

            $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
            $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR); 
            $stmt->bindValue(':num',$num,PDO::PARAM_INT);
            $stmt->execute();
        }
        //カートテーブルに同じ商品があるとき
        else{
            //カートテーブルに商品個数を加算する
            $sql="update cart set num=num+:num where memberid=:memberid and goodscode=:goodscode";

            $stmt=$dbh->prepare($sql);

            $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
            $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR); 
            $stmt->bindValue(':num',$num,PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    public function update(int $memberid,string $goodscode,int $num){
        $dbh=DAO::get_db_connect();


        $sql="update cart set num=:num where memberid=:memberid and goodscode=:goodscode";

        $stmt=$dbh->prepare($sql);

        $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
        $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR); 
        $stmt->bindValue(':num',$num,PDO::PARAM_INT);
        $stmt->execute();
    }
    public function delete(int $memberid,string $goodscode){
        $dbh=DAO::get_db_connect();
        
        $sql="delete from cart where memberid =:memberid  and goodscode = :goodscode";

        $stmt=$dbh->prepare($sql);

        $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
        $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR); 
        $stmt->execute();
    }
    public function delete_by_memberid(int $memberid){
        //会員のカートデータをすべて削除
        $dbh=DAO::get_db_connect();

        $sql="delete from cart where memberid=:memberid";

        $stmt=$dbh->prepare($sql);

        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);

        $stmt->execute();
    }
}