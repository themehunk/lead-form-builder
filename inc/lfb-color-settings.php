<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class LFB_COLORS {

    function change_color() {
        $svg = lfb_svg( 'palette', 15 );
        return '<button type="button" class="lfb-cd-btn">' . $svg . __( 'Customize Design', 'lead-form-builder' ) . '</button>';
    }

    function lfb_color_form( $fid ) {
        ?>
<div class="lfb-design-preview" colorid="<?php echo esc_attr( $fid ); ?>"></div>

<div class="cd-panel from-right">

    <header class="cd-panel-header">
        <div class="lfb-panel-hdr">
            <div class="lfb-panel-hdr-left">
                <?php echo lfb_svg( 'palette', 18 ); ?>
                <span><?php _e( 'Form Design', 'lead-form-builder' ); ?></span>
            </div>
            <a href="#0" class="cd-panel-close" title="<?php _e( 'Close', 'lead-form-builder' ); ?>">
                <?php echo lfb_svg( 'close' ); ?>
            </a>
        </div>
    </header>

    <div class="cd-panel-container">
        <div id="lfb-design-root"></div>
    </div><!-- .cd-panel-container -->

</div><!-- .cd-panel -->
        <?php
    }
}
