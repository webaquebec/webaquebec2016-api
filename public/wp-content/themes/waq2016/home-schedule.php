<?php
/*
Template Name: Horaire
*/
global $post;

$context = Timber::get_context();
$post = new TimberPost();

// Set context
$context = array_merge($context, array(
    'post' => $post
));

Timber::render('home/schedule.twig', $context);
