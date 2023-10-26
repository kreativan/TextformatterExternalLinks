<?php

/**
 * TextformatterExternalLinks
 * @author Ivan Milincic <hello@kreativan.dev>
 * @link http://www.kraetivan.dev
 */

namespace ProcessWire;

class TextformatterExternalLinks extends Textformatter implements ConfigurableModule {

  public static function getModuleInfo() {
    return array(
      'title' => 'Textformatter External Links',
      'version' => 100,
      'summary' => 'Add "_blank" and "nofollow" attributes to all external links',
      'author' => 'Ivan Milincic',
      'href' => 'https://github.com/kreativan/TextformatterExternalLinks',
    );
  }

  public function __construct() {
    parent::__construct();
  }

  public function format(&$value) {

    $links = $this->find_external_links($value);
    if (!$links) return $value;

    $replace = "<a target='_blank' rel='nofollow noopener noreferrer'";
    foreach ($links as $link) {
      $value = str_replace("<a", $replace, $value);
    }
  }

  public function find_external_links($value) {
    // find all external links using regex in $value
    $pattern = '/<a\s+(?![^>]*\btarget=["\']_blank["\'])[^>]*\bhref=["\'](https?:\/\/[^"\']+)["\'][^>]*>/i';
    preg_match_all($pattern, $value, $matches);
    if (empty($matches[0])) return;
    $links = [];
    foreach ($matches as $match) {
      // ifmatch starts with <a
      if (substr($match[0], 0, 2) == "<a") {
        $links[] = $match[0];
      }
    }
    return $links;
  }
}
