import './style.scss';
import { useState, useEffect, useRef, useCallback } from '@wordpress/element';
import {
	PanelBody,
	RangeControl,
	SelectControl,
	ToggleControl,
	Button,
	ColorPicker,
	TextareaControl,
} from '@wordpress/components';

const DEFAULT_SETTINGS = {
	lfb_form_width: 100,
	lfb_form_border_width: 0,
	lfb_form_border_style: 'none',
	lfb_form_border_color: '#cccccc',
	lfb_form_border_radius: 0,
	lfb_form_box_shadow: 'none',
	lfb_header_image: '',
	lfb_color_heading: '#111111',
	lfb_heading_alignment: 'left',
	lfb_heading_hide: 'block',
	lfb_heading_font_size: 26,
	lfb_header_algmnt_tb: 0,
	lfb_header_algmnt_lr: 0,
	lfb_bg_image: '',
	lfb_color_bg: 'rgba(255,255,255,0)',
	lfb_form_padding_top: 2,
	lfb_form_padding_bottom: 2,
	lfb_form_padding_left: 2,
	lfb_form_padding_right: 2,
	lfb_color_label: '#111111',
	lfb_color_field_border: 'rgba(33,15,15,0.67)',
	lfb_field_border_width: 1,
	lfb_field_border_style: 'solid',
	lfb_field_border_radius: 0,
	lfb_color_field_bg: '#ffffff',
	lfb_color_field_placeholder: 'rgba(96,96,96,0.89)',
	lfb_color_button_text: '#ffffff',
	lfb_color_button_bg: 'rgba(96,96,96,0.89)',
	lfb_color_button_bg_hover: 'rgba(86,86,86,0.87)',
	lfb_color_button_border: 'rgba(96,96,96,0.89)',
	lfb_btn_border_width: 1,
	lfb_btn_border_style: 'solid',
	lfb_btn_border_radius: 0,
	lfb_button_aligment: 'left',
	lfb_button_font_size: 16,
	lfb_btn_padding_tb: 2,
	lfb_btn_padding_lr: 35,
	lfb_custom_css: '',
	lfb_heading_position: 'default',
	lfb_color_header_overlay: 'rgba(0,0,0,0)',
	lfb_header_backdrop_blur: 0,
	lfb_bg_backdrop_blur: 0,
	lfb_field_columns: '1',
	lfb_req_star_color: '#e53e3e',
	lfb_req_star_size: 14,
	lfb_icon_bg: '#7b61ff',
	lfb_choice_checked_color: '#7b61ff',
};

function parseSettings( saved ) {
	const out = { ...DEFAULT_SETTINGS };
	if ( saved && typeof saved === 'object' ) {
		Object.keys( DEFAULT_SETTINGS ).forEach( ( key ) => {
			if ( saved[ key ] !== undefined && saved[ key ] !== null ) {
				out[ key ] =
					typeof DEFAULT_SETTINGS[ key ] === 'number'
						? ( ( v ) => {
								const n = parseInt( v, 10 );
								return isNaN( n ) ? DEFAULT_SETTINGS[ key ] : n;
						  } )( saved[ key ] )
						: String( saved[ key ] );
			}
		} );
	}
	return out;
}

function ColorPickerControl( { label, value, onChange } ) {
	const [ open, setOpen ] = useState( false );
	const ref = useRef( null );

	useEffect( () => {
		if ( ! open ) return;
		function handleClick( e ) {
			if ( ref.current && ! ref.current.contains( e.target ) ) {
				setOpen( false );
			}
		}
		document.addEventListener( 'mousedown', handleClick );
		return () => document.removeEventListener( 'mousedown', handleClick );
	}, [ open ] );

	return (
		<div className="lfb-gp-color-row" ref={ ref }>
			<span className="lfb-gp-color-label">{ label }</span>
			<button
				type="button"
				className="lfb-gp-swatch-btn"
				onClick={ () => setOpen( ( v ) => ! v ) }
				aria-label={ `Pick color for ${ label }` }
			>
				<span className="lfb-gp-swatch" style={ { background: value } } />
				<span className="lfb-gp-swatch-hex">{ value }</span>
			</button>
			{ open && (
				<div className="lfb-gp-picker-dropdown">
					<ColorPicker
						color={ value }
						onChange={ onChange }
						enableAlpha
						copyFormat="hex"
					/>
					<Button
						variant="secondary"
						size="small"
						className="lfb-gp-picker-done"
						onClick={ () => setOpen( false ) }
					>
						Done
					</Button>
				</div>
			) }
		</div>
	);
}

