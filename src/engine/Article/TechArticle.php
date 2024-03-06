<?php

namespace Schema\Engine\Article;

class TechArticle extends Article {

    public function __construct( $post_id = null )
    {
        parent::__construct($post_id);

        $this->schema_type = 'TechArticle';
    }

    /**
     * Type key
     *
     * @return string
     */
    protected function type()
    {
        return 'TechArticle';
    }

}