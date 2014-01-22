<?php
/**
 *     author asmalouski
 * 
 */

namespace Tutorial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PostController extends AbstractActionController
{
    public function viewAction()
    {
     //   $postId = $this->getEvent()->getRouteMatch()->getParam('postId');
        $content = 'Пагоню не спыняе нi боль, нi кроу, нi страх! I над крывiцкiм краем як птах лунае сцяг.';
        $viewObj = new ViewModel(array(
            'title' => 'Основная идея',
            'content' => $content
            
        ));
       
        return $viewObj;
    }
}