function AlignControl( { value, onChange } ) {
	return (
		<div className="lfb-gp-align-row">
			{ [ { v: 'left', label: 'Left' }, { v: 'center', label: 'Center' }, { v: 'right', label: 'Right' } ].map(
				( { v, label } ) => (
					<button
						key={ v }
						type="button"
						title={ label }
						className={ 'lfb-gp-align-btn' + ( value === v ? ' is-active' : '' ) }
						onClick={ () => onChange( v ) }
					>
						{ label }
					</button>
				)
			) }
		</div>
	);
}

function ImageControl( { label, value, onChange } ) {
	function openMediaLibrary() {
		if ( ! window.wp || ! window.wp.media ) return;
		const frame = window.wp.media( {
			title: label,
			button: { text: 'Use this image' },
			multiple: false,
		} );
		frame.on( 'select', () => {
			const attachment = frame.state().get( 'selection' ).first().toJSON();
			onChange( attachment.url );
		} );
		frame.open();
	}

	return (
		<div className="lfb-gp-image-row">
			<span className="lfb-gp-color-label">{ label }</span>
			<div className="lfb-gp-image-preview">
				{ value && <img src={ value } alt="" className="lfb-gp-thumb" /> }
			</div>
			<div className="lfb-gp-image-btns">
				<Button variant="secondary" size="small" onClick={ openMediaLibrary }>
					{ value ? 'Change' : 'Upload' }
				</Button>
				{ value && (
					<Button
						variant="link"
						isDestructive
						size="small"
						onClick={ () => onChange( '' ) }
					>
						Remove
					</Button>
				) }
			</div>
		</div>
	);
}

