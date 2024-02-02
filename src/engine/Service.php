<?php

namespace Schema\Engine;

class Service {

    public $schema_root_path;

    public function __construct() {
        $this->schema_root_path = dirname( SCHEMA_PLUGIN_PATH ) . '/templates/';
    }


    public function read_schema( $schema_name ) {
        $schema_data = file_get_contents( $this->schema_root_path . $schema_name );

        $schema_data_decoded = json_decode( $schema_data, true );

        return $schema_data_decoded;
    }
}