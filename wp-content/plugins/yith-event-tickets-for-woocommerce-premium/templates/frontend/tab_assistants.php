<?php
if(isset($organization['display'])){
    if('on' == $organization['display']){
      echo  do_shortcode('[organizers]');
    }
}

echo do_shortcode('[users_purchased]');