function buildPreviewCss( s ) {
	const headerDisplay = s.lfb_header_image ? 'block' : 'none';
	const twoCol = s.lfb_field_columns === '2';
	const overlayHeading = s.lfb_heading_position === 'overlay';

	return `
.leadform-show-form {
    max-width: ${ s.lfb_form_width }% !important;
    ${ s.lfb_form_border_style !== 'none' && s.lfb_form_border_width > 0
		? `border: ${ s.lfb_form_border_width }px ${ s.lfb_form_border_style } ${ s.lfb_form_border_color } !important;`
		: 'border: none !important;' }
    border-radius: ${ s.lfb_form_border_radius }px !important;
    box-shadow: ${ s.lfb_form_box_shadow } !important;
    overflow: ${ s.lfb_form_border_radius > 0 ? 'hidden' : 'visible' } !important;
}
.leadform-show-form .lead-form-front h2 {
    color: ${ s.lfb_color_heading } !important;
    font-size: ${ s.lfb_heading_font_size }px !important;
    text-align: ${ s.lfb_heading_alignment } !important;
    display: ${ s.lfb_heading_hide } !important;
}
.leadform-show-form .lead-head {
    background-image: url('${ s.lfb_header_image }') !important;
    background-size: auto !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    position: relative !important;
    display: ${ headerDisplay } !important;
    padding-top: ${ s.lfb_header_algmnt_tb }% !important;
    padding-bottom: ${ s.lfb_header_algmnt_lr }% !important;
    min-height: ${ s.lfb_header_image ? '60px' : '0' } !important;
}
.leadform-show-form .lead-head:before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important; left: 0 !important;
    width: 100% !important; height: 100% !important;
    background-color: ${ s.lfb_color_header_overlay } !important;
    backdrop-filter: blur(${ s.lfb_header_backdrop_blur }px) !important;
    -webkit-backdrop-filter: blur(${ s.lfb_header_backdrop_blur }px) !important;
    z-index: 0 !important;
    display: block !important;
    pointer-events: none !important;
}
.leadform-show-form .lead-form-front {
    background-image: url('${ s.lfb_bg_image }') !important;
    background-size: cover !important;
    background-position: center !important;
    padding: ${ s.lfb_form_padding_top }% ${ s.lfb_form_padding_right }% ${ s.lfb_form_padding_bottom }% ${ s.lfb_form_padding_left }% !important;
}
.leadform-show-form .lead-form-front:before {
    background-color: ${ s.lfb_color_bg } !important;
    backdrop-filter: blur(${ s.lfb_bg_backdrop_blur }px) !important;
    -webkit-backdrop-filter: blur(${ s.lfb_bg_backdrop_blur }px) !important;
}
.leadform-show-form label { color: ${ s.lfb_color_label } !important; }
.leadform-show-form span ul li { color: ${ s.lfb_color_label } !important; }
.leadform-show-form ::-webkit-input-placeholder { color: ${ s.lfb_color_field_placeholder } !important; }
.leadform-show-form :-moz-placeholder { color: ${ s.lfb_color_field_placeholder } !important; }
.leadform-show-form ::placeholder { color: ${ s.lfb_color_field_placeholder } !important; }
.leadform-show-form .lfb-req-star { color: ${ s.lfb_req_star_color } !important; font-size: ${ s.lfb_req_star_size }px !important; }
.leadform-show-form .lfb-date-icon { background: ${ s.lfb_icon_bg } !important; }
.leadform-show-form .lfb_input_upload { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4'/%3E%3Cpolyline points='17 8 12 3 7 8'/%3E%3Cline x1='12' y1='3' x2='12' y2='15'/%3E%3C/svg%3E") center / 18px 18px no-repeat, ${ s.lfb_icon_bg } !important; }
.leadform-show-form input[type="radio"]:checked { border-color: ${ s.lfb_choice_checked_color } !important; box-shadow: inset 0 0 0 4px ${ s.lfb_choice_checked_color } !important; }
.leadform-show-form input[type="checkbox"]:checked { background: ${ s.lfb_choice_checked_color } !important; border-color: ${ s.lfb_choice_checked_color } !important; }
.leadform-show-form textarea,
.leadform-show-form input:not([type]),
.leadform-show-form input[type="email"],
.leadform-show-form input[type="number"],
.leadform-show-form input[type="password"],
.leadform-show-form input[type="tel"],
.leadform-show-form input[type="url"],
.leadform-show-form input[type="text"],
.leadform-show-form select {
    background-color: ${ s.lfb_color_field_bg } !important;
    border-color: ${ s.lfb_color_field_border } !important;
    border-width: ${ s.lfb_field_border_width }px !important;
    border-style: ${ s.lfb_field_border_style } !important;
    border-radius: ${ s.lfb_field_border_radius }px !important;
    color: ${ s.lfb_color_field_placeholder } !important;
}
.leadform-show-form input[type="submit"] {
    color: ${ s.lfb_color_button_text } !important;
    background: ${ s.lfb_color_button_bg } !important;
    border: ${ s.lfb_btn_border_style === 'none' || s.lfb_btn_border_width === 0
		? 'none'
		: `${ s.lfb_btn_border_width }px ${ s.lfb_btn_border_style } ${ s.lfb_color_button_border }` } !important;
    border-radius: ${ s.lfb_btn_border_radius }px !important;
    font-size: ${ s.lfb_button_font_size }px !important;
    padding-top: ${ s.lfb_btn_padding_tb }% !important;
    padding-bottom: ${ s.lfb_btn_padding_tb }% !important;
    width: ${ s.lfb_btn_padding_lr }% !important;
}
.leadform-show-form input[type="submit"]:hover {
    background: ${ s.lfb_color_button_bg_hover } !important;
    border: 1px solid ${ s.lfb_color_button_bg_hover } !important;
}
.leadform-show-form .submit-type { text-align: ${ s.lfb_button_aligment } !important; }
${ twoCol ? `
.leadform-show-form .lead-form-front {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    column-gap: 16px !important;
    align-items: start !important;
}
.leadform-show-form .lead-form-front .textarea-type,
.leadform-show-form .lead-form-front .message-type,
.leadform-show-form .lead-form-front .lf-form-panel,
.leadform-show-form .lead-form-front .captcha-field-area,
.leadform-show-form .lead-form-front .lf-loading,
.leadform-show-form .lead-form-front h2 {
    grid-column: 1 / -1 !important;
}
@media (max-width: 600px) {
    .leadform-show-form .lead-form-front {
        grid-template-columns: 1fr !important;
    }
}` : `
.leadform-show-form .lead-form-front {
    display: block !important;
    grid-template-columns: unset !important;
}` }
${ overlayHeading ? `
.leadform-show-form .lead-head {
    display: flex !important;
    align-items: center !important;
    justify-content: ${ s.lfb_heading_alignment === 'right' ? 'flex-end' : s.lfb_heading_alignment === 'center' ? 'center' : 'flex-start' } !important;
    min-height: 80px !important;
}
.leadform-show-form .lead-head h2 {
    color: ${ s.lfb_color_heading } !important;
    font-size: ${ s.lfb_heading_font_size }px !important;
    text-align: ${ s.lfb_heading_alignment } !important;
    display: ${ s.lfb_heading_hide } !important;
    margin: 0 !important;
    padding: 8px 16px !important;
    position: relative !important;
    z-index: 2 !important;
}` : '' }
${ s.lfb_custom_css || '' }
	`.trim();
}

