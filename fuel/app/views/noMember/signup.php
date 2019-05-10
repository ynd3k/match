<?=$head?>
<?=$header?>
    <section class="l-container">
        <?php if(!empty($error)):?>
            <? foreach($error as $key => $val):?>
                <?=$val?>
            <? endforeach;?>
        <?php endif;?>
        <?=$signupform?>
    </section>
<?=$footer?>
