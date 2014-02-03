<?php

namespace Blog\Models\Posts;

use Zend\Db\TableGateway\TableGateway;

class PostsTable {
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll(){
         $resultSet = $this->tableGateway->select();
         return $resultSet;        
    }
    
    public function addPost(Posts $post){
        $data = array(
             'title'     => $post->title,
             'text'      => $post->text,
             'author_id' => '$post->author_id',
             'date'      => $post->date
         );

         $id = (int) $post->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getPostById($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('User doesn\'t exist');
             }
         }
    }
    
    public function getPostById($id){
         
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;        
    }
    
    public function editPost($data){
        
    }
    
    public function deletePost ($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
}