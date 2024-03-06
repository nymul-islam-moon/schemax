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

        $schema_data = file_get_contents( SCHEMAX_ASSETS . '/templates/' . $schema_file );

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
     * Get Youtube video thumbnail
     *
     * @param $video_id
     * @param $size
     * @return false|string
     */
    protected function get_thumbnail( $video_id, $size='default' ) {

        if ( ! empty( $video_id ) ) {
            $thumbnail_url = 'https://img.youtube.com/vi/' . $video_id . '/';
            // Choose the appropriate size
            switch ($size) {
                case 'default':
                    $thumbnail_url .= 'default.jpg'; // 120x90 pixels
                    break;
                case 'medium':
                    $thumbnail_url .= 'mqdefault.jpg'; // 320x180 pixels
                    break;
                case 'high':
                    $thumbnail_url .= 'hqdefault.jpg'; // 480x360 pixels
                    break;
                case 'standard':
                    $thumbnail_url .= 'sddefault.jpg'; // 640x480 pixels
                    break;
                case 'maxres':
                    $thumbnail_url .= 'maxresdefault.jpg'; // Maximum resolution
                    break;
                default:
                    $thumbnail_url .= 'default.jpg'; // Default size
            }
            return $thumbnail_url;
        } else {
            return false; // Video ID not found
        }
    }

    /**
     * Get images from the content
     *
     * @param $content
     * @return string[]
     */
    protected function get_images( $content ) {
        // Define the regular expression to match <img> tags and capture the src attribute
        $pattern = '/<img[^>]+src="([^">]+)"/';

        // Use preg_match_all to find all matches
        preg_match_all( $pattern, $content, $matches );

        // The image URLs are stored in the first captured group, which is $matches[1]
        $image_urls = $matches[1];

        return $image_urls;
    }

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