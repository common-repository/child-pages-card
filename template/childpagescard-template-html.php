<?php
/**
 * Child Pages Card Template HTML File
 *
 * @package WordPress
 * @subpackage Child Pages Card
 * @since Child Pages Card 2.00
 * @version 1.00
 */

?>
<div class="childpagescard_wrap">
	<a style="text-decoration: none;" href="<?php echo esc_url( $url ); ?>">
	<?php if ( $img ) : ?>
		<figure class="childpagescard_img_block<?php echo esc_html( $hash ); ?>">
			<img style="border-radius: 5px; width: <?php echo esc_attr( $img_width ); ?>px; height: <?php echo esc_attr( $img_height ); ?>px;" src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
		</figure>
	<?php endif; ?>
	<div class="childpagescard_inner">
		<div class="childpagescard_border<?php echo esc_html( $hash ); ?>">
			<div class="childpagescard_title<?php echo esc_html( $hash ); ?>">
				<?php echo esc_html( $title ); ?>
			</div>
			<?php if ( ! is_null( $excerpt ) ) : ?>
				<div class="childpagescard_description<?php echo esc_html( $hash ); ?>">
					<?php echo esc_html( $excerpt ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div style="clear: both;"></div>
	</a>
</div>
