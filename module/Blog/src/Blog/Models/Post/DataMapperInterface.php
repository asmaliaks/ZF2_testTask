<?php

namespace Blog\Modles\Post;

interface DataMapperInterface {
    /**
     * 
     * @param type $id
     * @return Post;
     */
    public function fetchPostById($id);
    public function fetchPostsByCategoryiD($id);
}
