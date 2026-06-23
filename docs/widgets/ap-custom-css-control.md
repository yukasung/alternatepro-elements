# AP Custom CSS Shared Control

Date: 2026-06-23

## Summary

`AP Custom CSS` is implemented as a shared Elementor widget trait so AlternatePro widgets can reuse the same Advanced tab control, editor panel ordering, CSS sanitization, and frontend inline rendering behavior.

## Files

- `includes/Controls/ApCustomCssControl.php`
- `assets/js/custom-css-editor.js`
- `includes/Widgets/WidgetsModule.php`
- `includes/Widgets/SlidesWidget.php`
- `includes/Widgets/NavMenuWidget.php`
- `includes/Widgets/ImageCarouselWidget.php`
- `includes/Widgets/SiteLogoWidget.php`

## Usage

Add the trait to an Elementor widget:

```php
use AlternatePro\Elements\Controls\ApCustomCssControl;

final class ExampleWidget extends \Elementor\Widget_Base {
	use ApCustomCssControl;
}
```

Register the shared control after the widget's own controls:

```php
$this->register_ap_custom_css_controls(
	array(
		'placeholder' => "selector .example-widget {\n\t/* CSS */\n}",
		'description' => __( 'Use selector to scope rules to this AP widget.', 'alternatepro-elements' ),
	)
);
```

Render the shared CSS before widget markup:

```php
$settings = $this->get_settings_for_display();

$this->render_ap_custom_css( $settings );
```

## Behavior

- Adds an Advanced tab section labeled `AP Custom CSS`.
- Adds a CSS code editor using the shared setting key `ap_custom_css`.
- Moves the section to the bottom of the Elementor Advanced tab stack.
- Replaces the `selector` token with the current widget wrapper selector.
- Strips risky CSS constructs before inline output.
- Uses `assets/js/custom-css-editor.js` to keep the section and code editor body at the bottom of the editor panel.

## Current Widget Usage

- `AP Slides`
- `AP Menu`
- `AP Image Carosel`
- `AP Site Logo`

## Validation

Passed:

- `php -l includes/Controls/ApCustomCssControl.php`
- `php -l includes/Widgets/SlidesWidget.php`
- `php -l includes/Widgets/NavMenuWidget.php`
- `php -l includes/Widgets/ImageCarouselWidget.php`
- `php -l includes/Widgets/SiteLogoWidget.php`
- `php -l includes/Widgets/WidgetsModule.php`
- `node --check assets/js/custom-css-editor.js`
- Targeted PHPCS for the shared control, AP widgets using the control, and widgets module.
- All-plugin PHP syntax fallback.
- `git diff --check`
- AP Site Logo frontend smoke validation confirmed shared `selector` replacement and inline CSS rendering through the common trait.
