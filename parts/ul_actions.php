<ul class="actions">
    <li><?php if ( function_exists( 'get_simple_likes_button' ) ) {echo get_simple_likes_button( get_the_ID() ); } ?></li>
    <li><a href="<?php the_permalink(); ?>#respond" rel="bookmark"><svg class="icon"><use xlink:href="#icon-comment" /></svg></a></li>
    <li><svg class="icon"> <use xlink:href="#icon-three-dots"></use> </svg></li>
</ul>