
/**
 * Audio schema name key
 */
if (isset($article_arr['name'])) {
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
if (isset($article_arr['description'])) {
    $description = $this->description();
    if (!empty($description)) {
        $article_arr['description'] = $description;
    } else {
        unset($article_arr['description']);
    }
}

/**
 * Audio schema contentUrl key
 */
if (isset($article_arr['contentUrl'])) {
    $contentUrl = $this->contentUrl();
    if (!empty($contentUrl)) {
        $article_arr['contentUrl'] = $contentUrl;
    } else {
        unset($article_arr['contentUrl']);
    }
}

/**
 * Audio schema encodingFormat key
 */
if (isset($article_arr['encodingFormat'])) {
    $encodingFormat = $this->encodingFormat();
    if (!empty($encodingFormat)) {
        $article_arr['encodingFormat'] = $encodingFormat;
    } else {
        unset($article_arr['encodingFormat']);
    }
}

/**
 * Audio schema duration key
 */
if (isset($article_arr['duration'])) {
    $duration = $this->duration();
    if (!empty($duration)) {
        $article_arr['duration'] = $duration;
    } else {
        unset($article_arr['duration']);
    }
}

/**
 * Audio schema datePublished key
 */
if (isset($article_arr['datePublished'])) {
    $datePublished = $this->datePublished();
    if (!empty($datePublished)) {
        $article_arr['datePublished'] = $datePublished;
    } else {
        unset($article_arr['datePublished']);
    }
}

/**
 * Audio schema interactionStatistic key
 */
if (isset($article_arr['interactionStatistic'])) {
    $interactionStatistic = $this->interactionStatistic();
    if (!empty($interactionStatistic)) {
        $article_arr['interactionStatistic'] = $interactionStatistic;
    } else {
        unset($article_arr['interactionStatistic']);
    }
}

/**
 * Audio schema author key
 */
if (isset($article_arr['author'])) {
    $author = $this->author();
    if (!empty($author)) {
        $article_arr['author'] = $author;
    } else {
        unset($article_arr['author']);
    }
}

/**
 * Audio schema publisher key
 */
if (isset($article_arr['publisher'])) {
    $publisher = $this->publisher();
    if (!empty($publisher)) {
        $article_arr['publisher'] = $publisher;
    } else {
        unset($article_arr['publisher']);
    }
}

/**
 * Audio schema inLanguage key
 */
if (isset($article_arr['inLanguage'])) {
    $inLanguage = $this->inLanguage();
    if (!empty($inLanguage)) {
        $article_arr['inLanguage'] = $inLanguage;
    } else {
        unset($article_arr['inLanguage']);
    }
}

/**
 * Audio schema keywords key
 */
if (isset($article_arr['keywords'])) {
    $keywords = $this->keywords();
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
    $license = $this->license();
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
    $isAccessibleForFree = $this->isAccessibleForFree();
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
    $transcript = $this->transcript();
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
    $mainEntityOfPage = $this->mainEntityOfPage();
    if (!empty($mainEntityOfPage)) {
        $article_arr['mainEntityOfPage'] = $mainEntityOfPage;
    } else {
        unset($article_arr['mainEntityOfPage']);
    }
}

