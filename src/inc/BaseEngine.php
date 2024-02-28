<?php

namespace Schema\Inc;

abstract class BaseEngine {

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

    /**
     * Update the schema with new data.
     * Child classes should implement this method to modify $this->schema.
     *
     * @return mixed
     */
    abstract protected function update_schema();

    /**
     * Attach schema data in the script tag with schema type
     *
     * @return void
     */
    public function __destruct() {
        $this->update_schema();
        $schema = apply_filters("schemax_{$this->schema_type}_schema", $this->schema);
        echo "<script src='schemax-$this->schema_type' type='application/ld+json'>$schema</script>";
    }
}