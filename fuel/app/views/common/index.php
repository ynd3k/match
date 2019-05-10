<?=$head?>
<?=$header?>
    <section class="l-container">
        <a href="" class="c-btn c-btn--active">案件投稿</a>
            <?php 
                if( !empty($error)){
                    foreach($error as $key=>$val){
                        echo $val;
                    }
                }
            ?>
        <?=$post_proposition_form?>
        
        <div class="l-proposition-container" id="proposition-list">
            <div class="l-tab">
                <proposition-switch-btn
                    type="単発"
                    v-on:event="switchTabSingle"
                    v-bind:class="{'c-btn--active': isShowSingle}"
                ></proposition-switch-btn>
                <proposition-switch-btn
                    type="レベニューシェア"
                    v-on:event="switchTabShare"
                    v-bind:class="{'c-btn--active': isShowShare }"
                ></proposition-switch-btn>
                
            </div>
            <proposition-list-single
                v-if="isShowSingle"
            ></proposition-list-single>
            <proposition-list-share
                v-if="isShowShare"
            ></proposition-list-share>
        </div>
    </section>
<?=$footer?>
