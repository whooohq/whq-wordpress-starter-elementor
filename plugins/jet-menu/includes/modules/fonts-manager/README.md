# Cherry X Fonts Manager module

Module enqueue Google web fonts depends from options or theme mods

## How to use:

1. Copy this module into your theme/plugin
2. Add path to `cherry-x-fonts-manager.php` file to `CX_Loader` initialization.
3. Initialize module on `after_setup_theme` hook with priority `0` or later, Example:

```php
add_action( 'after_setup_theme', 'twentyseventeen_init', 0 );

function twentyseventeen_init() {

	new CX_Fonts_Manager( array(
		'prefix'    => 'twentyseventeen',
		'single'    => false,
		'type'      => 'theme_mod',
		'get_fonts' => false,
		'options'   => array(
			'body' => array(
				'family'  => 'body_font_family',
				'style'   => 'body_font_style',
				'weight'  => 'body_font_weight',
				'charset' => 'body_character_set',
			),
			'h1' => array(
				'family'  => 'h1_font_family',
				'style'   => 'h1_font_style',
				'weight'  => 'h1_font_weight',
				'charset' => 'h1_character_set',
			),
		)
	) );

}
```

## Arguments:
`CX_Fonts_Manager` accepts an array of options with next structure:
* `prefix`        - theme mod / option prefix
* `type`          - options type for database - theme_mod or options
* `single`        - Works only for options `type`. Defines how options are stored in database, if `true` - in single array, named `prefix`, if `false` - each option is separate field in DB.
* `get_fonts`     - Callback that returns array of all available fonts in next format - 'CSS font-family value' => 'Font name'. This is required option.
* `options`       - registered options array

`options` is an associative array of arrays, key - is fonts options group slug. Value - is array of pairs - font CSS option => font DB option.

## Notes:
If you are using `CX_Customizer` module, you can automatically init `CX_Fonts_Manager` from customizer instance - deatils [here](https://github.com/Cherry-X-framework/cherry-x-customizer)

