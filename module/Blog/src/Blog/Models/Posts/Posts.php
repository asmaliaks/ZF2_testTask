<?php

namespace Blog\Models\Posts;

class Posts {
    public $id;
    public $title;
    public $text;
    public $date;
    public $author_id;
    
    public function exchangeArray($data){
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->text = (isset($data['text'])) ? $data['text'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        $this->author_id = (isset($data['author_id'])) ? $data['author_id'] : null;
    }
    
    public function getArrayCopy()
{
    return get_object_vars($this);
}
}
