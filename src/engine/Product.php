<?php

namespace Schema\Engine;

class Product extends BaseEngine {

    public $product_id, $current_product, $schema_name;

    public function __construct()
    {
        $this->schema_name = 'product.json';
        $this->schema_path = dirname( SCHEMA_PLUGIN_PATH ) . '/templates/';
        global $product;
        $this->current_product = $product;
        $this->product_id = $product->get_id();
    }

    public function name() {
        $product = wc_get_product( $this->product_id );
        return $product ? $product->get_name() : 'Default Product Name';
    }

    public function read_schema() {

        $schema_data = file_get_contents( $this->schema_path . $this->schema_name );
        $schema_data_decoded = json_decode( $schema_data, true );
        return $schema_data_decoded;
    }

    public function update_schema() {
        $schema_arr = $this->read_schema();

        $schema_arr['name'] = $this->name();

        $updated_schema_data = json_encode( $schema_arr );

        return $updated_schema_data;
    }

    public static function attach_schema() {
        $instant = new self();
        $updated_data = $instant->update_schema();
        echo "<script type='application/ld+json'>$updated_data</script>";
    }

}