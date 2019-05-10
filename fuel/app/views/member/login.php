<?=$head?>
<?=$header?>
    <section class="l-container">
        <?php if(!empty($error)):?>
            <?php foreach($error as $key => $val):?>
                <?=$val?>
            <?php endforeach;?>
        <?php endif;?>
        <?=$loginform?>
    </section>
<?=$footer?>
