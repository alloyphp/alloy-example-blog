<?php
namespace Module\Blog;
use App, Alloy;

/**
 * Blog Module
 * 
 * Extends from base Application controller so custom functionality can be added easily
 *   lib/App/Module/ControllerAbstract
 */
class Controller extends App\Module\ControllerAbstract
{
    /**
     * Index
     * @method GET
     */
    public function indexAction(Alloy\Request $request)
    {
        $kernel = \Kernel();
    	$mapper = $kernel->mapper();

        $posts = $mapper->all('Module\Blog\Post')
            ->order(array('date_published' => 'DESC'));

    	// Returns Alloy\View\Template object that renders template on __toString:
    	//   views/indexAction.html.php
        return $this->template(__FUNCTION__)
        	->set(compact('posts'));
    }
}