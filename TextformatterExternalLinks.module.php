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

  /**
   * Format
   * loop trough links array and replace old link with new one
   */
  public function format(&$value) {
    $links = $this->find_external_links($value);
    if (!$links) return $value;
    foreach ($links as $link) {
      $value = str_replace($link[0], $link[1], $value);
    }
  }

  /**
   * Find all external links
   * saved them in array as [$old_link => $new_link]
   */
  public function find_external_links($value) {
    // find all external links using regex in $value
    $pattern = '/<a\s+(?![^>]*\btarget=["\']_blank["\'])[^>]*\bhref=["\'](https?:\/\/[^"\']+)["\'][^>]*>/i';
    // $pattern = '/<a[^>]*\bhref=["\'](https?:\/\/[^"\']+)["\'](?!.*\btarget=["\']_blank["\'])[^\r\n<>]*<\/a>/i';

    preg_match_all($pattern, $value, $matches);
    if (empty($matches[0])) return;
    $links = [];
    $replace = "<a target='_blank' rel='nofollow noopener noreferrer'";
    foreach ($matches[0] as $match) {
      // ifmatch starts with <a
      if (substr($match, 0, 2) == "<a") {
        $links[] = [
          0 => $match,
          1 => str_replace("<a", $replace, $match),
        ];
      }
    }
    return $links;
  }
}
