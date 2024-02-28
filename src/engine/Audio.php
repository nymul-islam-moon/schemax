<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;

class Audio extends BaseEngine {

    private $audio_links, $post_id;
    public function __construct( $audio_links , $post_id = null ) {

        $this->schema_file          = 'audio.json';

        parent::__construct();

        $this->schema_type          = 'audio';
        $this->post_id              = $post_id;
        $this->audio_links          = $audio_links;
    }

    protected function update_schema() {
        $this->schema           = json_encode( $this->single_audio( $this->schema_structure ) );
    }

    protected function single_audio( $audio_arr ) {

        /**
         * Audio schema name key
         */
        if ( isset( $audio_arr['name'] ) ) {
            $name = $this->name();
            if (!empty($name)) {
                $audio_arr['name'] = $name;
            } else {
                unset($audio_arr['name']);
            }
        }

        /**
         * Audio schema description key
         */
        if ( isset( $audio_arr['description'] ) ) {
            $description = $this->description();
            if ( ! empty( $description ) ) {
                $audio_arr['description'] = $description;
            } else {
                unset( $audio_arr['description'] );
            }
        }

        /**
         * Audio schema contentUrl key
         */
        if ( isset( $audio_arr['contentUrl'] ) ) {
            $contentUrl = $this->contentUrl();
            if ( ! empty( $contentUrl ) ) {
                $audio_arr['contentUrl'] = $contentUrl;
            } else {
                unset( $audio_arr['contentUrl'] );
            }
        }

        if ( isset( $audio_arr['additionalContentUrls'] ) ) {
            $additionalContentUrls = $this->additionalContentUrls();

            if ( !empty( $additionalContentUrls ) ) {
                $audio_arr['additionalContentUrls'] = $additionalContentUrls;
            } else {
                unset( $audio_arr['additionalContentUrls'] );
            }
        }

        /**
         * Audio schema encodingFormat key
         */
        if ( isset( $audio_arr['encodingFormat'] ) ) {
            $encodingFormat = null;
//            $encodingFormat = $this->encodingFormat();
            if (!empty($encodingFormat)) {
                $audio_arr['encodingFormat'] = $encodingFormat;
            } else {
                unset($audio_arr['encodingFormat']);
            }
        }

        /**
         * Audio schema duration key
         */
        if ( isset( $audio_arr['duration'] ) ) {
            $duration = null;
//            $duration = $this->duration();
            if (!empty($duration)) {
                $audio_arr['duration'] = $duration;
            } else {
                unset($audio_arr['duration']);
            }
        }

        /**
         * Audio schema datePublished key
         */
        if ( isset( $audio_arr['datePublished'] ) ) {
            $datePublished = null;
//            $datePublished = $this->datePublished();
            if (!empty($datePublished)) {
                $audio_arr['datePublished'] = $datePublished;
            } else {
                unset( $audio_arr['datePublished'] );
            }
        }

        /**
         * Audio schema interactionStatistic key
         */
        if ( isset( $audio_arr['interactionStatistic'] ) ) {
            $interactionStatistic = null;
//            $interactionStatistic = $this->interactionStatistic();
            if (!empty($interactionStatistic)) {
                $audio_arr['interactionStatistic'] = $interactionStatistic;
            } else {
                unset($audio_arr['interactionStatistic']);
            }
        }

        /**
         * Audio schema author key
         */
        if ( isset( $audio_arr['author'] ) ) {
            $author = null;
//            $author = $this->author();
            if ( !empty( $author ) ) {
                $audio_arr['author'] = $author;
            } else {
                unset( $audio_arr['author'] );
            }
        }

        /**
         * Audio schema publisher key
         */
        if ( isset( $audio_arr['publisher'] ) ) {
            $publisher = null;
//            $publisher = $this->publisher();
            if (!empty($publisher)) {
                $audio_arr['publisher'] = $publisher;
            } else {
                unset($audio_arr['publisher']);
            }
        }

        /**
         * Audio schema inLanguage key
         */
        if ( isset( $audio_arr['inLanguage'] ) ) {
            $inLanguage = $this->inLanguage();
            if ( !empty( $inLanguage ) ) {
                $audio_arr['inLanguage'] = $inLanguage;
            } else {
                unset( $audio_arr['inLanguage'] );
            }
        }

        /**
         * Audio schema keywords key
         */
        if ( isset( $audio_arr['keywords'] ) ) {
            $keywords = null;
//            $keywords = $this->keywords();
            if (!empty($keywords)) {
                $audio_arr['keywords'] = $keywords;
            } else {
                unset($audio_arr['keywords']);
            }
        }

        /**
         * Audio schema license key
         */
        if (isset($audio_arr['license'])) {
            $license = null;
//            $license = $this->license();
            if (!empty($license)) {
                $audio_arr['license'] = $license;
            } else {
                unset($audio_arr['license']);
            }
        }

        /**
         * Audio schema isAccessibleForFree key
         */
        if (isset($audio_arr['isAccessibleForFree'])) {
            $isAccessibleForFree = null;
//            $isAccessibleForFree = $this->isAccessibleForFree();
            if (!empty($isAccessibleForFree)) {
                $audio_arr['isAccessibleForFree'] = $isAccessibleForFree;
            } else {
                unset($audio_arr['isAccessibleForFree']);
            }
        }

        /**
         * Audio schema transcript key
         */
        if (isset($audio_arr['transcript'])) {
            $transcript = null;
//            $transcript = $this->transcript();
            if (!empty($transcript)) {
                $audio_arr['transcript'] = $transcript;
            } else {
                unset($audio_arr['transcript']);
            }
        }

        /**
         * Audio schema mainEntityOfPage key
         */
        if (isset($audio_arr['mainEntityOfPage'])) {
            $mainEntityOfPage = null;
//            $mainEntityOfPage = $this->mainEntityOfPage();
            if (!empty($mainEntityOfPage)) {
                $audio_arr['mainEntityOfPage'] = $mainEntityOfPage;
            } else {
                unset($audio_arr['mainEntityOfPage']);
            }
        }

        return apply_filters("schemax_{$this->schema_type}_single_audio", $audio_arr );
    }

    /**
     * Get name
     *
     * @return mixed|null
     */
    protected function name() {
        $name = get_the_title( $this->post_id );

        if ( ! empty( $name ) ) {
            return apply_filters("schemax_{$this->schema_type}_name", $name );
        }
        return null;
    }

    /**
     * Get Description
     *
     * @return mixed|void|null
     */
    protected function description() {

        $description = get_the_content( $this->post_id );

        if (! empty( $description ) ) {
            return apply_filters("schemax_{$this->schema_type}_description", $description);
        }
    }

    /**
     * Get ContentUrl
     *
     * @return mixed|void|null
     */
    protected function contentUrl() {
        $contentUrl = $this->audio_links[0];
        return !empty( $contentUrl ) ? apply_filters("schemax_{$this->schema_type}_contentUrl", $contentUrl) : null;
    }

    /**
     * Get additionalContentUrls
     *
     * @return mixed|null
     */
    protected function additionalContentUrls() {
        $additionalContentUrls = $this->audio_links;
        return !empty( $additionalContentUrls ) ? apply_filters("schemax_{$this->schema_type}_additionalContentUrls", $additionalContentUrls) : null;
    }

    protected function inLanguage() {

        $inLanguage = get_bloginfo('language');

        return $inLanguage;
    }

}