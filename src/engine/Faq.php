<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;

class Faq extends BaseEngine {

    private $post_id;

    public function __construct( $post_id = null ) {
        $this->schema_file      = 'faq.json';

        parent::__construct();

        $this->schema_type      = 'FAQ';
        $this->post_id          = $post_id;

//        error_log( print_r( 'here', true ) );
    }

    protected function update_schema()
    {
        $this->schema            = json_encode( $this->faq( $this->schema_structure ) );

        return apply_filters( "schemax_{$this->schema_type}_update_schema", $this->schema );
    }

    protected function faq( $faq_arr ) {

        return apply_filters("schemax_{$this->schema_type}_faq", $faq_arr );
    }
}