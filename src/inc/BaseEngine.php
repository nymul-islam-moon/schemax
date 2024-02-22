<?php

namespace Schema\Inc;

class BaseEngine {

    /**
     * Global variables
     *
     * @var
     */
    protected $schema_file, $schema_structure, $schema_type, $schema;

    /**
     * BaseEngine __construct method
     */
    protected function __construct() {
        $this->schema_structure = $this->read_schema( $this->schema_file );
    }

    /**
     * Read the schema data from json file
     *
     * @param $schema_file
     * @return mixed
     */
    protected function read_schema( $schema_file ) {
        $schema_data = file_get_contents( dirname( SCHEMA_PLUGIN_PATH ) . '/templates/' . $schema_file );

        return json_decode( $schema_data, true );
    }

    protected function updated_schema() {

    }

    public function __destruct() {
        $this->update_schema();
        echo "<script src='schemax-$this->schema_type' type='application/ld+json'>$this->schema</script>";
    }
}