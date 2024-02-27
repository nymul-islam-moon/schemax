<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;

class Video extends BaseEngine {

    private $post_id, $video_links;

    public function __construct( $video_links, $post_id = null ) {

        $this->schema_file      = 'video.json';
        parent::__construct();

        $this->schema_type      = 'video';
        $this->post_id          = $post_id;
        $this->video_links      = $video_links;
    }

    /**
     * Update the schema data
     *
     * @return mixed|null
     */
    protected function update_schema() {
        $this->schema           = json_encode( $this->single_video( $this->schema_structure ) );

        return apply_filters( "schemax_{$this->schema_type}_update_schema", $this->schema );
    }

    /**
     * Update the single video data
     * @param $video_arr
     * @return mixed|null
     */
    protected function single_video( $video_arr ) {

        /**
         * Video schema name key
         */
        if (isset($video_arr['name'])) {
            $name                                           = $this->name();
            if ( ! empty( $name ) ) {
                $video_arr['name']                          = $name;
            } else {
                unset( $video_arr['name'] );
            }
        }

        /**
         * Video schema description key
         */
        if (isset($video_arr['description'])) {
            $description                                    = $this->description();
            if (!empty($description)) {
                $video_arr['description']                 = $description;
            } else {
                unset($video_arr['description']);
            }
        }

        /**
         * Video schema thumbnailUrl key
         */
        if (isset($video_arr['thumbnailUrl'])) {
            $thumbnailUrl                                   = $this->thumbnailUrl();
            if (!empty($thumbnailUrl)) {
                $video_arr['thumbnailUrl']                = $thumbnailUrl;
            } else {
                unset($video_arr['thumbnailUrl']);
            }
        }

        /**
         * Video schema uploadDate key
         */
        if (isset($video_arr['uploadDate'])) {
            $uploadDate                                     = $this->uploadDate();
            if (!empty($uploadDate)) {
                $video_arr['uploadDate']                  = $uploadDate;
            } else {
                unset($video_arr['uploadDate']);
            }
        }

        /**
         * Video schema duration key
         */
        if (isset($video_arr['duration'])) {
            $duration                                       = $this->duration();
            if (!empty($duration)) {
                $video_arr['duration']                    = $duration;
            } else {
                unset($video_arr['duration']);
            }
        }

        /**
         * Video schema contentUrl key
         */
        if (isset($video_arr['contentUrl'])) {
            $contentUrl                                     = $this->contentUrl();
            if (!empty($contentUrl)) {
                $video_arr['contentUrl']                  = $contentUrl;
            } else {
                unset($video_arr['contentUrl']);
            }
        }

        /**
         * Video schema embedUrl key
         */
        if (isset($video_arr['embedUrl'])) {
            $embedUrl                                       = $this->embedUrl();
            if (!empty($embedUrl)) {
                $video_arr['embedUrl']                    = $embedUrl;
            } else {
                unset($video_arr['embedUrl']);
            }
        }

        /**
         * Video schema interactionStatistic key
         */
        if (isset($video_arr['interactionStatistic'])) {
            $interactionStatistic                           = $this->interactionStatistic();
            if (!empty($interactionStatistic)) {
                $video_arr['interactionStatistic']        = $interactionStatistic;
            } else {
                unset($video_arr['interactionStatistic']);
            }
        }

        /**
         * Video schema regionsAllowed key
         */
        if (isset($video_arr['regionsAllowed'])) {
            $regionsAllowed                                 = $this->regionsAllowed();
            if (!empty($regionsAllowed)) {
                $video_arr['regionsAllowed']              = $regionsAllowed;
            } else {
                unset($video_arr['regionsAllowed']);
            }
        }

        /**
         * Video schema author key
         */
        if (isset($video_arr['author'])) {
            $author                                         = $this->author();
            if (!empty($author)) {
                $video_arr['author']                      = $author;
            } else {
                unset($video_arr['author']);
            }
        }

        /**
         * Video schema commentCount key
         */
        if (isset($video_arr['commentCount'])) {
            $commentCount                                   = $this->commentCount();
            if (!empty($commentCount)) {
                $video_arr['commentCount']                = $commentCount;
            } else {
                unset($video_arr['commentCount']);
            }
        }

        /**
         * Video schema interactionCount key
         */
        if (isset($video_arr['interactionCount'])) {
            $interactionCount                               = $this->interactionCount();
            if (!empty($interactionCount)) {
                $video_arr['interactionCount']            = $interactionCount;
            } else {
                unset($video_arr['interactionCount']);
            }
        }

        /**
         * Video schema dateModified key
         */
        if ( isset( $video_arr['dateModified'] ) ) {
            $dateModified                                   = $this->dateModified();
            if (!empty($dateModified)) {
                $video_arr['dateModified']                = $dateModified;
            } else {
                unset( $video_arr['dateModified'] );
            }
        }

        /**
         * Video schema datePublished key
         */
        if ( isset( $video_arr['datePublished'] ) ) {
            $datePublished                                  = $this->datePublished();
            if (!empty($datePublished)) {
                $video_arr['datePublished']                 = $datePublished;
            } else {
                unset($video_arr['datePublished']);
            }
        }

        /**
         * Video schema mainEntityOfPage key
         */
        if (isset($video_arr['mainEntityOfPage'])) {
            $mainEntityOfPage                               = $this->mainEntityOfPage();
            if (!empty($mainEntityOfPage)) {
                $video_arr['mainEntityOfPage']              = $mainEntityOfPage;
            } else {
                unset($video_arr['mainEntityOfPage']);
            }
        }

        /**
         * Video schema videoQuality key
         */
        if (isset($video_arr['videoQuality'])) {
            $videoQuality                                   = $this->videoQuality();
            if (!empty($videoQuality)) {
                $video_arr['videoQuality']                  = $videoQuality;
            } else {
                unset($video_arr['videoQuality']);
            }
        }

        /**
         * Video schema transcript key
         */
        if (isset($video_arr['transcript'])) {
            $transcript                                     = $this->transcript();
            if (!empty($transcript)) {
                $video_arr['transcript']                    = $transcript;
            } else {
                unset($video_arr['transcript']);
            }
        }

        /**
         * Video schema genre key
         */
        if (isset($video_arr['genre'])) {
            $genre                                          = $this->genre();
            if (!empty($genre)) {
                $video_arr['genre']                         = $genre;
            } else {
                unset($video_arr['genre']);
            }
        }

        /**
         * Video schema keywords key
         */
        if (isset($video_arr['keywords'])) {
            $keywords                                       = $this->keywords();
            if (!empty($keywords)) {
                $video_arr['keywords']                      = $keywords;
            } else {
                unset($video_arr['keywords']);
            }
        }

        /**
         * Video schema isFamilyFriendly key
         */
        if (isset($video_arr['isFamilyFriendly'])) {
            $isFamilyFriendly                               = $this->isFamilyFriendly();
            if (!empty($isFamilyFriendly)) {
                $video_arr['isFamilyFriendly']              = $isFamilyFriendly;
            } else {
                unset($video_arr['isFamilyFriendly']);
            }
        }

        /**
         * Video schema requiresSubscription key
         */
        if (isset($video_arr['requiresSubscription'])) {
            $requiresSubscription                           = $this->requiresSubscription();
            if (!empty($requiresSubscription)) {
                $video_arr['requiresSubscription']          = $requiresSubscription;
            } else {
                unset($video_arr['requiresSubscription']);
            }
        }

        /**
         * Video schema publisher key
         */
        if (isset($video_arr['publisher'])) {
            $publisher                                      = $this->publisher();
            if (!empty($publisher)) {
                $video_arr['publisher']                     = $publisher;
            } else {
                unset($video_arr['publisher']);
            }
        }

        /**
         * Video schema license key
         */
        if (isset($video_arr['license'])) {
            $license                                        = $this->license();
            if (!empty( $license ) ) {
                $video_arr['license']                     = $license;
            } else {
                unset( $video_arr['license'] );
            }
        }

        /**
         * Video schema comment key
         */
        if ( isset( $video_arr['comment'] ) ) {
            $comment                                        = $this->comment();
            if ( ! empty( $comment ) ) {
                $video_arr['comment']                     = $comment;
            } else {
                unset( $video_arr['comment'] );
            }
        }

        return apply_filters("schemax_{$this->schema_type}_single_video", $video_arr );
    }

