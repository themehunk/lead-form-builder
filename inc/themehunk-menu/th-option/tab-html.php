<?php
$getUrlTab = isset($_GET['th-tab']) && $_GET['th-tab'] ? sanitize_key($_GET['th-tab']) : false;
$tabActiveRp =  $getUrlTab == 'recommended-plugin' ? 'active' : '';
if (!$tabActiveRp) {
    $tabActiveWl = 'active';
}
?>

<div class="th-market-container">
        <div class="th-market-wrapper">
<div class="wrap-th about-wrap-th theme_info_wrapper">
<?php include_once THEMEHUNK_PDIR . "th-option/header.php"; ?>
    </div>

<div class="content-wrap">
    <div class="main">
<div class="tab-left" >
        <div class="tab-heading-wrap"><h3>Available Plugins</h3>
        <div>
            <select>
            <option>All Plugins</option>
            <option>Installed</option>
            <option>Not Installed</option>
        </select>
    </div>
    </div>
        <div id="Recommended-Plugin" class="tabcontent active <?php echo esc_attr($tabActiveRp) ?>">
            <div class="rp-two-column">
            <?php $this->plugin_install(); ?>
            </div>
        </div>
</div> <!-- tab div close -->



<div class="sidebar-wrap">
    <div class="sidebar">
    <?php include('sidebar.php' ); ?>
    </div>
</div>


</div>
</div>

    </div>
 <div>