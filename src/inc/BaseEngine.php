<?php

namespace Schema\Inc;

class BaseEngine {

    protected $schema_file, $schema_structure, $schema_type;

    protected function __construct() {
        $this->schema_structure = $this->read_schema( $this->schema_file );
    }

    /**
     * Read the schema data
     *
     * @param $schema_name
     * @return mixed
     */
    public function read_schema( $schema_file ) {
        $schema_data = file_get_contents( dirname( SCHEMA_PLUGIN_PATH ) . '/templates/' . $schema_file );

        return json_decode( $schema_data, true );
    }
}