<?php
if ( ! defined( 'ABSPATH' ) ) exit;

echo '<div class="wrap">';
lfb_admin_menu_header();

$pro_url = 'https://themehunk.com/product/lead-form-builder-pro/';
$doc_url = 'https://www.themehunk.com/docs/lead-form/';
$sup_url = 'https://themehunk.com/contact-us/';

$features = [
    [ 'icon' => 'palette',  'title' => 'Live Customizer',          'desc' => 'Customize form colors, fonts and styles in real-time with live preview.' ],
    [ 'icon' => 'mail',     'title' => 'SMTP Mail Configure',       'desc' => 'Send reliable emails via your own SMTP server for better deliverability.' ],
    [ 'icon' => 'shield',   'title' => 'Google reCaptcha',          'desc' => 'Protect your forms from spam and bots with Google reCaptcha v2/v3.' ],
    [ 'icon' => 'user',     'title' => 'Admin Email Settings',      'desc' => 'Configure custom admin notifications with flexible email templates.' ],
    [ 'icon' => 'arrow-right', 'title' => 'Thank You Page Redirect','desc' => 'Redirect users to any page after a successful form submission.' ],
    [ 'icon' => 'settings', 'title' => 'User Email Settings',       'desc' => 'Send auto-reply emails to form submitters with custom messages.' ],
    [ 'icon' => 'upload',   'title' => 'Form Import / Export',      'desc' => 'Easily migrate forms between sites with one-click import and export.' ],
    [ 'icon' => 'download', 'title' => 'Lead Export (CSV)',         'desc' => 'Download all your leads as a CSV file for use in any spreadsheet app.' ],
    [ 'icon' => 'box',      'title' => 'MailChimp Addon',           'desc' => 'Automatically add form leads to your MailChimp audience/list.' ],
    [ 'icon' => 'drag',     'title' => 'Drag & Drop Ordering',      'desc' => 'Reorder form fields instantly with smooth drag and drop.' ],
    [ 'icon' => 'upload-cloud', 'title' => 'File Uploading',        'desc' => 'Allow users to upload files and attachments directly through your forms.' ],
    [ 'icon' => 'file',     'title' => 'Premade Layouts',           'desc' => 'Start with beautiful, ready-made form templates and customise in seconds.' ],
];

$themes = [
    [ 'name' => 'Shop Mania',   'img' => LFB_PLUGIN_URL . 'images/1.png',         'url' => 'https://themehunk.com/th-shop-mania' ],
    [ 'name' => 'Top Store',   'img' => LFB_PLUGIN_URL . 'images/3.png',     'url' => 'https://themehunk.com/product/top-store-pro' ],
    [ 'name' => 'Big Store',  'img' => LFB_PLUGIN_URL . 'images/2.png',          'url' => 'https://themehunk.com/product/big-store' ],
];
?>

