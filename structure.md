# Lead Form Builder — Plugin Structure

## Root
```
lead-form-builder/
├── lead-form-builder.php       # Main plugin file — defines constants, bootstraps plugin
├── readme.txt                  # WordPress plugin readme
└── structure.md                # This file
```

---

## `/inc` — Core PHP Logic

```
inc/
├── inc.php                     # Master include — loads all core files & defines DB table constants
├── lfb-constant.php            # Plugin-wide constants (LFB_PLUGIN_URL, LFB_VER, etc.)
├── header.php                  # Defines lfb_admin_menu_header() wrapper
│
├── header-page/
│   └── header-page.php         # Renders the admin page header (title, breadcrumb, back button, add-new button)
│
├── lf-install.php              # Admin menu registration, asset enqueuing, page callbacks (lfb_lead_form_page, lfb_pro_feature)
├── lf-shortcode.php            # Registers [lead-form] shortcode → calls LFB_Front_end_FORMS
├── lf-db.php                   # LFB_SAVE_DB class — all database read/write operations
│
├── create-lead-form.php        # LFB_AddNewForm class + lfb_create_form_sanitize() — handles create & field sanitization
├── edit-delete-form.php        # LFB_EDIT_DEL_FORM class — edit and delete form logic
├── show-forms-backend.php      # LFB_SHOW_FORMS class — renders the admin form list table
├── show-lead.php               # LFB_Show_Leads class — renders today/total leads tables
│
├── front-end.php               # LFB_Front_end_FORMS class — renders form HTML on the frontend (all field types)
├── lead-store-type.php         # Handles form submission storage types
├── ajax-functions.php          # WordPress AJAX handlers for frontend form submission
│
├── lfb-svg-icons.php           # lfb_svg() helper — centralised SVG icon registry
├── lfb-color-settings.php      # LFB_COLORS class — live form color/design customizer (slide-in panel)
├── lfb-style.php               # Outputs custom inline CSS per form (color settings)
├── lfb-form-settings.php       # Form-level settings page (per-form config)
│
├── email-setting.php           # Admin/user email notification settings
├── export-leads.php            # Lead CSV export logic
├── lfb-import-export.php       # Form import/export (JSON)
├── lfb-widget.php              # WordPress widget integration
├── deactivate-feedback.php     # Deactivation feedback modal
│
├── options/
│   └── option.php              # Upgrade to Pro page — hero, features grid, about, recommended themes
│
└── themehunk-menu/
    ├── admin-menu.php          # ThemeHunk marketplace admin menu registration
    ├── plugins-list.php        # List of recommended ThemeHunk plugins
    └── th-option/
        ├── th-option.php       # themehunk_plugin_option class — plugin install/activate UI
        ├── tab-html.php        # Tab HTML layout for ThemeHunk marketplace page
        ├── header.php          # Marketplace page header
        ├── sidebar.php         # Marketplace page sidebar
        └── assets/
            ├── css/started.css
            └── js/th-options.js
```

---

## `/css` — Admin & Frontend Stylesheets

```
css/
├── b-style.css                 # Admin backend styles (header, form table, tabs, action buttons, design page)
├── f-style.css                 # Frontend form styles (fields, default card border, error states, required asterisk)
├── option-style.css            # Upgrade to Pro page styles
├── deactivate-feedback.css     # Deactivation modal styles
└── jquery.sweet-dropdown.min.css  # Dropdown component styles
```

---

## `/js` — Admin & Frontend Scripts

```
js/
├── b-script.js                 # Admin backend JS (form builder UI, field ordering, duplicate, delete, tab switching)
├── f-script.js                 # Frontend JS (form submission via AJAX, required field validation, email validation, error states)
├── upload.js                   # File upload field handler
├── deactivate-feedback.js      # Deactivation feedback modal JS
├── modernizr.js                # Modernizr feature detection
└── jquery.sweet-dropdown.min.js  # Dropdown component
```

---

## `/block` — Gutenberg Block

