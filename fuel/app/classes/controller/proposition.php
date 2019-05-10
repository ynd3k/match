<?php
/**
 * 案件詳細
 */
use \Model\DbManager;

 class Controller_Proposition extends Controller{
    

    public function action_index(){
        $proposition_id = Input::get('proposition_id');
        $proposition_detail_data = DbManager::get_proposition($proposition_id);
        $error = '';
        $form = Fieldset::forge('publicmsgform');
        $vali = '';
        $view = View::forge('member/proposition');

        $form->form()->set_attribute('method', 'get');
        $form->add('public_msg', 'メッセージ', array('type'=>'text'))
        ->add_rule('required')
        ->add_rule('min_length', 1)
        ->add_rule('max_length', 255);
        //urlクエリ文字列のproposition_idを保持する
        $form->add('proposition_id', '', array('type'=>'hidden', 'value'=>$proposition_id));

        $form->add('submit', '', array('type'=>'submit', 'value'=>'投稿'));

        if(Input::get() && Auth::check()){

            $vali = $form->validation();
            if($vali->run()){
                Log::warning('バリデーションOK');
                DbManager::insert_public_msg(Input::get('public_msg'), Arr::get(Auth::get_user_id(), 1), $proposition_id,  date('Y-m-d H:i:s') );
            }
            else{
                Log::warning('バリデーションエラー');
                $error = $vali->error();
                //あとで直す バリデーションエラーなのになぜか$errorが空
                //var_dump($error);
                $form->repopulate();
            }
        }
        else{
            Log::warning(Input::get('public_msg'));
            Log::warning('POST送信→ログインされてない');
        }
        $public_msg_data = DbManager::get_public_msg($proposition_id);

        $view->set_global('publicmsgform', $form->build(''), false);
        $view->set_global('error', $error);
        $view->set_global('public_msg_data', $public_msg_data);
        $view->set('head', View::forge('template/head'));
        $view->set('header', View::forge('template/header'));
        $view->set('footer', View::forge('template/footer'));
        $view->set('proposition_detail_data', $proposition_detail_data);
        
        return $view;
    }

    
 }