function DesignPanel() {
	const initialSettings =
		typeof lfbDesignPanel !== 'undefined' ? lfbDesignPanel.settings : {};

	const [ settings, setSettings ] = useState( () => parseSettings( initialSettings ) );
	const [ saveState, setSaveState ] = useState( 'idle' );

	const updateSetting = useCallback( ( key, value ) => {
		setSettings( ( prev ) => ( { ...prev, [ key ]: value } ) );
	}, [] );

	// Live preview CSS injection
	useEffect( () => {
		let tag = document.getElementById( 'lfb-gp-preview-style' );
		if ( ! tag ) {
			tag = document.createElement( 'style' );
			tag.id = 'lfb-gp-preview-style';
			document.body.appendChild( tag );
		}
		tag.textContent = buildPreviewCss( settings );

		// Move heading element between header and form depending on overlay mode
		const headEl = document.querySelector( '.lead-head' );
		const formEl = document.querySelector( '.lead-form-front' );
		const h2El = document.querySelector( 'h2.lfb-heading' );
		if ( headEl && formEl && h2El ) {
			if ( settings.lfb_heading_position === 'overlay' ) {
				if ( h2El.parentElement !== headEl ) headEl.appendChild( h2El );
			} else {
				if ( h2El.parentElement === headEl ) formEl.insertBefore( h2El, formEl.firstChild );
			}
		}
	}, [ settings ] );

	const handleSave = useCallback( async () => {
		if ( typeof lfbDesignPanel === 'undefined' ) return;
		setSaveState( 'saving' );
		try {
			const params = new URLSearchParams( {
				...Object.fromEntries(
					Object.entries( settings ).map( ( [ k, v ] ) => [ k, String( v ) ] )
				),
				colorid: String( lfbDesignPanel.formId ),
				action: 'SaveColorsSettings',
				security: lfbDesignPanel.nonce,
			} );
			const res = await fetch( lfbDesignPanel.ajaxUrl, { method: 'POST', body: params } );
			const text = await res.text();
			setSaveState( text.trim() === '1' ? 'saved' : 'error' );
		} catch {
			setSaveState( 'error' );
		}
		setTimeout( () => setSaveState( 'idle' ), 2500 );
	}, [ settings ] );

	const saveLabel = { idle: 'Save Changes', saving: 'Saving…', saved: '✓ Saved', error: '✗ Error — try again' }[ saveState ];

	return (
		<div className="lfb-gp-wrap">

			{ /* Form Settings */ }
			<PanelBody title="Form Settings" initialOpen={ false }>
				<RangeControl label="Form Width (%)" value={ settings.lfb_form_width } onChange={ ( v ) => updateSetting( 'lfb_form_width', v ) } min={ 10 } max={ 100 } />
				<RangeControl label="Border Width (px)" value={ settings.lfb_form_border_width } onChange={ ( v ) => updateSetting( 'lfb_form_border_width', v ) } min={ 0 } max={ 20 } />
				<SelectControl
					label="Border Style"
					value={ settings.lfb_form_border_style }
					options={ [
						{ label: 'None', value: 'none' },
						{ label: 'Solid', value: 'solid' },
						{ label: 'Dashed', value: 'dashed' },
						{ label: 'Dotted', value: 'dotted' },
						{ label: 'Double', value: 'double' },
					] }
					onChange={ ( v ) => updateSetting( 'lfb_form_border_style', v ) }
				/>
				<ColorPickerControl label="Border Color" value={ settings.lfb_form_border_color } onChange={ ( v ) => updateSetting( 'lfb_form_border_color', v ) } />
				<RangeControl label="Border Radius (px)" value={ settings.lfb_form_border_radius } onChange={ ( v ) => updateSetting( 'lfb_form_border_radius', v ) } min={ 0 } max={ 100 } />
				<SelectControl
					label="Box Shadow"
					value={ settings.lfb_form_box_shadow }
					options={ [
						{ label: 'None', value: 'none' },
						{ label: 'Light', value: '0 2px 8px rgba(0,0,0,0.10)' },
						{ label: 'Medium', value: '0 4px 16px rgba(0,0,0,0.15)' },
						{ label: 'Strong', value: '0 8px 30px rgba(0,0,0,0.25)' },
					] }
					onChange={ ( v ) => updateSetting( 'lfb_form_box_shadow', v ) }
				/>
			</PanelBody>

			{ /* Header Settings */ }
			<PanelBody title="Header Settings" initialOpen={ false }>
				<ImageControl label="Header Image" value={ settings.lfb_header_image } onChange={ ( v ) => updateSetting( 'lfb_header_image', v ) } />
				<ColorPickerControl label="Header Overlay Color" value={ settings.lfb_color_header_overlay } onChange={ ( v ) => updateSetting( 'lfb_color_header_overlay', v ) } />
				<RangeControl label="Header Backdrop Blur (px)" value={ settings.lfb_header_backdrop_blur } onChange={ ( v ) => updateSetting( 'lfb_header_backdrop_blur', v ) } min={ 0 } max={ 30 } />
				<ColorPickerControl label="Heading Color" value={ settings.lfb_color_heading } onChange={ ( v ) => updateSetting( 'lfb_color_heading', v ) } />
				<div className="lfb-gp-field">
					<label className="lfb-gp-label">Alignment</label>
					<AlignControl value={ settings.lfb_heading_alignment } onChange={ ( v ) => updateSetting( 'lfb_heading_alignment', v ) } />
				</div>
				<ToggleControl label="Show Heading" checked={ settings.lfb_heading_hide === 'block' } onChange={ ( v ) => updateSetting( 'lfb_heading_hide', v ? 'block' : 'none' ) } />
				<ToggleControl
					label="Show Heading on Header Image"
					help="Places the heading text overlaid on the header image"
					checked={ settings.lfb_heading_position === 'overlay' }
					onChange={ ( v ) => updateSetting( 'lfb_heading_position', v ? 'overlay' : 'default' ) }
				/>
				<RangeControl label="Font Size (px)" value={ settings.lfb_heading_font_size } onChange={ ( v ) => updateSetting( 'lfb_heading_font_size', v ) } min={ 8 } max={ 80 } />
				<RangeControl label="Top Padding (%)" value={ settings.lfb_header_algmnt_tb } onChange={ ( v ) => updateSetting( 'lfb_header_algmnt_tb', v ) } min={ 0 } max={ 50 } />
				<RangeControl label="Bottom Padding (%)" value={ settings.lfb_header_algmnt_lr } onChange={ ( v ) => updateSetting( 'lfb_header_algmnt_lr', v ) } min={ 0 } max={ 50 } />
			</PanelBody>

			{ /* Background */ }
			<PanelBody title="Background" initialOpen={ false }>
				<ImageControl label="Background Image" value={ settings.lfb_bg_image } onChange={ ( v ) => updateSetting( 'lfb_bg_image', v ) } />
				<ColorPickerControl label="Background Color" value={ settings.lfb_color_bg } onChange={ ( v ) => updateSetting( 'lfb_color_bg', v ) } />
				<RangeControl label="Backdrop Blur (px)" value={ settings.lfb_bg_backdrop_blur } onChange={ ( v ) => updateSetting( 'lfb_bg_backdrop_blur', v ) } min={ 0 } max={ 30 } />
				<p className="lfb-gp-group-title">Form Padding</p>
				<RangeControl label="Top (%)" value={ settings.lfb_form_padding_top } onChange={ ( v ) => updateSetting( 'lfb_form_padding_top', v ) } min={ 0 } max={ 30 } />
				<RangeControl label="Bottom (%)" value={ settings.lfb_form_padding_bottom } onChange={ ( v ) => updateSetting( 'lfb_form_padding_bottom', v ) } min={ 0 } max={ 30 } />
				<RangeControl label="Left (%)" value={ settings.lfb_form_padding_left } onChange={ ( v ) => updateSetting( 'lfb_form_padding_left', v ) } min={ 0 } max={ 30 } />
				<RangeControl label="Right (%)" value={ settings.lfb_form_padding_right } onChange={ ( v ) => updateSetting( 'lfb_form_padding_right', v ) } min={ 0 } max={ 30 } />
			</PanelBody>

			{ /* Field Settings */ }
			<PanelBody title="Field Settings" initialOpen={ false }>
				<ColorPickerControl label="Label Color" value={ settings.lfb_color_label } onChange={ ( v ) => updateSetting( 'lfb_color_label', v ) } />
				<ColorPickerControl label="Border Color" value={ settings.lfb_color_field_border } onChange={ ( v ) => updateSetting( 'lfb_color_field_border', v ) } />
				<RangeControl label="Border Width (px)" value={ settings.lfb_field_border_width } onChange={ ( v ) => updateSetting( 'lfb_field_border_width', v ) } min={ 0 } max={ 10 } />
				<SelectControl
					label="Border Style"
					value={ settings.lfb_field_border_style }
					options={ [
						{ label: 'Solid', value: 'solid' },
						{ label: 'Dashed', value: 'dashed' },
						{ label: 'Dotted', value: 'dotted' },
						{ label: 'None', value: 'none' },
					] }
					onChange={ ( v ) => updateSetting( 'lfb_field_border_style', v ) }
				/>
				<RangeControl label="Border Radius (px)" value={ settings.lfb_field_border_radius } onChange={ ( v ) => updateSetting( 'lfb_field_border_radius', v ) } min={ 0 } max={ 50 } />
				<ColorPickerControl label="Field Background" value={ settings.lfb_color_field_bg } onChange={ ( v ) => updateSetting( 'lfb_color_field_bg', v ) } />
				<ColorPickerControl label="Placeholder" value={ settings.lfb_color_field_placeholder } onChange={ ( v ) => updateSetting( 'lfb_color_field_placeholder', v ) } />
				<SelectControl
					label="Column Layout"
					value={ settings.lfb_field_columns }
					options={ [
						{ label: '1 Column', value: '1' },
						{ label: '2 Columns', value: '2' },
					] }
					onChange={ ( v ) => updateSetting( 'lfb_field_columns', v ) }
				/>
				<ColorPickerControl label="Required (*) Color" value={ settings.lfb_req_star_color } onChange={ ( v ) => updateSetting( 'lfb_req_star_color', v ) } />
				<RangeControl label="Required (*) Size (px)" value={ settings.lfb_req_star_size } onChange={ ( v ) => updateSetting( 'lfb_req_star_size', v ) } min={ 8 } max={ 32 } />
				<p className="lfb-gp-group-title">Field Type Colors</p>
				<ColorPickerControl label="Icon Background (Calendar &amp; Upload)" value={ settings.lfb_icon_bg } onChange={ ( v ) => updateSetting( 'lfb_icon_bg', v ) } />
				<ColorPickerControl label="Radio &amp; Checkbox Selected Color" value={ settings.lfb_choice_checked_color } onChange={ ( v ) => updateSetting( 'lfb_choice_checked_color', v ) } />
			</PanelBody>

			{ /* Submit Button */ }
			<PanelBody title="Submit Button" initialOpen={ false }>
				<ColorPickerControl label="Text Color" value={ settings.lfb_color_button_text } onChange={ ( v ) => updateSetting( 'lfb_color_button_text', v ) } />
				<ColorPickerControl label="Background" value={ settings.lfb_color_button_bg } onChange={ ( v ) => updateSetting( 'lfb_color_button_bg', v ) } />
				<ColorPickerControl label="Hover Background" value={ settings.lfb_color_button_bg_hover } onChange={ ( v ) => updateSetting( 'lfb_color_button_bg_hover', v ) } />
				<ColorPickerControl label="Border Color" value={ settings.lfb_color_button_border } onChange={ ( v ) => updateSetting( 'lfb_color_button_border', v ) } />
				<RangeControl label="Border Width (px)" value={ settings.lfb_btn_border_width } onChange={ ( v ) => updateSetting( 'lfb_btn_border_width', v ) } min={ 0 } max={ 10 } />
				<SelectControl
					label="Border Style"
					value={ settings.lfb_btn_border_style }
					options={ [
						{ label: 'Solid', value: 'solid' },
						{ label: 'Dashed', value: 'dashed' },
						{ label: 'Dotted', value: 'dotted' },
						{ label: 'None', value: 'none' },
					] }
					onChange={ ( v ) => updateSetting( 'lfb_btn_border_style', v ) }
				/>
				<RangeControl label="Border Radius (px)" value={ settings.lfb_btn_border_radius } onChange={ ( v ) => updateSetting( 'lfb_btn_border_radius', v ) } min={ 0 } max={ 50 } />
				<div className="lfb-gp-field">
					<label className="lfb-gp-label">Alignment</label>
					<AlignControl value={ settings.lfb_button_aligment } onChange={ ( v ) => updateSetting( 'lfb_button_aligment', v ) } />
				</div>
				<RangeControl label="Font Size (px)" value={ settings.lfb_button_font_size } onChange={ ( v ) => updateSetting( 'lfb_button_font_size', v ) } min={ 8 } max={ 40 } />
				<RangeControl label="Top/Bottom Padding (%)" value={ settings.lfb_btn_padding_tb } onChange={ ( v ) => updateSetting( 'lfb_btn_padding_tb', v ) } min={ 0 } max={ 40 } />
				<RangeControl label="Button Width (%)" value={ settings.lfb_btn_padding_lr } onChange={ ( v ) => updateSetting( 'lfb_btn_padding_lr', v ) } min={ 0 } max={ 100 } />
			</PanelBody>

			{ /* Custom CSS */ }
			<PanelBody title="Custom CSS" initialOpen={ false }>
				<TextareaControl
					value={ settings.lfb_custom_css }
					onChange={ ( v ) => updateSetting( 'lfb_custom_css', v ) }
					rows={ 10 }
					className="lfb-gp-css-ta"
					placeholder="/* Add your custom CSS here */"
				/>
			</PanelBody>

			{ /* Reset */ }
			<PanelBody title="Reset Styles" initialOpen={ false } className="lfb-gp-reset-panel">
				<p className="lfb-gp-reset-desc">Reset all styling and customization to defaults.</p>
				<Button
					variant="secondary"
					isDestructive
					onClick={ () => {
						if ( window.confirm( 'This will reset all styling and customization. Do you want to proceed?' ) ) {
							setSettings( { ...DEFAULT_SETTINGS } );
						}
					} }
				>
					Reset All Styles
				</Button>
			</PanelBody>

			{ /* Footer save bar */ }
			<div className="lfb-gp-footer">
				<Button
					variant="primary"
					className={ `lfb-gp-save-btn lfb-gp-save-${ saveState }` }
					onClick={ handleSave }
					isBusy={ saveState === 'saving' }
					disabled={ saveState === 'saving' }
				>
					{ saveLabel }
				</Button>
			</div>
		</div>
	);
}

document.addEventListener( 'DOMContentLoaded', () => {
	const { createRoot, render } = wp.element;
	const root = document.getElementById( 'lfb-design-root' );
	if ( ! root ) return;

	if ( typeof createRoot === 'function' ) {
		createRoot( root ).render( <DesignPanel /> );
	} else if ( typeof render === 'function' ) {
		render( <DesignPanel />, root );
	}
} );
