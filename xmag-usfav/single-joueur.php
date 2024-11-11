<?php
/**
 * The template for displaying all single posts.
 *
 * @package xMag
 * @since xMag 1.0
 */

get_header(); ?>

<?php if ( have_posts() ) : ?>
		
	<?php if ( get_theme_mod('xmag_post_featured_image') && get_theme_mod('xmag_post_featured_image_size') == 'fullwidth' && has_post_thumbnail() ) : ?>
		
		<div class="featured-image">
			<header class="entry-header overlay">
				<span class="category"><?php the_category(' '); ?></span>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<div class="entry-meta">
					<?php
						xmag_posted_on();
						xmag_entry_comments();
					?>
				</div>
			</header>
			<div class="cover-bg" style="background-image:url(<?php the_post_thumbnail_url('xmag-thumb'); ?>)"></div>
		</div><!-- .featured-image -->

	<?php endif; ?>
	
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
		
                <article class="post-player" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <section class="left-player">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php if ( get_theme_mod('xmag_post_featured_image_size', 'default') == 'default' ) : ?>
                                    <figure class="entry-thumbnail">
                                        <?php the_post_thumbnail('large'); ?>
                                    </figure>
                            <?php endif; ?>
                        <?php endif; ?>
                    </section>
                    <section class="right-player">
                        <header class="entry-header">

                            <div class="name">
                                <h1><span><?php echo get_field('prenom');?></span> <span><?php echo get_field('nom');?></span></h2>
                                
                                <div class="infos">
                                    <span class="age">
                                        <?php
                                            $date_string = get_field( 'date_naissance');  
                                            $birthday = new DateTime($date_string);
                                            $interval = $birthday->diff(new DateTime);
                                            echo $interval->y . ' ans'; 
                                        ?>
                                    </span>
                                    -
                                    <span class="category">
                                        <?php echo get_field('categorie');?>
                                    </span>

                                    <?php
                                        $object = get_field_object('sexe');
                                        if ($object['value'] == 'homme'):?>
                                            <i class="fa-solid fa-mars"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-venus"></i>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <span class="points">
                                <?php echo get_field('points_fftt');?>
                            </span>
                        </header>

                        <div class="icons-player">
                            <div class="icon-player licence">
                                <i class="fa-regular fa-address-card"></i>
                                <div class="info">
                                    <label>N° licence</label>
                                    <span><?php echo get_field('numero_licence');?></span>
                                </div>
                            </div>

                            <div class="icon-player team">
                                <i class="fa-solid fa-users"></i>
                                <div class="info">
                                    <label>Equipe</label>
                                    <span><?php
                                        $equipe = get_field('equipe');
                                        if ($equipe) {
                                            echo '<a href="' . get_permalink($equipe->ID) . '">' . $equipe->post_title . '</a>';
                                        } else {
                                            echo 'Pas d\'équipe assignée';
                                        }
                                    ?></span>
                                </div>
                            </div>

                            <div class="icon-player date">
                                <i class="fas fa-calendar-check"></i>
                                <div class="info">
                                    <label>Arrivée en</label>
                                    <span><?php 
                                        $date = new DateTimeImmutable(get_field('date_arrivee_club'));
                                        echo $date->format('Y');
                                    ?></span>
                                </div>
                            </div>

                            <div class="icon-player hand">
                                <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                                <div class="info">
                                    <label>Main</label>
                                    <span><?php
                                        $object = get_field_object('main');
                                        echo $object['choices'][get_field('main')];
                                    ?></span>
                                </div>
                            </div>

                            <div class="icon-player points">
                            <i class="fa-solid fa-chart-simple"></i>
                                <div class="info">
                                    <label>Points FFTT</label>
                                    <span><?php echo get_field('points_fftt');?></span>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        <div class="entry-content">
                            <?php 
                                the_content();
                                wp_link_pages( array(
                                'before' => '<div class="page-links">' . __( 'Pages:', 'xmag' ),
                                'after'  => '</div>',
                                'link_before' => '<span class="page-number">',
                                'link_after'  => '</span>',
                                ) );
                            ?>
                        </div><!-- .entry-content -->
                    </section>
                        
                    <footer class="entry-footer">
                        <?php xmag_entry_footer(); ?>
                    </footer><!-- .entry-footer -->

                </article><!-- #post-## -->

                <?php
                the_post_navigation( array(
                    'prev_text'	=> __( 'Previous Post', 'xmag' ) . '<span>%title</span>',
                    'next_text'	=> __( 'Next Post', 'xmag' ) . '<span>%title</span>',
                ) );
                ?>
				
			<?php endwhile; // end of the loop. ?>
			
		</main><!-- #main -->
	</div><!-- #primary -->

    <?php echo do_shortcode('[css_calendar_list]'); ?>

<?php endif; ?>
<?php get_footer(); ?>