    /**
     * Get name
     *
     * @return mixed|void|null
     */
    protected function name() {
        $name = get_the_title( $this->post_id );

        if ( ! empty( $name ) ) {
            return apply_filters("schemax_{$this->schema_type}_name", $name );
        }
    }

    /**
     * Get Description
     *
     * @return mixed|void|null
     */
    protected function description() {
        return null;
        $description = get_the_title($this->post_id);

        if (!empty($description)) {
            return apply_filters("schemax_{$this->schema_type}_description", $description);
        }
    }

    /**
     * Get ThumbnailUrl
     *
     * @return mixed|void|null
     */
    protected function thumbnailUrl() {
        return null;
        $thumbnailUrl = get_the_title($this->post_id);

        if (!empty($thumbnailUrl)) {
            return apply_filters("schemax_{$this->schema_type}_thumbnailUrl", $thumbnailUrl);
        }
    }

    /**
     * Get UploadDate
     *
     * @return mixed|void|null
     */
    protected function uploadDate() {
        return null;
        $uploadDate = get_the_title($this->post_id);

        if (!empty($uploadDate)) {
            return apply_filters("schemax_{$this->schema_type}_uploadDate", $uploadDate);
        }
    }

    /**
     * Get Duration
     *
     * @return mixed|void|null
     */
    protected function duration() {
        return null;
        $duration = get_the_title($this->post_id);

        if (!empty($duration)) {
            return apply_filters("schemax_{$this->schema_type}_duration", $duration);
        }
    }

