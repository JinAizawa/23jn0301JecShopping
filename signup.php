<?php
    require_once './helpers/MemberDAO.php';

    //POSTメソッドでリクエストされたとき
    if($_SERVER['REQUEST_METHOD']==='POST'){
        //入力された会員データを受け取る
        $email=$_POST['email'];
        $password=$_POST['password'];
        $password2=$_POST['password_confirm'];
        $membername=$_POST['name'];
        $zipcode=$_POST['zipcode'];
        $address=$_POST['address'];
        $tel1=$_POST['tel1'];
        $tel2=$_POST['tel2'];
        $tel3=$_POST['tel3'];
    
    $memberDAO=new MemberDAO();

    //メールアドレスの形式チェック
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errs['email']='メールアドレスの形式が正しくありません。';
    }
    //メールアドレスが登録されているか確認
    elseif($memberDAO->email_exists($email)==true){
        $errs['email']='このメールアドレスはすでに登録されています。';
    }
    //パスワードの文字数チェック
    if (!preg_match('/^.{4,}$/', $password)) {
        $errs['password'] = 'パスワードは4文字以上で入力してください。';
    }
    //パスワードの一致チェック
    elseif($password!=$password2){
        $errs['password']='パスワードが一致しません。';
    }
    //名前の未入力チェック
    if($membername===''){
        $errs['name']='お名前を入力してください。';
    }
    //郵便番号の形式チェック
    if(!preg_match('/^[0-9]{3}-[0-9]{4}$/',$zipcode)){
        $errs['zipcode']='郵便番号は3桁-4桁で入力してください。';
    }
    //住所の入力チェック
    if($address===''){
        $errs['address']='住所を入力してください。';
    }
    if (
        !preg_match('/\A\d{2,5}\z/', $tel1) ||
        !preg_match('/\A\d{1,4}\z/', $tel2) ||
        !preg_match('/\A\d{4}\z/', $tel3)
    ) {
                $errs['tel']='電話番号は半角数字2～5桁、1～4桁、4桁で入力してください。';
    }
    //エラーがなければDBに会員データを追加
    if(empty($errs)){
    $member=new Member();
    $member->email=$email;
    $member->password=$password;
    $member->membername=$membername;
    $member->zipcode=$zipcode;
    $member->address=$address;

    //電話番号をハイフン(-)で連結する
    $member->tel='';
        if($tel1!==''&&$tel2!==''&&$tel3!==''){
            $member->tel="{$tel1}-{$tel2}-{$tel3}";
        }
        //DBに会員データを登録する
        $memberDAO->insert($member);

        //登録完了ページsignupEnd.phpへ遷移
        
        header('Location: signupEnd.php');

        exit;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新規会員登録</title>
</head>
<body>
    <?php include 'header2.php';?>

    <h1>会員登録</h1>
    <p>以下の項目を入力し、登録ボタンをクリックしてください(*は必須)</p>
    
<form action ="signup.php" method="POST">
    <table>
        <tr>
            <td>メールアドレス*</td>
            <td><input type="email" name="email">
            <span style="color:red"><?=@$errs['email']?></span>
            </td>
        </tr>
        <tr>
            <td>パスワード*</td>
            <td><input type="password" minlength="4" name="password">
            <span style="color:red"><?=@$errs['password']?></span>
            </td>
        </tr>
        <tr>
            <td>パスワード(再入力)*</td>
            <td><input type="password" name="password_confirm"></td>
        </tr>
        <tr>
            <td>お名前*</td>
            <td><input type="text" name="name" required></td>
        </tr>
        <tr>
            <td>郵便番号*</td>
            <td><input type="text" pattern="\d{3}-\d{4}" title="郵便番号は3桁-4桁でハイフン(-)を入れて入力してください。" name="zipcode">
            <span style="color:red"><?=@$errs['zipcode']?></span>
            </td>
        </tr>
        <tr>
            <td>住所*</td>
            <td><input type="text" name="address" require>
            <span style="color:red"><?=@$errs['address']?></span>
            </td>
        </tr>
        <tr>
            <td>電話番号</td>
            <td>
                <input type="tel" name="tel1" size="4">-
                <input type="tel" name="tel2" size="4">-
                <input type="tel" name="tel3" size="4">
                <span style="color:red"><?=@$errs['tel']?></span>
            </td>
        </tr>
    </table>
    <input type="submit" name="signup">
</form>
</body>
</html>
