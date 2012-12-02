<div class='container'>
    <div class="row">
        <div class='span4'>
            <h3><?php echo $app['name']; ?></h3>
            <img src='<?php echo $app['icon_url_100']; ?>' width='100' height='100' />
        </div>
    </div>
        <div class="row">
    <?php foreach($reviews as $review): ?>
            <div class='span4'>
                <h5><?php echo $review['subject']; ?></h5>
                <p>
                    <?php echo " by " . $review['user_name']; ?>
                    <?php if($review['helpful_rated_num']): ?>
                        <?php echo "&#128077;" . $review['helpful_rated_num']; ?>
                    <?php endif; ?>
                    <?php if(intval($review['total_rated_num'] - $review['helpful_rated_num'])): ?>
                        <?php echo "&#128078;" . intval($review['total_rated_num'] - $review['helpful_rated_num']); ?>
                    <?php endif; ?>
                </p>
                <p><?php echo $review['content']; ?></p>
            <hr />
            </div>
    <?php endforeach; ?>
        </div>
    <p><a href='<?php echo $app['store_url']; ?>'>Jump to iTunes</a></p>
</div>
