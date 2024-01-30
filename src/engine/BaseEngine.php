<?php

namespace Schema\Engine;

class BaseEngine {

    protected $templatePath, $fileName, $schemaUpdatedData = 'nymul-islam';

    function __construct() {
        $this->templatePath = dirname( SCHEMA_PLUGIN_PATH ) . 'templates/';
        add_action( 'wp_head', [ $this, 'attach_to_wp_head' ] );
    }

    public function schema() {
        $fileData = file_get_contents( $this->templatePath . $this->fileName );

        return $fileData;
    }

    protected function attach_to_wp_head() {
        echo "nymul-islam";
//        echo "<script type='application/ld+json'>$this->schemaUpdatedData</script>>";
    }
}