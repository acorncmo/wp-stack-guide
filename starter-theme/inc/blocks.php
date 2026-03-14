<?php
/**
 * Block render helper.
 *
 * Shared pipeline for all blocks (Lazy Blocks, ACF, or native Gutenberg).
 * Every block renders through Timber/Twig using this single function.
 *
 * Usage in a block.php shim:
 *   echo theme_block_render( 'hero-section', $attributes );
 *
 * The corresponding Twig template lives at:
 *   templates/blocks/hero-section.twig
 */

use Timber\Timber;

function theme_block_render( $template, $attrs = [] ) {
    $context = Timber::context();
    $context['attrs'] = $attrs;
    return Timber::compile( "blocks/{$template}.twig", $context );
}
