<?=$head?>
<?=$header?>
    <section class="l-container">
        <h2>タイトル</h2>
       
        <dl class="">
            <?php foreach($proposition_detail_data as $key => $val):?>
                <?php if( Arr::get(Auth::get_user_id(), 1) ==  $val['user_id']):?>
                    <button class="js-edit-proposition">編集する</button>
                <?php endif;?>
                <dt>案件種別</dt>
                <dd><?=$val['type']?></dd>
                <dt>金額上限</dt>
                <dd><?=$val['price_limit']?></dd>
                <dt>金額下限</dt>
                <dd><?=$val['price_lower_limit']?></dd>
                <dt>内容</dt>
                <dd><?=$val['contents']?></dd>
            <?php endforeach;?>
        </dl>
        <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        <button class="c-btn c-btn--active">応募</button>
    </section>
    <section class="l-container">
        <h2>パブリックメッセージ</h2>
        <div>
            <?php 
            use \Model\DbManager;
            ?>
            <?php if(!empty($public_msg_data)):?>
                    <?php foreach($public_msg_data as $key => $val):?>
                        <?=$val['msg']?>
                        <?=$val['username'].'さん'?><br>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        <div>
            <?php if(!empty($error)):?>
                <?php foreach($error as $key => $val):?>
                    <?=$val?>
                <?php endforeach;?>
            <?php endif;?>
            <?=$publicmsgform?>
        </div>
    </section>
<?=$footer?>
