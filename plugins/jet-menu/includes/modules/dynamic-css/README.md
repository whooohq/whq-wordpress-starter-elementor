# Cherry X Dynamic CSS module

Allows to connect CSS with DB options in LESS/SCSS-like way. Also allows to collect dynamically added in content CSS.

## How to use:

1. Copy this module into your theme/plugin
2. Add path to `cherry-x-dynamic-css.php` file to `CX_Loader` initialization.
3. Initialize module on `after_setup_theme` hook with priority `0` or later, Example:

```php
add_action( 'after_setup_theme', 'twentyseventeen_init', 0 );

function twentyseventeen_init() {

	$dynamic_css = new CX_Dynamic_CSS( array(
		'prefix'         => 'twentyseventeen',
		'type'           => 'theme_mod',
		'parent_handles' => array(
			'css' => 'twentyseventeen-style',
			'js'  => 'twentyseventeen-global',
		),
		'single'         => false,
		'css_files'      => array(
			get_theme_file_path( 'assets/css/dynamic.css' ),
			get_theme_file_path( 'assets/css/dynamic/site/elements.css' ),
			get_theme_file_path( 'assets/css/dynamic/widgets/widget-default.css' ),
		),
		'options_cb'    => 'get_theme_mods',
		'options'       => array(
			'header_logo_color',
			'header_logo_font_family',
			'header_logo_font_style',
			'header_logo_font_weight',
			'header_logo_font_size',
			'body_font_family',
			'body_font_style',
			'body_font_weight',
			'body_font_size',
		),
	) );

	$dynamic_css->collector->add_style(
		'.test',
		array(
			'color' => '#f00',
		)
	);

}
```

## Arguments:
`CX_Dynamic_CSS` accepts an array of options with next structure:

* `prefix`         - theme mod / option prefix
* `type`           - options type for database - theme_mod or options
* `single`         - Works only for options `type`. Defines how options are stored in database, if `true` - in single array, named `prefix`, if `false` - each option is separate field in DB.
* `parent_handles` - Array of main theme/plugin CSS and JS handles. This handles will be relative handles for adding module assets with `add_inline_style` and `add_inline_script` functions. JS handle is used only for adding dynmic styles with `add_style` method, so it not necessary.
* `css_files`      - List of CSS files with dynmic CSS markup.
* `options`        - registered options array (array of options keys, thats will be converted into variables)
* `options_cb`     - if you want to pass prepared variables with values you may pass callback function into this argument. Callback function should return array of pairs 'variable_name' => 'variable_value'.

## Notes:
If you want dynically add styles from content instead of CSS files with variables - you should store `CX_Dynamic_CSS` into variable. To add dynmic styles you need to call next method:
```php
$dynamic_css->collector->add_style(
	$css_selector,
	$css_rules,
	$media
);
```
Where:
* `$css_selector` - String. CSS selector to apply styles for.
* `$css_rules` - Array. Array of CSS rules, which should be apllied. Example:
   ```php
   array(
      'color'      => '#f00',
      'text-align' => get_option( 'my-text-align' )
   )
   ```
* `$media` - Array. Media query args. Example:
  ```php
   array(
      'min' => '768px',
      'max' => '1199px',
   )
   ```
