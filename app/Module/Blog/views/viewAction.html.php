<?php
// Sets page title
// @see app/layouts/app.html.php
$view->head()->title($post->title);
?>

<h2><?php echo $post->title; ?></h2>

<p><?php echo nl2br($post->body); ?></p>

<?php echo $view->link('Back to Post Listing', array(
  'module' => 'Blog', 'action' => 'index'), 'module_action'); ?>

<?php
$view->block('sidebar', function() use($kernel, $post) {
    echo $kernel->dispatch('Blog', 'tagCloud', array($post));
});
$view->block('extra_content', function() use($kernel, $post) {
    echo $kernel->dispatch('Ratings', 'starsForType', array('blog', $post->id));
    echo $kernel->dispatch('Comments', 'viewType', array('blog', $post->id));
});
?>