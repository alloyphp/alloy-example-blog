<?php
namespace Module\Blog;
use App, Alloy;

class Post extends Spot\EntityAbstract
{
	protected static $_datasource = 'blog_posts';
	
	public static function fields()
	{
		return array(
			'id' => array('type' => 'int', 'primary' => true, 'serial' => true),
			'title' => array('type' => 'string', 'required' => true),
			'body' => array('type' => 'text', 'required' => true),
			'status' => array('type' => 'int', 'default' => 0),
			'date_published' => array('type' => 'datetime', 'default' => null),
			'date_created' => array('type' => 'datetime', 'default' => new \DateTime()),
			'date_modified' => array('type' => 'datetime', 'default' => new \DateTime())
		);
	}
}