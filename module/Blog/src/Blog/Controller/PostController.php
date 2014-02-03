<?php
/**
 *     author asmalouski
 * 
 */

namespace Blog\Controller;
use Blog\Forms\PostForm;
use Blog\Forms\Filters\PostFilter;
use Blog\Models\Posts\Posts;
use Blog\Models\Posts\PostsTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class PostController extends AbstractActionController
{
    protected $postsTable;
    
    public function listAction(){
        return new ViewModel(array(
            'posts' => $this->getPostsTable()->fetchAll(),
        ));
    }
    
    public function viewPostAction(){
        
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('postId');
        $post = $this->getPostsTable()->getPostById($id);
        $viewObj = new ViewModel(array(
            'post' => $post
            
        )); 
        return $viewObj;
    }
    
    public function viewAction()
    {
     //   $postId = $this->getEvent()->getRouteMatch()->getParam('postId');
        $content = 'Пагоню не спыняе нi боль, нi кроу, нi страх! I над крывiцкiм краем як птах лунае сцяг.';
        $viewObj = new ViewModel(array(
            'title' => 'Основная идея',
            'content' => $content
            
        ));
       $widgetObj = new ViewModel();
       $widgetObj->setTemplate('post/widget');
       $viewObj->addChild($widgetObj, 'widget');
        return $viewObj;
    }
    
    public function addPostAction(){
      
        $form = new PostForm();
        $request = $this->getRequest();
        if($request->isPost()){
            $newPost = new Posts();
            $regFilter = new PostFilter();        
            $form->setInputFilter($regFilter->getInputFilter());  
            $form->setData($request->getPost());
            if($form->isValid()){
                $newPost->exchangeArray($form->getData());
                $this->getPostsTable()->addPost($newPost);
                
                return $this->redirect()->toRoute('blogPost/add-post');
            }else{
                    
            }
        }
        return new ViewModel(array(
            'form' => $form
        ));
    }
    
    public function editPostAction(){
         $id = (int) $this->getEvent()->getRouteMatch()->getParam('postId');
         if(!$id){
            $id = $this->getRequest()->getPost('id');
         }   
         $post = $this->getPostsTable()->getPostById($id);
         $form = new PostForm();
         $form->bind($post);
         $form->get('submit')->setAttribute('value', 'Редактировать');
         
         $request = $this->getRequest();

         if($request->isPost()){
             $regFilter = new PostFilter();
             $form->setInputFilter($regFilter->getInputFilter());
             $form->setData($request->getPost());
             if($form->isValid()){
                 $this->getPostsTable()->addPost($post);
                 
                 return $this->redirect()->toRoute('blogPost');
             }
         }
         
         return array(
             'id' => $id,
             'form' => $form,
         );
    }
    
    public function removePostAction(){
        $id = (int) $this->params()->fromRoute('id', $_GET['id']);
        $this->getPostsTable()->deletePost($id);
        $this->redirect()->toRoute('blogPost');
    }
    
    private function getPostsTable(){
         if (!$this->postsTable) {
             $sm = $this->getServiceLocator();
             $this->postsTable = $sm->get('Blog\Models\Posts\PostsTable');
         }
         return $this->postsTable;        
    }
}
