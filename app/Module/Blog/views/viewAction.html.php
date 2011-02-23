<?php
// Sets page title
// @see app/layouts/app.html.php
$view->head()->title($post->title);
?>

<h2><?php echo $post->title; ?></h2>

<p><?php echo nl2br($post->body); ?></p>

<p><?php echo $view->link('Back to Post Listing', array(
    'module' => 'Blog',
    'action' => 'index'
    ), 'module_action'); ?></p>