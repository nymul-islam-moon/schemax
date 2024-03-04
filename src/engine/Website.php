<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;


class Website extends BaseEngine {

    /**
     * Website schema constructor
     *
     * @param $post_id
     */
    public function __construct( $post_id = null ) {


        $this->schema_file      = 'webSite.json';

        parent::__construct();

        $this->schema_type      = 'Website';
        $this->post_id          = $post_id;
    }

    protected function update_schema() {
        $this->schema           = json_encode( $this->single_site( $this->schema_structure ) );
    }

    protected function single_site( $website_arr ) {
        return $website_arr;
    }

}