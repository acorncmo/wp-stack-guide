<?php
$context = Timber\Timber::context();
$context['post'] = Timber\Timber::get_post();
Timber\Timber::render( 'single.twig', $context );