    /**
     * Get ContentUrl
     *
     * @return mixed|void|null
     */
    protected function contentUrl() {
        return null;
        $contentUrl = get_the_title($this->post_id);

        if (!empty($contentUrl)) {
            return apply_filters("schemax_{$this->schema_type}_contentUrl", $contentUrl);
        }
    }

    /**
     * Get EmbedUrl
     *
     * @return mixed|void|null
     */
    protected function embedUrl() {
        return null;
        $embedUrl = get_the_title($this->post_id);

        if (!empty($embedUrl)) {
            return apply_filters("schemax_{$this->schema_type}_embedUrl", $embedUrl);
        }
    }

    /**
     * Get InteractionStatistic
     *
     * @return mixed|void|null
     */
    protected function interactionStatistic() {
        return null;
        $interactionStatistic = get_the_title($this->post_id);

        if (!empty($interactionStatistic)) {
            return apply_filters("schemax_{$this->schema_type}_interactionStatistic", $interactionStatistic);
        }
    }

    /**
     * Get RegionsAllowed
     *
     * @return mixed|void|null
     */
    protected function regionsAllowed() {
        return null;
        $regionsAllowed = get_the_title($this->post_id);

        if (!empty($regionsAllowed)) {
            return apply_filters("schemax_{$this->schema_type}_regionsAllowed", $regionsAllowed);
        }
    }

    /**
     * Get Author
     *
     * @return mixed|void|null
     */
    protected function author() {
        return null;
        $author = get_the_title($this->post_id);

        if (!empty($author)) {
            return apply_filters("schemax_{$this->schema_type}_author", $author);
        }
    }

    /**
     * Get CommentCount
     *
     * @return mixed|void|null
     */
    protected function commentCount() {
        return null;
        $commentCount = get_the_title($this->post_id);

        if (!empty($commentCount)) {
            return apply_filters("schemax_{$this->schema_type}_commentCount", $commentCount);
        }
    }