```
block/
├── app.php                     # Block registration, AJAX handler (lead_form_builderr_data), script localization
├── package.json                # npm config — uses @wordpress/scripts for build
│
├── src/                        # Source files (edit with these)
│   ├── block.json              # Block metadata (name, attributes, render file)
│   ├── index.js                # Block entry — registerBlockType()
│   ├── edit.js                 # Editor UI — form dropdown (SelectControl) + form HTML preview via AJAX
│   ├── save.js                 # Save → null (server-side render)
│   ├── render.php              # Frontend render — do_shortcode('[lead-form form-id=X]') from formid attribute
│   ├── view.js                 # Frontend view script
│   ├── editor.scss             # Editor-only styles (block preview, loading, empty states)
│   └── style.scss              # Frontend block styles
│
└── build/                      # Compiled output (auto-generated by `npm run build`)
    ├── block.json
    ├── index.js
    ├── index.css
    ├── index.asset.php
    ├── render.php              # Copied from src/render.php on build
    ├── style-index.css
    ├── view.js
    └── view.asset.php
```

### Block Data Flow
1. **Editor**: `edit.js` calls `lead_form_builderr_data` AJAX → gets all form titles for the dropdown and the rendered HTML preview of the selected form
2. **Save**: `formid` attribute is saved in post content (block JSON)
3. **Frontend**: `render.php` runs `do_shortcode('[lead-form form-id={formid}]')` server-side — same output as the shortcode

---

## `/elementor` — Elementor Widget

```
elementor/
├── class-lfb-init.php          # Initialises the Elementor integration
├── lfb-addon-elementor.php     # Registers the LFB Elementor widget
├── css/lfb-styler.css          # Elementor widget styles
└── modules/lfb-styler.php      # Elementor widget module
```

---

## `/images` — Plugin Images & SVGs

```
images/
├── icon.png / icon.svg         # Plugin icon
├── Contact-from-leadfomr-builder.png  # Upgrade to Pro page hero image
├── big-store-1.png             # Recommended theme thumbnail
├── open-shop-theme.png         # Recommended theme thumbnail
├── gogo-image.png              # Recommended theme thumbnail
├── load.gif / spinner.gif      # Loading spinners
└── *.svg                       # Various UI SVG icons (back, gear, captcha, etc.)
```

---

## `/notify` — Admin Notices

```
notify/
├── notify.php                  # Notice registration and display logic
└── notify-html.php             # Notice HTML template
```

---

## `/font-awesome` — Icon Font

```
font-awesome/
├── css/font-awesome.css        # Font Awesome 4 CSS (used for some frontend form icons)
└── fonts/                      # Font Awesome webfont files
```

---

## Key Design Patterns

| Concern | Where |
|---|---|
| Admin page header | `inc/header-page/header-page.php` → `lfb_header_page_manage()` |
| SVG icons (admin) | `inc/lfb-svg-icons.php` → `lfb_svg('icon-name', size)` |
| Form field sanitize | `inc/create-lead-form.php` → `lfb_create_form_sanitize()` |
| Frontend form render | `inc/front-end.php` → `LFB_Front_end_FORMS::lfb_show_front_end_forms()` |
| Frontend asset loading | `inc/lf-install.php` → `lfb_page_has_form()` — detects shortcode **or** block |
| Form submission (AJAX) | `inc/ajax-functions.php` + `js/f-script.js` |
| Custom form styles | `inc/lfb-style.php` + `inc/lfb-color-settings.php` |
| DB operations | `inc/lf-db.php` → `LFB_SAVE_DB` class |
| Required field star | `inc/front-end.php` → `lfb_req_star()` private method |
| Validation (JS) | `js/f-script.js` → `lfbValidateRequiredFields()` (required + email format) |
| Upgrade to Pro page | `inc/options/option.php` + `css/option-style.css` |

---

## CSS Variable Reference

Defined in `css/b-style.css` `:root`:

| Variable | Default | Usage |
|---|---|---|
| `--global-bg-active-color` | `#3c40e7` | Primary blue (buttons, active states) |
| `--global-bg-color` | `#8487ff` | Light blue (gradients) |
| `--global-bg-light-color` | `#f8faff` | Light background |
| `--global-count-bg-color` | `#ff7528` | Badge/counter orange |
| `--global-button-color` | `#3c40e7` | Button fill |
| `--global-button-hover-color` | `#0c12ff` | Button hover |

---

## Database Tables

| Table | Purpose |
|---|---|
| `{prefix}lead_form` | Stores form definitions (title, serialized field data, settings) |
| `{prefix}lead_form_data` | Stores submitted lead entries per form |
