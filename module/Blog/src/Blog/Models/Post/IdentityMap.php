<?php

namespace Blog\Models\Post;

use Blog\IdentityMapAbstract;

class IdentityMap extends IdentityMapAbstract {
    
    public function __construct(DataMapInterface $dm){
        $this->_dataMapper = $dm;
        return $this;
    }
    /**
     * 
     * @param $id
     * @return bool
     */
    private function isPosCached($id){
        
    }
    /**
     * 
     * @param type $id
     * @return Post
     */
    private function fetchPostById($id){
        
    }
}

