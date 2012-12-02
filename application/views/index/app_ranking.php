<div class='container'>
    <h3>Today's Top Grossing</h3>
    <div class='row'>
    <?php foreach($app_list as $app): ?>
        <div class='span4'>
            <h5>#<?php echo "{$app['rank']}"; ?> <a href="/index/app_detail/<?php echo $app['app_id']; ?>"><?php if(strlen($app['name']) > 50){echo substr($app['name'],0,47)."...";}else{echo $app['name'];} ?></a></h5>
            <img src='<?php echo $app['icon_url']; ?>' width='60' height='60' />
            <?php echo rating_to_star($app['average_user_rating_for_current_version']); ?>
            <?php echo "&#128077;" . $app['user_rating_count_for_current_version']; ?>
        </div> 
    <?php endforeach; ?>
    </div>
    <div>
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
