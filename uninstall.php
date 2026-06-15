<?php
/**
 * Uninstall handler for AlternatePro Elements.
 *
 * Builder templates are intentionally preserved because they contain user-created
 * layouts. Plugin-owned transient/cache data can be removed here in future.
 *
 * @package AlternatePro\Elements
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_transient( 'apro_header_footer_template_cache' );
