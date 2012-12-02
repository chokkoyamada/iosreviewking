<div class="container">
	<h3>Most Reviewed Apps</h3>
    <div class="row">
        <?php foreach($app_list as $app): ?>
            <div class="span4">
                <img src='<?php echo $app['icon_url']; ?>' width='60' height='60' />
                <a href="/index/app_detail/{$app['app_id']};" ?>"<?php echo $app['name']; ?>"</a>
				<?php echo "{$app['num']} reviews"; ?> 
            </div> 
        <?php endforeach; ?>
    </div>
    <div>
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
