<?php
/**
 * トップページを管理する
 * @案件投稿
 * @ユーザー登録
 * @ログイン
 */
use \Model\DbManager;

class Controller_Top extends Controller{
    /**
     * @案件投稿
     * 案件投稿フォーム作成
     * POST送信があればログインチェック
     * フォーム内容をバリデーション
     * フォーム内容をDBに保存
     * ビューに変数を渡す
     */
    public function action_index(){
        $view = View::forge('common/index');
        $error = '';
        $form = Fieldset::forge('post_proposition_form');
        $type_ops = array('単発', 'レベニューシェア');
        $price_ops = array(false, '1000', '2000', '3000');

        $form->add('title', 'タイトル', array('type'=>'text'))
        ->add_rule('required')
        ->add_rule('min_length', 1)
        ->add_rule('max_length', 255);
        
        $form->add('type', '案件種別', array('options'=>$type_ops, 'type'=>'radio', 'value'=>'true', 'class'=>'js-change-radio-proposition-type'))
        ->add_rule('required');
        
        $form->add('price_limit', '金額(上限)', array('options'=>$price_ops, 'type'=>'select'))
        ->add_rule('required')
        //金額が選択されていない(0)の場合はエラー
        ->add_rule('match_pattern', '/^[1-9]/');

        $form->add('price_lower_limit', '金額(下限)', array('options'=>$price_ops, 'type'=>'select'))
        ->add_rule('required')
        //金額が選択されていない(0)の場合はエラー
        ->add_rule('match_pattern', '/^[1-9]/');

        $form->add('contents', '内容', array('type'=>'text'))
        ->add_rule('required')
        ->add_rule('max_length', 255);

        $form->add('submit', '', array('type'=>'submit', 'value'=>'投稿'));
        
        if(Input::post()){
            if(Auth::check()){
                //案件種別でレベニューシェアを選択した場合は金額項目を非表示にするが存在はしているので、そのまま投稿すると金額が選択されていないためバリデーションエラーになる
                //そのバリデーションエラーを回避するため金額項目を一旦削除する
                if($type_ops[Input::post('type')] === 'レベニューシェア'){
                    $form->delete('price_limit');
                    $form->delete('price_lower_limit');
                }
                $vali = $form->validation();
                if($vali->run()){
                    //レベニューシェアを選択し投稿してバリデーションエラーになった場合　再度投稿しようとするとエラーになるのでそれを回避
                    $priceLimit = (!empty(Input::post('price_limit'))) ? $price_ops[Input::post('price_limit')] : false;
                    $priceLowerLimit = (!empty(Input::post('price_lower_limit'))) ? $price_ops[Input::post('price_lower_limit')] : false;

                    DbManager::insert_proposition(Input::post('title'), $type_ops[Input::post('type')], $priceLimit, $priceLowerLimit, Input::post('contents'), Arr::get(Auth::get_user_id(), 1), date('Y-m-d H:i:s'));

                    //案件種別がレベニューシェアの場合　一旦削除した金額項目を再度表示
                    if($type_ops[Input::post('type')] === 'レベニューシェア'){
                        $form->add_after('price_limit', '金額(上限)', array('options'=>$price_ops, 'type'=>'select'), array(), 'type' )
                        ->add_rule('required')
                        ->add_rule('match_pattern', '/^[1-9]/');

                        $form->add_after('price_lower_limit', '金額(下限)', array('options'=>$price_ops, 'type'=>'select'), array(), 'type' )
                        ->add_rule('required')
                        ->add_rule('match_pattern', '/^[1-9]/');
                    }             
                }
                else{
                    $error = $vali->error();
                    $form->repopulate();
                }
                
            }
            else{
                //あとで直す 全部消してResponse~をコメントアウト解除
                Log::warning('s');
                Auth::login('d@d.d', 'd');
                Auth::force_login(Arr::get(Auth::get_user_id(), 1));
                DbManager::insert_proposition(Input::post('title'), $type_ops[Input::post('type')], $price_ops[Input::post('price_limit')], $price_ops[Input::post('price_lower_limit')], Input::post('contents'), Arr::get(Auth::get_user_id(), 1), date('Y-m-d H:i:s'));
                //Response::redirect('top/login');
            }
        }
        $view->set_global('post_proposition_form', $form->build(''), false);
        $view->set_global('error', $error);
        $view->set('head', View::forge('template/head'));
        $view->set('header', View::forge('template/header'));
        $view->set('footer', View::forge('template/footer'));
        return $view;
    }

