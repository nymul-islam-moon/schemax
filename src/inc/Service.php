<?php

namespace Schema\Inc;

class Service {

    public $schema_root_path;

    public function __construct() {
        $this->schema_root_path = dirname( SCHEMAX_PATH ) . '/templates/';
    }


    /**
     * Read the schema data
     *
     * @param $schema_name
     * @return mixed
     */
    public function read_schema( $schema_name ) {
        $schema_data = file_get_contents( $this->schema_root_path . $schema_name );

        $schema_data_decoded = json_decode( $schema_data, true );

        return $schema_data_decoded;
    }

    /**
     * Show the Schema in meta tag
     *
     * @return void
     */
    public function attach_schema( $schema, $type ) {
        echo "<script src='schemax-$type' type='application/ld+json'>$schema</script>";
    }
}