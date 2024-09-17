<?php get_header(); ?>




<ul>
    <?php while (have_posts()):
        the_post(); ?>

        <li>
            <a href="<?php the_permalink(); ?>">
                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title(); ?></h5>
                        <p class="card-text"><?php the_content(); ?></p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </a>

        </li>
    <?php endwhile; ?>
</ul>

<?php get_footer(); ?>