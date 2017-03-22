<?php
?>
<h3>
    <?php echo $title ?>
</h3>
<div class="yith_wcevti_users_user_content">
    <?php foreach ($users_tickets as $user_data){
        ?>
        <div class="_user_item">
            <div class="_user_image">
                <?php echo $user_data['avatar'];?>
            </div>
            <p class="_user_field">
                <span class="_user_name"><?php echo $user_data['display_name']?> </span>
                <?php if (isset($user_data['purchased_tickets'])){?>
                <span class="_tickets_purchased"><?php echo $user_data['purchased_tickets'] .' '. __('tickets purchased'); ?> </span>
                <?php }?>
            </p>
        </div>

        <?php
    }?>
</div>