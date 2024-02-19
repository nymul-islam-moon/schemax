<?php

namespace Schema\Engine;

use Schema\Inc\Service;

class Article {

    private $schema_name, $schema_type, $schema_service, $post_id;

    public function __construct( $post_id = null ) {
        $this->schema_name      = 'article.json';
        $this->schema_type      = 'article';
        $this->post_id          = $post_id;

        // creating new object of the service schema
        $this->schema_service   = new Service();
    }

    /**
     * Update the Article schema with real time data
     *
     * @return mixed|null
     */
    protected function update_schema() {
        $this->schema_structure             = $this->schema_service->read_schema( $this->schema_name );

        if ( is_single() ) {
            $updated_schema_data            = json_encode( $this->single_article( $this->schema_structure, 'NewsArticle' ) );
        }

        return apply_filters( "schemax_{$this->schema_type}_update_schema", $updated_schema_data );
    }


    protected function single_article( $article_arr, $type ) {

        if ( isset( $article_arr['@type'] ) ) {
            $article_arr['@type'] = $type;
        }else {
            unset( $article_arr['@type'] );
        }

        if ( isset( $article_arr['mainEntityOfPage'] ) && ! empty( $this->mainEntityOfPage( $article_arr['mainEntityOfPage'] ) ) ) {
            $article_arr['mainEntityOfPage'] = $this->mainEntityOfPage( $article_arr['mainEntityOfPage'] );
        } else {
            unset( $article_arr['mainEntityOfPage'] );
        }

        return $article_arr;
    }

    /**
     * Get the mainEntityOfPage array data
     * @param $mainEntityOfPage
     * @return array
     */
    protected function mainEntityOfPage( $mainEntityOfPage ) {

        if ( isset( $mainEntityOfPage['@id'] ) ) {
            $mainEntityOfPage['@id']       = get_permalink($this->post_id);
        }else {
            unset( $mainEntityOfPage['@id'] );
        }

        if ( ! empty( $mainEntityOfPage['@id'] ) ) {
            return $mainEntityOfPage;
        }

        return [];
    }


    /**
     * Show the Schema in meta tag
     *
     * @return void
     */
    public function attach_schema() {
        $updated_data = $this->update_schema();
        echo "<script src='schemax-$this->schema_type' type='application/ld+json'>$updated_data</script>";
    }
}