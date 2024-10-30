<?php
/**
 * Child Pages Card Template CSS File
 *
 * @package WordPress
 * @subpackage Child Pages Card
 * @since Child Pages Card 2.00
 * @version 1.00
 */

?>
<style>
.childpagescard_img_block<?php echo esc_html( $hash ); ?> {
  float: <?php echo esc_attr( $img_pos ); ?>;
  padding: 10px;
}
.childpagescard_border<?php echo esc_html( $hash ); ?> {
  border-<?php echo esc_attr( $border_pos ); ?>: solid <?php echo esc_attr( $color_width ); ?>px <?php echo esc_attr( $color ); ?>;
  padding: 0.25em 0.25em;
  color: #494949;
  background: transparent;
}
.childpagescard_title<?php echo esc_html( $hash ); ?> {
  line-height: <?php echo esc_attr( $t_line_height ); ?>%;
  font-weight: bold;
  display: block;
}
.childpagescard_description<?php echo esc_html( $hash ); ?> {
  line-height: <?php echo esc_attr( $d_line_height ); ?>%;
  color: #333;
}
</style>
