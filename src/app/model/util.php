<?php
/**
 * UTrackIt - tracking technologies tutorial
 * -------------------------------------------
 * 
 * Utilities
 *  
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */
 
/**
 * Return class="active", if this is the active page
 */
function classActive($page, $name) {
    if ($page==$name) 
        return 'class="active"';
    else
        return '';
}

/**
 *  For servers that use an IIS server with no URL rewrite capabilities...
 *  Rewrite a URL as ?q= query param if needed.
 */
function rewriteURL($path) {
    $path = (USE_QUERY_PATH) ? '?q='.$path : '/' . $path; 
    return $path;   
} 

/**
 * Add a GET URL parameter in appropriate form
 * @to-do track # of parameter so it handles 1st, 2nd parameters right
 */
 function urlParam($param) {
    $param = (USE_QUERY_PATH) ? '&'.$param : '?' . $param; 
    return $param;   
 }
 
/**
 * Return a formatted HTML string with the trackers submenu as a series of <li> elements
 * If $page is set, that link will be given an 'active' class.
 */
function trackerMenu($page='') {
    global $TRACKERS;

    $output = '';
    foreach ($TRACKERS as $tracker => $anchor) {
        $path = rewriteURL($anchor['path']);
       
        $output .= '<li ' . classActive($page, $tracker) . '>' . PHP_EOL;
        $output .= '   <a href="'. $path .'" title="Tracker: '. $anchor['name'] .'">' . $anchor['name'] . '</a>' . PHP_EOL;
        $output .= '</li>' . PHP_EOL;
    }
    return $output;   
}

/**
 * Return a formatted HTML string with the trackers DB submenu as a series of <li> elements
 * If $page is set, that link will be given an 'active' class.
 */
function trackerDBMenu() {
    global $TRACKERS;

    $output = '';
    foreach ($TRACKERS as $tracker => $anchor) {
        $path = HACKERS_URL."?track_type=".$anchor['track_type'];
        $output .= '<li>' . PHP_EOL;
        $output .= '   <a href="'. $path .'" title="Tracker: '. $anchor['name'] .'">' . $anchor['name'] . ' DB</a>' . PHP_EOL;
        $output .= '</li>' . PHP_EOL;
    }
    return $output;
}

/**
 * Redirect to given url.
 */ 
function Redirect($url = '/', $permanent = FALSE)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, TRUE, ($permanent === TRUE) ? 301 : 303);
    }

    exit();
}

/**
 * The Request URI without any query parameters
 */
function RequestURI($with_query=false)
{
    $uri = $_SERVER["REQUEST_URI"];
    if (! $with_query) {
        $uri = strtok($uri,'?');
    }
    return $uri;
}

/**
 * Redirect back to referer - usually after processing a form.
 */ 
function RedirectBack($default_url = '/')
{
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $current = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $server  = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
    // Only go back if that doesn't cause recursion and "back" is still on this site.
    if ( $referer &&
         basename($referer) == basename($current) ) //&& parse_url( $referer, PHP_URL_HOST ) == $server )
        $url = $referer;
    else
        $url = $default_url;
    Redirect($url, FALSE);
}

/**
 * Obtain the site base absolute URL
 */
function siteURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['SERVER_NAME'].'/';
    return $protocol.$domainName;
}
/**
 * Compute an absolute URL from a relative path
 */
function rel2abs( $rel, $base=null ) {

    $base = $base?$base:siteURL();

    // parse base URL  and convert to local variables: $scheme, $host,  $path
    extract( parse_url( $base ) );

    if ( strpos( $rel,"//" ) === 0 ) {
        return $scheme . ':' . $rel;
    }

    // return if already absolute URL
    if ( parse_url( $rel, PHP_URL_SCHEME ) != '' ) {
        return $rel;
    }

    // queries and anchors
    if ( $rel[0] == '#' || $rel[0] == '?' ) {
        return $base . $rel;
    }

    // remove non-directory element from path
    $path = preg_replace( '#/[^/]*$#', '', $path );

    // destroy path if relative url points to root
    if ( $rel[0] ==  '/' ) {
        $path = '';
    }

    // dirty absolute URL
    $abs = $host . $path . "/" . $rel;

    // replace '//' or  '/./' or '/foo/../' with '/'
    $abs = preg_replace( "/(\/\.?\/)/", "/", $abs );
    $abs = preg_replace( "/\/(?!\.\.)[^\/]+\/\.\.\//", "/", $abs );

    // absolute URL is ready!
    return $scheme . '://' . $abs;
}