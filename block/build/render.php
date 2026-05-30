<div <?php echo get_block_wrapper_attributes(); ?>>
<?php
$formid = isset( $attributes['formid'] ) ? absint( $attributes['formid'] ) : 1;
echo do_shortcode( '[lead-form form-id=' . $formid . ']' );
?>
</div>
