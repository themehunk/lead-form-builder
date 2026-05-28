<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function lfb_custom_style( $fid, $lfbdb ) {
    $colordata = $lfbdb->lfb_get_colors_data( $fid );
    if ( isset( $colordata[0]->colorData ) && ! empty( $colordata[0]->colorData ) ) :
        $colors = maybe_unserialize( $colordata[0]->colorData );
        if ( ! is_array( $colors ) ) return '';
        extract( $colors );

        $lfb_color_header_overlay = isset( $lfb_color_header_overlay ) ? $lfb_color_header_overlay : 'rgba(0,0,0,0)';
        $lfb_header_backdrop_blur = isset( $lfb_header_backdrop_blur ) ? intval( $lfb_header_backdrop_blur ) : 0;
        $lfb_bg_backdrop_blur     = isset( $lfb_bg_backdrop_blur )     ? intval( $lfb_bg_backdrop_blur )     : 0;
        $lfb_form_border_width    = isset( $lfb_form_border_width )    ? intval( $lfb_form_border_width )    : 0;
        $lfb_form_border_style    = isset( $lfb_form_border_style )    ? $lfb_form_border_style              : 'none';
        $lfb_form_border_color    = isset( $lfb_form_border_color )    ? $lfb_form_border_color              : '#cccccc';
        $lfb_form_border_radius   = isset( $lfb_form_border_radius )   ? intval( $lfb_form_border_radius )   : 0;
        $lfb_form_box_shadow      = isset( $lfb_form_box_shadow )      ? $lfb_form_box_shadow                : 'none';
        $border_css = ( $lfb_form_border_style === 'none' || $lfb_form_border_width === 0 )
            ? 'border:none;'
            : "border:{$lfb_form_border_width}px {$lfb_form_border_style} {$lfb_form_border_color};";
        $form_overflow         = ( $lfb_form_border_radius > 0 ) ? 'hidden' : 'visible';
        $lfb_field_border_width  = isset( $lfb_field_border_width )  ? intval( $lfb_field_border_width )  : 1;
        $lfb_field_border_style  = isset( $lfb_field_border_style )  ? $lfb_field_border_style            : 'solid';
        $lfb_field_border_radius = isset( $lfb_field_border_radius ) ? intval( $lfb_field_border_radius ) : 0;
        $lfb_field_columns       = isset( $lfb_field_columns )       ? $lfb_field_columns                 : '1';
        $lfb_req_star_color      = isset( $lfb_req_star_color )      ? $lfb_req_star_color                : '#e53e3e';
        $lfb_req_star_size       = isset( $lfb_req_star_size )       ? intval( $lfb_req_star_size )       : 14;
        $lfb_icon_bg             = isset( $lfb_icon_bg )             ? $lfb_icon_bg                       : '#7b61ff';
        $lfb_choice_checked_color = isset( $lfb_choice_checked_color ) ? $lfb_choice_checked_color        : '#7b61ff';
        $lfb_btn_border_width    = isset( $lfb_btn_border_width )    ? intval( $lfb_btn_border_width )    : 1;
        $lfb_btn_border_style    = isset( $lfb_btn_border_style )    ? $lfb_btn_border_style              : 'solid';
        $lfb_btn_border_radius   = isset( $lfb_btn_border_radius )   ? intval( $lfb_btn_border_radius )   : 0;
        $lfb_button_font_size    = isset( $lfb_button_font_size )    ? intval( $lfb_button_font_size )    : 14;
        $lfb_button_aligment     = isset( $lfb_button_aligment )     ? $lfb_button_aligment               : 'left';
        $lfb_btn_padding_tb      = isset( $lfb_btn_padding_tb )      ? intval( $lfb_btn_padding_tb )      : 0;
        $lfb_btn_padding_lr      = isset( $lfb_btn_padding_lr )      ? intval( $lfb_btn_padding_lr )      : 0;
        $btn_width_css           = ( $lfb_btn_padding_lr === 0 ) ? 'width:auto;' : "width:{$lfb_btn_padding_lr}%;";
        $btn_border_css = ( $lfb_btn_border_style === 'none' || $lfb_btn_border_width === 0 )
            ? 'border:none;'
            : "border:{$lfb_btn_border_width}px {$lfb_btn_border_style} {$lfb_color_button_border};";
        $header_display   = ( $lfb_header_image == '' ) ? 'display: none;' : '';
        $heading_on_image = ( isset( $lfb_heading_position ) && $lfb_heading_position === 'overlay' );

        $return = "<style>
.leadform-show-form-{$fid}.leadform-show-form{
    display:block;
    margin:auto;
    max-width:{$lfb_form_width}%;
    {$border_css}
    border-radius:{$lfb_form_border_radius}px;
    box-shadow:{$lfb_form_box_shadow};
    overflow:{$form_overflow};
}
.leadform-show-form-{$fid} .lead-head{
    padding-top:{$lfb_header_algmnt_tb}%;
    padding-bottom:{$lfb_header_algmnt_lr}%;
}
.leadform-show-form-{$fid} .lead-form-front{
    background-image:url('{$lfb_bg_image}');
    padding:{$lfb_form_padding_top}% {$lfb_form_padding_right}% {$lfb_form_padding_bottom}% {$lfb_form_padding_left}%;
}
.leadform-show-form-{$fid} .lead-form-front:before{
    background-color:{$lfb_color_bg};
    backdrop-filter:blur({$lfb_bg_backdrop_blur}px);
    -webkit-backdrop-filter:blur({$lfb_bg_backdrop_blur}px);
}
.leadform-show-form-{$fid} .lead-head{
    background-image:url('{$lfb_header_image}');
    background-size:auto;
    background-position:center;
    background-repeat:no-repeat;
    position:relative;
    {$header_display}
}
.leadform-show-form-{$fid} .lead-head:before{
    content:'';
    position:absolute;
    top:0;left:0;width:100%;height:100%;
    background-color:{$lfb_color_header_overlay};
    backdrop-filter:blur({$lfb_header_backdrop_blur}px);
    -webkit-backdrop-filter:blur({$lfb_header_backdrop_blur}px);
    z-index:0;display:block;pointer-events:none;
}
.leadform-show-form-{$fid} .lead-form-front h2{
    color:{$lfb_color_heading};
    font-size:{$lfb_heading_font_size}px;
    display:{$lfb_heading_hide};
    text-align:{$lfb_heading_alignment};
}
.leadform-show-form-{$fid} label{ color:{$lfb_color_label}; }
.leadform-show-form-{$fid} .lfb-req-star{ color:{$lfb_req_star_color}; font-size:{$lfb_req_star_size}px; }
.leadform-show-form-{$fid} .lfb-date-icon{ background:{$lfb_icon_bg} !important; }
.leadform-show-form-{$fid} .lfb_input_upload{
    background:url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2718%27 height=%2718%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27white%27 stroke-width=%272.5%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3E%3Cpath d=%27M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4%27/%3E%3Cpolyline points=%2717 8 12 3 7 8%27/%3E%3Cline x1=%2712%27 y1=%273%27 x2=%2712%27 y2=%2715%27/%3E%3C/svg%3E') center / 18px 18px no-repeat, {$lfb_icon_bg} !important;
    border-radius:0 8px 8px 0 !important;
}
.leadform-show-form-{$fid} input[type=radio]:checked{
    border-color:{$lfb_choice_checked_color} !important;
    box-shadow:inset 0 0 0 4px {$lfb_choice_checked_color} !important;
}
.leadform-show-form-{$fid} input[type=checkbox]:checked{
    background:{$lfb_choice_checked_color} !important;
    border-color:{$lfb_choice_checked_color} !important;
}
.leadform-show-form-{$fid} span ul li{ color:{$lfb_color_label}; }
.leadform-show-form-{$fid} ::-webkit-input-placeholder{ color:{$lfb_color_field_placeholder}; }
.leadform-show-form-{$fid} :-moz-placeholder{ color:{$lfb_color_field_placeholder}; }
.leadform-show-form-{$fid} :-ms-input-placeholder{ color:{$lfb_color_field_placeholder}; }
.leadform-show-form-{$fid} textarea,
.leadform-show-form-{$fid} input:not([type]),
.leadform-show-form-{$fid} input[type='email'],
.leadform-show-form-{$fid} input[type='number'],
.leadform-show-form-{$fid} input[type='password'],
.leadform-show-form-{$fid} input[type='tel'],
.leadform-show-form-{$fid} input[type='url'],
.leadform-show-form-{$fid} input[type='text'],
.leadform-show-form-{$fid} select{
    background-color:{$lfb_color_field_bg};
    border-color:{$lfb_color_field_border};
    border-width:{$lfb_field_border_width}px;
    border-style:{$lfb_field_border_style};
    border-radius:{$lfb_field_border_radius}px;
    color:{$lfb_color_field_placeholder};
}
.leadform-show-form-{$fid} input[type='submit']{
    color:{$lfb_color_button_text};
    background:{$lfb_color_button_bg};
    {$btn_border_css}
    border-radius:{$lfb_btn_border_radius}px;
    font-size:{$lfb_button_font_size}px;
    padding-top:{$lfb_btn_padding_tb}%;
    padding-bottom:{$lfb_btn_padding_tb}%;
    {$btn_width_css}
    display:inline-block;
}
.leadform-show-form-{$fid} .submit-type{ text-align:{$lfb_button_aligment}; }
.leadform-show-form-{$fid} input[type='submit']:hover{
    background:{$lfb_color_button_bg_hover};
    border-color:{$lfb_color_button_bg_hover};
}
{$lfb_custom_css}
</style>";

        $return .= "<style>
@media only screen and (max-width:767px){
    .leadform-show-form-{$fid} input[type='submit']{
        max-width:100%;
        box-sizing:border-box;
    }
}
@media screen and (max-width:550px){
    .leadform-show-form-{$fid} input[type='submit']{
        width:100%;
        max-width:100%;
        box-sizing:border-box;
        white-space:normal;
    }
}
</style>";

        if ( $lfb_field_columns === '2' ) {
            $return .= "<style>
.leadform-show-form-{$fid} .lead-form-front{
    display:grid;
    grid-template-columns:1fr 1fr;
    column-gap:16px;
    align-items:start;
}
.leadform-show-form-{$fid} .lead-form-front .textarea-type,
.leadform-show-form-{$fid} .lead-form-front .message-type,
.leadform-show-form-{$fid} .lead-form-front .lf-form-panel,
.leadform-show-form-{$fid} .lead-form-front .captcha-field-area,
.leadform-show-form-{$fid} .lead-form-front .lf-loading,
.leadform-show-form-{$fid} .lead-form-front h2{ grid-column:1 / -1; }
@media (max-width:600px){
    .leadform-show-form-{$fid} .lead-form-front{ grid-template-columns:1fr; }
}
</style>";
        }

        if ( $heading_on_image ) {
            $flex_justify = ( $lfb_heading_alignment === 'center' ) ? 'center' : ( ( $lfb_heading_alignment === 'right' ) ? 'flex-end' : 'flex-start' );
            $return .= "<style>
.leadform-show-form-{$fid} .lead-head{
    display:flex;
    align-items:center;
    justify-content:{$flex_justify};
    min-height:80px;
}
.leadform-show-form-{$fid} .lead-head h2{
    color:{$lfb_color_heading};
    font-size:{$lfb_heading_font_size}px;
    text-align:{$lfb_heading_alignment};
    display:{$lfb_heading_hide};
    margin:0;
    padding:8px 16px;
    position:relative;
    z-index:2;
}
</style>";
        }

        return $return;
    endif;

    return '';
}
