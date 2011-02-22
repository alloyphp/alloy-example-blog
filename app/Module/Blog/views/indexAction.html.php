<?php
// Sets page title
// @see app/layouts/app.html.php
$view->title('Hello from Alloy Framework!');
?>

<h2>Blog Posts</h2>
<?php
$table = $this->generic('datagrid')
	->data($posts)
	->column('Title', function($item) {
		return $item->title;
	})
	->column('Published', function($item) use($view) {
		return $view->toDate($item->date_publushed);
	})
	->column('Edit', function($item) use($view) {
		return $view->link();
	});

// Renders full table for all items given on __toString
echo $table;
?>