    /**
     * Get InteractionCount
     *
     * @return mixed|void|null
     */
    protected function interactionCount() {
        return null;
        $interactionCount = get_the_title($this->post_id);

        if (!empty($interactionCount)) {
            return apply_filters("schemax_{$this->schema_type}_interactionCount", $interactionCount);
        }
    }

    /**
     * Get DateModified
     *
     * @return mixed|void|null
     */
    protected function dateModified() {
        return null;
        $dateModified = get_the_title($this->post_id);

        if (!empty($dateModified)) {
            return apply_filters("schemax_{$this->schema_type}_dateModified", $dateModified);
        }
    }

    /**
     * Get DatePublished
     *
     * @return mixed|void|null
     */
    protected function datePublished() {
        return null;
        $datePublished = get_the_title($this->post_id);

        if (!empty($datePublished)) {
            return apply_filters("schemax_{$this->schema_type}_datePublished", $datePublished);
        }
    }

    /**
     * Get MainEntityOfPage
     *
     * @return mixed|void|null
     */
    protected function mainEntityOfPage() {
        return null;
        $mainEntityOfPage = get_the_title($this->post_id);

        if (!empty($mainEntityOfPage)) {
            return apply_filters("schemax_{$this->schema_type}_mainEntityOfPage", $mainEntityOfPage);
        }
    }

    /**
     * Get VideoQuality
     *
     * @return mixed|void|null
     */
    protected function videoQuality() {
        return null;
        $videoQuality = get_the_title($this->post_id);

        if (!empty($videoQuality)) {
            return apply_filters("schemax_{$this->schema_type}_videoQuality", $videoQuality);
        }
    }

    /**
     * Get Transcript
     *
     * @return mixed|void|null
     */
    protected function transcript() {
        return null;
        $transcript = get_the_title($this->post_id);

        if (!empty($transcript)) {
            return apply_filters("schemax_{$this->schema_type}_transcript", $transcript);
        }
    }

    /**
     * Get Genre
     *
     * @return mixed|void|null
     */
    protected function genre() {
        return null;
        $genre = get_the_title($this->post_id);

        if (!empty($genre)) {
            return apply_filters("schemax_{$this->schema_type}_genre", $genre);
        }
    }

    /**
     * Get Keywords
     *
     * @return mixed|void|null
     */
    protected function keywords() {
        return null;
        $keywords = get_the_title($this->post_id);

        if (!empty($keywords)) {
            return apply_filters("schemax_{$this->schema_type}_keywords", $keywords);
        }
    }

    /**
     * Get IsFamilyFriendly
     *
     * @return mixed|void|null
     */
    protected function isFamilyFriendly() {
        return null;
        $isFamilyFriendly = get_the_title($this->post_id);

        if (!empty($isFamilyFriendly)) {
            return apply_filters("schemax_{$this->schema_type}_isFamilyFriendly", $isFamilyFriendly);
        }
    }

    /**
     * Get RequiresSubscription
     *
     * @return mixed|void|null
     */
    protected function requiresSubscription() {
        return null;
        $requiresSubscription = get_the_title($this->post_id);

        if (!empty($requiresSubscription)) {
            return apply_filters("schemax_{$this->schema_type}_requiresSubscription", $requiresSubscription);
        }
    }

    /**
     * Get Publisher
     *
     * @return mixed|void|null
     */
    protected function publisher() {
        return null;
        $publisher = get_the_title($this->post_id);

        if (!empty($publisher)) {
            return apply_filters("schemax_{$this->schema_type}_publisher", $publisher);
        }
    }

    /**
     * Get License
     *
     * @return mixed|void|null
     */
    protected function license() {
        return null;
        $license = get_the_title($this->post_id);

        if (!empty($license)) {
            return apply_filters("schemax_{$this->schema_type}_license", $license);
        }
    }

    /**
     * Get Comment
     *
     * @return mixed|void|null
     */
    protected function comment() {
        return null;
        $comment = get_the_title($this->post_id);

        if (!empty($comment)) {
            return apply_filters("schemax_{$this->schema_type}_comment", $comment);
        }
    }

}