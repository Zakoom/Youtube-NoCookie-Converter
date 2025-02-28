<?php
if (!defined('ABSPATH')) { exit; }

/*
Plugin Name: YouTube NoCookie Converter
Description: Ersetzt youtube.com mit youtube-nocookie.com in eingebundenen Videos
Version: 1.0
Author: Björn Block, Grok
Author URI: https://www.bjoernblock.de
License:     GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html


Dieses Plugin ist freie Software: Sie können es unter den Bedingungen der
GNU General Public License, Version 3 oder später weitergeben und/oder ändern.

Dieses Plugin wird in der Hoffnung verteilt, dass es nützlich ist, aber
OHNE JEGLICHE GARANTIE, auch ohne die implizite Garantie der
MARKTGÄNGIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
Siehe die GNU General Public License für weitere Details.

Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
Plugin erhalten haben. Falls nicht, siehe <https://www.gnu.org/licenses/>.
*/

function ytnc_replace_youtube_urls($content) {
    // Alle notwendigen Patterns, ggfl. ergänzen
    $patterns = array(
        // Standard iframe
        '/src=["\'](https?:\/\/)(www\.)?youtube\.com\/embed\/([^"\']*)["\']/i',
        // Shorturl
        '/src=["\'](https?:\/\/)(www\.)?youtu\.be\/([^"\']*)["\']/i',
        // Ohne embed-link, meist als direkte Verlinkung
        '/(https?:\/\/)(www\.)?youtube\.com\/watch\?v=([^"\s]*)["\']?/i'
    );
    
    // Links durch die ersetzt wird, Reihenfolge wie einen Block drüber
    $replacements = array(
        'src="$1www.youtube-nocookie.com/embed/$3"',
        'src="$1www.youtube-nocookie.com/embed/$3"',
        '$1www.youtube-nocookie.com/embed/$3'
    );
    
    // Ersetzen ausführen
    $content = preg_replace($patterns, $replacements, $content);
    
    return $content;
}

// Filter setzen für content und oembed
add_filter('the_content', 'ytnc_replace_youtube_urls');
add_filter('embed_oembed_html', 'ytnc_replace_youtube_urls', 99, 4);

// Optional: shortcodes ersetzen
function ytnc_filter_shortcodes($output) {
    return ytnc_replace_youtube_urls($output);
}
add_filter('do_shortcode_tag', 'ytnc_filter_shortcodes');
