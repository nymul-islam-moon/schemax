<?php

namespace Schema\Engine;

class Product extends BaseEngine {

    public $product, $schema_name;

    public function __construct( $product_id = null )
    {
        global $product;
        if($product instanceof  \WC_Product) {
            $this->product = $product;
        }else{
            $this->product = wc_get_product($product_id);
        }

        $this->schema_name = 'product.json';
        $this->schema_path = dirname( SCHEMA_PLUGIN_PATH ) . '/templates/';
    }

    public function name() {
        return $this->product ? $this->product->get_name() : '';
    }

    public function description() {
        return $this->product ? $this->product->get_description() : '';
    }

    public function image() {
        if ($this->product) {
            $image_id = $this->product->get_image_id();
            return wp_get_attachment_image_url($image_id, 'full');
        }
        return '';
    }

    public function read_schema() {

        $schema_data = file_get_contents( $this->schema_path . $this->schema_name );
        $schema_data_decoded = json_decode( $schema_data, true );
        return $schema_data_decoded;
    }

    public function update_schema() {
        $schema_arr = $this->read_schema();

        $schema_arr['name'] = $this->name();
        $schema_arr['description'] = $this->description();
        $schema_arr['image'] = $this->image();

        $updated_schema_data = json_encode( $schema_arr );

        return $updated_schema_data;
    }

    public static function attach_schema() {
        $instant = new self();
        $updated_data = $instant->update_schema();
        echo "<script src='schemax' type='application/ld+json'>$updated_data</script>";
    }

}