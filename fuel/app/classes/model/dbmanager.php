<?php
namespace Model;

/**
 * DBを管理する
 * @トップページから投稿された案件を保存
 * @案件詳細ページの投稿案件を取得
 * @案件詳細ページから投稿されたパブリックメッセージを保存
 * @案件詳細ページから投稿されたパブリックメッセージとユーザーネームを取得
 * @
 */
class DbManager extends \Model{
    /**
     * @トップページから投稿された案件を保存
     */
    public static function insert_proposition($title, $type, $price_limit, $price_lower_limit, $contents, $user_id, $create_date){
        \DB::insert('proposition')->set(array(
            'title' => $title,
            'type' => $type,
            'price_limit' => $price_limit,
            'price_lower_limit' => $price_lower_limit,
            'contents' => $contents,
            'user_id' => $user_id,
            'create_date' => $create_date
        ))->execute();
    }

    /**
     * @案件詳細ページの投稿案件を取得
     */
    public static function get_proposition($proposition_id){
        return \DB::select()->from('proposition')->where('id', $proposition_id)->as_assoc()->execute();
    }

    /**
     * @案件詳細ページから投稿されたパブリックメッセージを保存
     */
    public static function insert_public_msg($msg, $user_id, $proposition_id, $create_date){
        \DB::insert('public_msg')->set(array(
            'msg' => $msg,
            'user_id' => $user_id,
            'proposition_id' => $proposition_id,
            'create_date' => $create_date
        ))->execute();
    }

    /**
     * @案件詳細ページから投稿されたパブリックメッセージとユーザーネームを取得
     */
    public static function get_public_msg($proposition_id){
        return \DB::select()->from('public_msg')->join('users', 'LEFT')->on('users.id', '=', 'public_msg.user_id')
                ->where('proposition_id', $proposition_id)->as_assoc()->execute();
    }

}