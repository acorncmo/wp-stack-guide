<?php
$context = Timber\Timber::context();
$context['post'] = Timber\Timber::get_post();
Timber\Timber::render( 'front-page.twig', $context );
