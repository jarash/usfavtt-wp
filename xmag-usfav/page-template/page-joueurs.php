<?php
/**
 * Template Name: Liste des joueurs
 *
 */

get_header();

$args = [
    'post_type' => 'joueur',
    'posts_per_page' => -1,
    'meta_key' => 'points_fftt',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
];

$query = new WP_Query($args);

?>

<?php if ( have_posts() ) : ?>
	<div id="primary" class="content-area primary-players">
		<main id="main" class="site-main" role="main">
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>

            <?php if ($query->have_posts()) : ?>
                    <ul id="players-list">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                            <li class="player-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="player-photo">
                                            <?php the_post_thumbnail('player-list'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="player-info">
                                        <h2><?php echo get_the_title(); ?></h2>
                                    </div>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php endif; ?>

<?php get_footer(); ?>
