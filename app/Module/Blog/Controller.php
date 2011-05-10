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

        if('html' == $request->format) {
            // Returns Alloy\View\Template object that renders template on __toString:
            //   views/indexAction.html.php
            return $this->template(__FUNCTION__)
               ->set(compact('posts'));
        } else {
            // API response
            return $kernel->resource($posts);
        }
    }


    /**
     * View single post
     * @method GET
     */
    public function viewAction(Alloy\Request $request)
    {
        $kernel = \Kernel();
        $mapper = $kernel->mapper();

        $post = $mapper->get('Module\Blog\Post', (int) $request->item);
        if(!$post) {
            return false;
        }

        if('html' == $request->format) {
            return $this->template(__FUNCTION__)
                ->set(compact('post'));   
        } else {
            return $kernel->resource($post);
        }
    }


    /**
     * Create new blog post form
     * @method GET
     */
    public function newAction(Alloy\Request $request)
    {
        $kernel = \Kernel();
        $post = $kernel->mapper()->get('Module\Blog\Post');
        return $kernel->spotForm($post); // From 'Spot' plugin
    }


    /**
     * Helper to save item
     */
    public function saveItem(\Module\Blog\Post $item)
    {
        $kernel = \Kernel();
        $mapper = $kernel->mapper();
        $request = $kernel->request();

        // Attempt save
        if($mapper->save($item)) {
            $itemUrl = $kernel->url(array('module' => 'Blog', 'item' => $item->id), 'module_item');

            // HTML
            if('html' == $request->format) {
                return $kernel->redirect($itemUrl);
            // Others (XML, JSON)
            } else {
                return $kernel->resource($item->data())
                    ->created($itemUrl);
            }
        // Handle errors
        } else {
            // HTML
            if('html' == $request->format) {
                // Re-display form
                $res = $kernel->spotForm($item);
            // Others (XML, JSON)
            } else {
                $res = $kernel->resource(array());
            }

            // Set HTTP status and errors
            return $res->status(400)
                ->errors($mapper->errors());
        }
    }


    /**
     * New post creation
     * @method POST
     */
    public function postMethod(Alloy\Request $request)
    {
        $kernel = \Kernel();
        $mapper = $kernel->mapper();

        $item = $mapper->get('Module\Blog\Post');
        $item->data($request->post());

        // Common save functionality
        return $this->saveItem($item);
    }


    /**
     * Edit single post
     * @method GET
     */
    public function editAction(Alloy\Request $request)
    {
        $kernel = \Kernel();
        $mapper = $kernel->mapper();

        $post = $mapper->get('Module\Blog\Post', (int) $request->item);
        if(!$post) {
            return false;
        }

        return $kernel->spotForm($post)
            ->action($kernel->url(array('module' => 'Blog', 'item' => $post->id), 'module_item'))
            ->method('put');
    }


    /**
     * Edit existing post
     * @method PUT
     */
    public function putMethod(Alloy\Request $request)
    {
        $kernel = \Kernel();
        $mapper = $kernel->mapper();

        $item = $mapper->get('Module\Blog\Post', $request->item);
        if(!$item) {
            return false;
        }

        $item->data($request->post());
        $item->date_modified = new \DateTime();

        // Common save functionality
        return $this->saveItem($item);
    }


    /**
     * Delete confirmation
     * @method GET
     */
    public function deleteAction(Alloy\Request $request)
    {
        $kernel = \Kernel();
        $mapper = $kernel->mapper();

        $post = $mapper->get('Module\Blog\Post', $request->item);
        if(!$post) {
            return false;
        }

        return $this->template(__FUNCTION__)
            ->set(compact('post'));
    }
    

    /**
     * Delete post
     * @method DELETE
     */
    public function deleteMethod(Alloy\Request $request)
    {
        $kernel = \Kernel();
        $mapper = $kernel->mapper();

        $item = $mapper->get('Module\Blog\Post', $request->item);
        if(!$item) {
            return false;
        }

        $mapper->delete($item);

        return $kernel->redirect($kernel->url(array('module' => 'Blog'), 'module'));
    }


    /**
     * Auto-install for module entity database tables, etc.
     */
    public function install()
    {
        return \Kernel()->mapper()->migrate('Module\Blog\Post');
    }
}