<div class="lfb-pro-page">

    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wplf-plugin-menu' ) ); ?>" class="lfb-back-btn">
        <?php echo lfb_svg( 'chevron-left', 16 ); ?>
        <?php esc_html_e( 'Back to Forms', 'lead-form-builder' ); ?>
    </a>

    <!-- ── Hero ─────────────────────────────────────────────────────── -->
    <div class="lfb-pro-hero">
        <div class="lfb-pro-hero-inner">
            <div class="lfb-pro-hero-badge">
                <?php echo lfb_svg( 'star', 14 ); ?>
                <?php esc_html_e( 'Lead Form Builder Pro', 'lead-form-builder' ); ?>
            </div>
            <h1 class="lfb-pro-hero-title">
                <?php esc_html_e( 'Unlock the Full Power of', 'lead-form-builder' ); ?><br>
                <?php esc_html_e( 'Lead Form Builder', 'lead-form-builder' ); ?>
            </h1>
            <p class="lfb-pro-hero-sub">
                <?php esc_html_e( 'Supercharge your lead generation with advanced features. Join over 5,000+ users growing their business with Pro.', 'lead-form-builder' ); ?>
            </p>
            <div class="lfb-pro-hero-actions">
                <a class="lfb-pro-cta-btn" target="_blank" href="<?php echo esc_url( $pro_url ); ?>">
                    <?php echo lfb_svg( 'arrow-right', 16 ); ?>
                    <?php esc_html_e( 'Upgrade to Pro', 'lead-form-builder' ); ?>
                </a>
                <a class="lfb-pro-cta-btn lfb-pro-cta-btn--outline" target="_blank" href="<?php echo esc_url( $doc_url ); ?>">
                    <?php echo lfb_svg( 'file', 16 ); ?>
                    <?php esc_html_e( 'View Documentation', 'lead-form-builder' ); ?>
                </a>
            </div>
            <div class="lfb-pro-hero-stars">
                <?php for ( $i = 0; $i < 5; $i++ ) : ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#f7a205" stroke="#f7a205" stroke-width="1"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <?php endfor; ?>
                <span><?php esc_html_e( 'Rated 5/5 by our users', 'lead-form-builder' ); ?></span>
            </div>
        </div>
        <div class="lfb-pro-hero-img">
            <img src="<?php echo esc_url( LFB_PLUGIN_URL . 'images/Contact-from-leadfomr-builder.png' ); ?>" alt="<?php esc_attr_e( 'Lead Form Builder Pro', 'lead-form-builder' ); ?>">
        </div>
    </div>

    <!-- ── Features ─────────────────────────────────────────────────── -->
    <div class="lfb-pro-section">
        <div class="lfb-pro-section-head">
            <span class="lfb-pro-section-tag"><?php esc_html_e( 'Pro Features', 'lead-form-builder' ); ?></span>
            <h2><?php esc_html_e( 'Everything You Need to Generate More Leads', 'lead-form-builder' ); ?></h2>
            <p><?php esc_html_e( 'All the powerful tools you need to build, customize, and manage high-converting lead forms.', 'lead-form-builder' ); ?></p>
        </div>
        <div class="lfb-pro-features-grid">
            <?php foreach ( $features as $f ) : ?>
            <div class="lfb-pro-feature-card">
                <div class="lfb-pro-feature-icon">
                    <?php echo lfb_svg( $f['icon'], 22 ); ?>
                </div>
                <div class="lfb-pro-feature-body">
                    <h4><?php echo esc_html( $f['title'] ); ?></h4>
                    <p><?php echo esc_html( $f['desc'] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="lfb-pro-section-cta">
            <a class="lfb-pro-cta-btn" target="_blank" href="<?php echo esc_url( $pro_url ); ?>">
                <?php echo lfb_svg( 'arrow-right', 16 ); ?>
                <?php esc_html_e( 'Get Pro Now', 'lead-form-builder' ); ?>
            </a>
        </div>
    </div>

    <!-- ── About + Support ──────────────────────────────────────────── -->
    <div class="lfb-pro-section lfb-pro-section--alt">
        <div class="lfb-pro-about-grid">
            <div class="lfb-pro-about-card">
                <div class="lfb-pro-about-icon"><?php echo lfb_svg( 'file', 26 ); ?></div>
                <h3><?php esc_html_e( 'About Lead Form Builder', 'lead-form-builder' ); ?></h3>
                <p><?php esc_html_e( 'Lead Form Builder is a powerful drag-and-drop form plugin and lead generator. Create unlimited responsive forms, manage leads, and grow your business. Works anywhere on your site — pages, posts and widgets.', 'lead-form-builder' ); ?></p>
                <a class="lfb-pro-link-btn" target="_blank" href="<?php echo esc_url( $pro_url ); ?>">
                    <?php echo lfb_svg( 'arrow-right', 14 ); ?>
                    <?php esc_html_e( 'Learn More', 'lead-form-builder' ); ?>
                </a>
            </div>
            <div class="lfb-pro-about-card">
                <div class="lfb-pro-about-icon"><?php echo lfb_svg( 'file', 26 ); ?></div>
                <h3><?php esc_html_e( 'Documentation', 'lead-form-builder' ); ?></h3>
                <p><?php esc_html_e( 'Need help getting started? Our detailed documentation covers every feature — from installation to advanced configuration.', 'lead-form-builder' ); ?></p>
                <a class="lfb-pro-link-btn" target="_blank" href="<?php echo esc_url( $doc_url ); ?>">
                    <?php echo lfb_svg( 'arrow-right', 14 ); ?>
                    <?php esc_html_e( 'Read Documentation', 'lead-form-builder' ); ?>
                </a>
            </div>
            <div class="lfb-pro-about-card">
                <div class="lfb-pro-about-icon"><?php echo lfb_svg( 'user', 26 ); ?></div>
                <h3><?php esc_html_e( 'Support Forum', 'lead-form-builder' ); ?></h3>
                <p><?php esc_html_e( 'Got a question or ran into an issue? Our friendly support team is ready to help you via our dedicated support forum.', 'lead-form-builder' ); ?></p>
                <a class="lfb-pro-link-btn" target="_blank" href="<?php echo esc_url( $sup_url ); ?>">
                    <?php echo lfb_svg( 'arrow-right', 14 ); ?>
                    <?php esc_html_e( 'Get Support', 'lead-form-builder' ); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- ── Recommended Themes ────────────────────────────────────────── -->
    <div class="lfb-pro-section">
        <div class="lfb-pro-section-head">
            <span class="lfb-pro-section-tag"><?php esc_html_e( 'Free Themes', 'lead-form-builder' ); ?></span>
            <h2><?php esc_html_e( 'Recommended Themes by ThemeHunk', 'lead-form-builder' ); ?></h2>
            <p><?php esc_html_e( 'Pair Lead Form Builder with these beautiful free WordPress themes.', 'lead-form-builder' ); ?></p>
        </div>
        <div class="lfb-pro-themes-grid">
            <?php foreach ( $themes as $theme ) : ?>
            <div class="lfb-pro-theme-card">
                <div class="lfb-pro-theme-badge"><?php esc_html_e( 'FREE', 'lead-form-builder' ); ?></div>
                <div class="lfb-pro-theme-img">
                    <img src="<?php echo esc_url( $theme['img'] ); ?>" alt="<?php echo esc_attr( $theme['name'] ); ?>">
                </div>
                <div class="lfb-pro-theme-footer">
                    <span class="lfb-pro-theme-name"><?php echo esc_html( $theme['name'] ); ?></span>
                    <a class="lfb-pro-theme-btn" target="_blank" href="<?php echo esc_url( $theme['url'] ); ?>">
                        <?php echo lfb_svg( 'eye', 14 ); ?>
                        <?php esc_html_e( 'Live Demo', 'lead-form-builder' ); ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div><!-- .lfb-pro-page -->
</div><!-- .wrap -->