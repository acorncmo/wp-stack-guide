<?php
/**
 * WordPress requires this file to exist.
 * Timber handles all routing -- see front-page.php, page.php, single.php, etc.
 */
$context = Timber\Timber::context();
$context['posts'] = Timber\Timber::get_posts();
Timber\Timber::render( 'index.twig', $context );
