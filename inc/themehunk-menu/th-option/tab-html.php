<?php
$getUrlTab   = isset( $_GET['th-tab'] ) && $_GET['th-tab'] ? sanitize_key( $_GET['th-tab'] ) : false;
$tabActiveRp = $getUrlTab == 'recommended-plugin' ? 'active' : '';
if ( ! $tabActiveRp ) {
    $tabActiveWl = 'active';
}
?>

<div class="th-market-container">
    <div class="th-market-wrapper">

        <div class="wrap-th about-wrap-th theme_info_wrapper">
            <?php include_once THEMEHUNK_PDIR . 'th-option/header.php'; ?>
        </div>

        <div class="content-wrap">
            <div class="main">

                <div class="tab-left">
                    <div class="tab-heading-wrap">
                        <h3><?php _e( 'Available Plugins', 'lead-form-builder' ); ?></h3>
                        <div class="th-filter-wrap">
                            <select id="th-plugin-filter">
                                <option value="all"><?php _e( 'All Plugins', 'lead-form-builder' ); ?></option>
                                <option value="installed"><?php _e( 'Installed', 'lead-form-builder' ); ?></option>
                                <option value="not-installed"><?php _e( 'Not Installed', 'lead-form-builder' ); ?></option>
                            </select>
                        </div>
                    </div>

                    <div id="Recommended-Plugin" class="tabcontent active <?php echo esc_attr( $tabActiveRp ); ?>">
                        <div id="th-plugin-grid" class="rp-two-column">
                            <?php $this->plugin_install(); ?>
                        </div>
                    </div>
                </div><!-- /tab-left -->

                <div class="sidebar-wrap">
                    <div class="sidebar">
                        <?php include( 'sidebar.php' ); ?>
                    </div>
                </div>

            </div><!-- /main -->
        </div><!-- /content-wrap -->

    </div><!-- /th-market-wrapper -->
</div><!-- /th-market-container -->