    /**
     * @ユーザー登録
     * ユーザー登録フォーム作成
     * POST送信があればバリデーション
     * フォーム内容をDBに保存
     * トップページに遷移
     * ビューに変数を渡す
     */
    public function action_signup(){
        $error = '';
        $formData = '';

        $form = Fieldset::forge('signupform');
        $form->add('username', 'ユーザー名', array('type'=>'text'))
        ->add_rule('required')
        ->add_rule('min_length', 1)
        ->add_rule('max_length', 255);

        $form->add('email', 'メールアドレス', array('type'=>'email'))
        ->add_rule('required')
        ->add_rule('valid_email')
        ->add_rule('min_length', 1)
        ->add_rule('max_length', 255);

        $form->add('password', 'パスワード', array('type'=>'password'))
        ->add_rule('required')
        ->add_rule('min_length', 1)
        ->add_rule('max_length', 255);

        $form->add('password_re', 'パスワード(再入力)', array('type'=>'password'))
        ->add_rule('required')
        ->add_rule('match_field', 'password');

        $form->add('submit', '', array('type'=>'submit', 'value'=>'登録'));

        if(Input::post()){
            $val = $form->validation();
            if($val->run()){
                Log::warning('success');
                $formData = $val->validated();
                $auth = Auth::instance();
                if($auth->create_user($formData['username'], $formData['password'], $formData['email'])){
                    Log::warning('ユーザー登録完了');
                }
                else{
                    Log::warning('ユーザー登録失敗');
                }
            }
            else{
                Log::warning('fail');
                $error = $val->error();
            }
            $form->repopulate();
        }

        $view = View::forge('noMember/signup');
        $view->set('head', View::forge('template/head'));
        $view->set('header', View::forge('template/header'));
        $view->set('footer', View::forge('template/footer'));
        $view->set_global('signupform', $form->build(''), false);
        $view->set_global('error', $error);
        return $view;
    }

    /**
     * @ログイン
     * ログインフォームを作成
     * POST送信があればバリデーション
     * フォーム内容を元にログインしトップページに遷移
     * ビューに変数を渡す
     */
    public function action_login(){
        $error = '';
        $form = Fieldset::forge('loginform');

        $form->add('email', 'メールアドレス', array('type'=>'email'))
        ->add_rule('required')
        ->add_rule('valid_email')
        ->add_rule('min_length', 1)
        ->add_rule('max_length', 255);

        $form->add('password', 'パスワード', array('type'=>'password'))
        ->add_rule('required')
        ->add_rule('min_length', 1)
        ->add_rule('max_length', 255);

        $form->add('submit', '', array('type'=>'submit', 'value'=>'ログイン'));

        if(Input::post()){
            $val = $form->validation();
            if($val->run()){
                Log::warning('バリデーション成功');
                $auth = Auth::instance();
                if($auth->login(Input::post('email'), Input::post('password'))){
                    Log::warning('ログイン成功');
                    //あとで直す
                    Auth::force_login(Arr::get(Auth::get_user_id(), 1));

                    //コントローラー名/index
                    Response::redirect('top/index');
                }
                else{
                    Log::warning('ログイン失敗');
                }
            }
            else{
                Log::warning('バリデーション失敗');
                $error = $val->error();
            }
            $form->repopulate();
        }
        
        $view = View::forge('member/login');
        $view->set('head', View::forge('template/head'));
        $view->set('header', View::forge('template/header'));
        $view->set('footer', View::forge('template/footer'));
        $view->set_global('loginform', $form->build(''), false);
        $view->set_global('error', $error);
        return $view;
    }

}