<?php
/**
 *     author asmalouski
 * 
 */

namespace Tutorial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    public function postAction()
    {
        $postId = $this->getEvent()->getRouteMatch()->getParam('postId');
       
        return new ViewModel();
    }
}
