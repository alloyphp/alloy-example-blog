<?php
// Sets page title
// @see app/layouts/app.html.php
$view->head()->title('Delete ' . $post->title . '?');
?>

<h2>Delete <?php echo $post->title; ?>?</h2>

<?php
// Datagrid to display Blog posts
$form = $this->generic('form')
    ->action($view->url(array(
        'module' => 'Blog',
        'item' => $post->id
        ), 'module_item'))
    ->method('delete')
    ->data(array(
        'item' => $post->id
    ))
    ->submit('Delete');

// Renders full table for all items
echo $form->content();
?>

<p><?php echo $view->link('Back to Post Listing', array(
    'module' => 'Blog',
    'action' => 'index'
    ), 'module_action'); ?></p>