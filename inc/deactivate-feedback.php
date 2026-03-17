<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lead Form Builder – Deactivation Feedback Popup
 * Shows a "Quick Feedback" modal when the user deactivates the plugin,
 * collects the reason, and sends it via REST API before deactivating.
 */

/* =========================================================
 * 1. ENQUEUE SCRIPTS / STYLES on the Plugins admin page
 * ========================================================= */
add_action( 'admin_enqueue_scripts', 'lfb_deactivate_feedback_assets' );
function lfb_deactivate_feedback_assets( $hook ) {
    if ( $hook !== 'plugins.php' ) return;

    wp_enqueue_style(
        'lfb-deactivate-feedback-css',
        LFB_PLUGIN_URL . 'css/deactivate-feedback.css',
        array(),
        LFB_VER
    );
    wp_enqueue_script(
        'lfb-deactivate-feedback-js',
        LFB_PLUGIN_URL . 'js/deactivate-feedback.js',
        array( 'jquery' ),
        LFB_VER,
        true
    );
    $plugin_file = LFB_BASE_DIR_PATH . 'lead-form-builder.php';
    $plugin_data = get_plugin_data( $plugin_file, false, false );

    wp_localize_script( 'lfb-deactivate-feedback-js', 'lfbDeactivate', array(
        'pluginFile'    => 'lead-form-builder/lead-form-builder.php',
        'apiUrl'        => rest_url( 'lfb/v1/deactivate-feedback' ),
        'nonce'         => wp_create_nonce( 'wp_rest' ),
        'pluginName'    => $plugin_data['Name'],
        'pluginVersion' => $plugin_data['Version'],
        'i18n'          => array(
            'submitting' => __( 'Submitting…', 'lead-form-builder' ),
            'submit'     => __( 'Submit & Deactivate', 'lead-form-builder' ),
        ),
    ) );
}

/* =========================================================
 * 2. RENDER MODAL HTML in admin footer (plugins page only)
 * ========================================================= */
add_action( 'admin_footer', 'lfb_deactivate_feedback_modal' );
function lfb_deactivate_feedback_modal() {
    global $hook_suffix;
    if ( 'plugins.php' !== $hook_suffix ) return;
    $reasons = array(
        'no_longer_needed'   => __( 'I no longer need the plugin', 'lead-form-builder' ),
        'found_better'       => __( 'I found a better plugin', 'lead-form-builder' ),
        'not_working'        => __( 'I couldn\'t get the plugin to work', 'lead-form-builder' ),
        'temporary'          => __( 'It\'s a temporary deactivation', 'lead-form-builder' ),
        'missing_feature'    => __( 'Plugin is missing a required feature', 'lead-form-builder' ),
        'other'              => __( 'Other', 'lead-form-builder' ),
    );
    ?>
    <div id="lfb-deactivate-overlay" class="lfb-df-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="lfb-df-title">
        <div class="lfb-df-modal">

            <div class="lfb-df-header">
                <span class="lfb-df-icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="20" rx="4" fill="#e74c3c"/>
                        <text x="10" y="15" text-anchor="middle" font-size="13" font-weight="bold" fill="#fff">!</text>
                    </svg>
                </span>
                <strong id="lfb-df-title"><?php esc_html_e( 'QUICK FEEDBACK', 'lead-form-builder' ); ?></strong>
            </div>

            <div class="lfb-df-body">
                <p><?php esc_html_e( 'If you have a moment, please share why you are deactivating Lead Form Builder:', 'lead-form-builder' ); ?></p>

                <ul class="lfb-df-reasons">
                    <?php foreach ( $reasons as $value => $label ) : ?>
                        <li>
                            <label>
                                <input type="radio" name="lfb_deactivate_reason" value="<?php echo esc_attr( $value ); ?>" />
                                <span><?php echo esc_html( $label ); ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="lfb-df-detail" id="lfb-df-detail-wrap" style="display:none;">
                    <textarea id="lfb-df-detail-text" rows="3" placeholder="<?php esc_attr_e( 'Please share more details (optional)…', 'lead-form-builder' ); ?>"></textarea>
                </div>
            </div>

            <div class="lfb-df-footer">
                <button type="button" id="lfb-df-submit" class="button button-primary lfb-df-submit-btn">
                    <?php esc_html_e( 'Submit & Deactivate', 'lead-form-builder' ); ?>
                </button>
                <a href="#" id="lfb-df-skip" class="lfb-df-skip-link">
                    <?php esc_html_e( 'Skip & Deactivate', 'lead-form-builder' ); ?>
                </a>
            </div>

        </div>
    </div>
    <?php
}

/* =========================================================
 * 3. REST API ROUTE – receive feedback
 * ========================================================= */
add_action( 'rest_api_init', 'lfb_deactivate_feedback_rest_route' );
function lfb_deactivate_feedback_rest_route() {
    register_rest_route( 'lfb/v1', '/deactivate-feedback', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'lfb_rest_save_deactivate_feedback',
        'permission_callback' => function() {
            return current_user_can( 'manage_options' );
        },
        'args' => array(
            'reason' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'required'          => true,
            ),
            'details' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'default'           => '',
            ),
            'site_url' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            ),
            'plugin_version' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            ),
            'plugin_name' => array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            ),
        ),
    ) );
}

function lfb_rest_save_deactivate_feedback( $request ) {
    $data = array(
        'reason'         => $request->get_param( 'reason' ),
        'details'        => $request->get_param( 'details' ),
        'site_url'       => $request->get_param( 'site_url' ),
        'plugin_version' => $request->get_param( 'plugin_version' ),
        'plugin_name'    => $request->get_param( 'plugin_name' ),
    );

    // Send to remote ThemeHunk server
    wp_remote_post(
        'https://themehunk.com/wp-json/wp/v2/themehunk/feedback',
        array(
            'method'   => 'POST',
            'timeout'  => 15,
            'blocking' => true,
            'headers'  => array(
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ),
            'body'     => wp_json_encode( $data ),
        )
    );

    return rest_ensure_response( array(
        'success' => true,
        'message' => __( 'Thank you for your feedback!', 'lead-form-builder' ),
    ) );
}
