<?php
/**
 * Vueの単発案件コンポーネントからAjax通信を受け取る
 * 単発案件一覧をDBから取得し、単発案件のコンポーネントに結果をJson形式で渡す
 */
try{
    $dsn = 'mysql:host=localhost;dbname=match;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    );
    $dbh = new PDO($dsn, $user, $password, $options);

    $stmt = $dbh->prepare('SELECT * FROM proposition WHERE type=:type');
    if($stmt->execute(array('type'=>'単発'))){
        echo json_encode(array(
        'proposition' => $stmt->fetchAll()
        ));
    }
    else{
        return;
    }
}
catch(Exception $e){
    var_dump($e->getMessage());
}


