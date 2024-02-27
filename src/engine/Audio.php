<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;

class Audio extends BaseEngine {

    private $audio_links, $post_id;
    public function __construct( $audio_links, $post_id = null ) {
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
        if ( isset( $article_arr['name'] ) ) {
            $name = $this->name();
            if (!empty($name)) {
                $article_arr['name'] = $name;
            } else {
                unset($article_arr['name']);
            }
        }

        /**
         * Audio schema description key
         */
        if ( isset( $article_arr['description'] ) ) {
            $description = null;
//            $description = $this->description();
            if (!empty($description)) {
                $article_arr['description'] = $description;
            } else {
                unset($article_arr['description']);
            }
        }

        /**
         * Audio schema contentUrl key
         */
        if ( isset( $article_arr['contentUrl'] ) ) {
            $contentUrl = null;
//            $contentUrl = $this->contentUrl();
            if (!empty($contentUrl)) {
                $article_arr['contentUrl'] = $contentUrl;
            } else {
                unset($article_arr['contentUrl']);
            }
        }

        /**
         * Audio schema encodingFormat key
         */
        if ( isset( $article_arr['encodingFormat'] ) ) {
            $encodingFormat = null;
//            $encodingFormat = $this->encodingFormat();
            if (!empty($encodingFormat)) {
                $article_arr['encodingFormat'] = $encodingFormat;
            } else {
                unset($article_arr['encodingFormat']);
            }
        }

        /**
         * Audio schema duration key
         */
        if ( isset( $article_arr['duration'] ) ) {
            $duration = null;
//            $duration = $this->duration();
            if (!empty($duration)) {
                $article_arr['duration'] = $duration;
            } else {
                unset($article_arr['duration']);
            }
        }

        /**
         * Audio schema datePublished key
         */
        if ( isset( $article_arr['datePublished'] ) ) {
            $datePublished = null;
//            $datePublished = $this->datePublished();
            if (!empty($datePublished)) {
                $article_arr['datePublished'] = $datePublished;
            } else {
                unset( $article_arr['datePublished'] );
            }
        }

        /**
         * Audio schema interactionStatistic key
         */
        if ( isset( $article_arr['interactionStatistic'] ) ) {
            $interactionStatistic = null;
//            $interactionStatistic = $this->interactionStatistic();
            if (!empty($interactionStatistic)) {
                $article_arr['interactionStatistic'] = $interactionStatistic;
            } else {
                unset($article_arr['interactionStatistic']);
            }
        }

        /**
         * Audio schema author key
         */
        if ( isset( $article_arr['author'] ) ) {
            $author = null;
//            $author = $this->author();
            if ( !empty( $author ) ) {
                $article_arr['author'] = $author;
            } else {
                unset( $article_arr['author'] );
            }
        }

        /**
         * Audio schema publisher key
         */
        if ( isset( $article_arr['publisher'] ) ) {
            $publisher = null;
//            $publisher = $this->publisher();
            if (!empty($publisher)) {
                $article_arr['publisher'] = $publisher;
            } else {
                unset($article_arr['publisher']);
            }
        }

        /**
         * Audio schema inLanguage key
         */
        if ( isset( $article_arr['inLanguage'] ) ) {
            $inLanguage = null;
//            $inLanguage = $this->inLanguage();
            if ( !empty( $inLanguage ) ) {
                $article_arr['inLanguage'] = $inLanguage;
            } else {
                unset( $article_arr['inLanguage'] );
            }
        }

        /**
         * Audio schema keywords key
         */
        if ( isset( $article_arr['keywords'] ) ) {
            $keywords = null;
//            $keywords = $this->keywords();
            if (!empty($keywords)) {
                $article_arr['keywords'] = $keywords;
            } else {
                unset($article_arr['keywords']);
            }
        }

        /**
         * Audio schema license key
         */
        if (isset($article_arr['license'])) {
            $license = null;
//            $license = $this->license();
            if (!empty($license)) {
                $article_arr['license'] = $license;
            } else {
                unset($article_arr['license']);
            }
        }

        /**
         * Audio schema isAccessibleForFree key
         */
        if (isset($article_arr['isAccessibleForFree'])) {
            $isAccessibleForFree = null;
//            $isAccessibleForFree = $this->isAccessibleForFree();
            if (!empty($isAccessibleForFree)) {
                $article_arr['isAccessibleForFree'] = $isAccessibleForFree;
            } else {
                unset($article_arr['isAccessibleForFree']);
            }
        }

        /**
         * Audio schema transcript key
         */
        if (isset($article_arr['transcript'])) {
            $transcript = null;
//            $transcript = $this->transcript();
            if (!empty($transcript)) {
                $article_arr['transcript'] = $transcript;
            } else {
                unset($article_arr['transcript']);
            }
        }

        /**
         * Audio schema mainEntityOfPage key
         */
        if (isset($article_arr['mainEntityOfPage'])) {
            $mainEntityOfPage = null;
//            $mainEntityOfPage = $this->mainEntityOfPage();
            if (!empty($mainEntityOfPage)) {
                $article_arr['mainEntityOfPage'] = $mainEntityOfPage;
            } else {
                unset($article_arr['mainEntityOfPage']);
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

}