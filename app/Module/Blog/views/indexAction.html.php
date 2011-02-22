<?php
// Sets page title
// @see app/layouts/app.html.php
$view->head()->title('Blog Posts from Alloy!');
?>

<h2>Blog Posts</h2>
<p><?php echo $view->link('Add New', array(
    'module' => 'Blog',
    'action' => 'new'
    ), 'module_action'); ?></p>

<?php
// Datagrid to display Blog posts
$table = $this->generic('datagrid')
    ->data($posts)
    ->column('Title', function($item) {
        return $item->title;
    })
    ->column('Published', function($item) use($view) {
        return $view->toDate($item->date_publushed);
    })
    ->column('Edit', function($item) use($view) {
        return $view->link('Edit', array(
            'module' => 'Blog',
            'action' => 'edit'
            ));
    })
    ->noData(function() {
        return "<p>No blog posts found.</p>";
    });

// Renders full table for all items
echo $table->content();
?>