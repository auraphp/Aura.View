<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View;

/**
 * 
 * Provides a mapping between .format file extensions and their Content-Type
 * values. Also handles negotiation between an array of Accept headers and a
 * list of available .format views to be rendered.
 * 
 * @package Aura.View
 * 
 */
class FormatTypes
{
    /**
     * 
     * An array of `.format` extensions to Content-Type mappings.
     * 
     * @var array
     * 
     */
    protected $map = [
        '.aif'      => 'audio/x-aiff',
        '.aifc'     => 'audio/x-aiff',
        '.aiff'     => 'audio/x-aiff',
        '.asf'      => 'video/x-ms-asf',
        '.asr'      => 'video/x-ms-asf',
        '.asx'      => 'video/x-ms-asf',
        '.atom'     => 'application/atom+xml',
        '.au'       => 'audio/basic',
        '.avi'      => 'video/x-msvideo',
        '.bas'      => 'text/plain',
        '.bmp'      => 'image/bmp',
        '.c'        => 'text/plain',
        '.css'      => 'text/css',
        '.csv'      => 'text/plain',
        '.dtd'      => 'application/xml-dtd',
        '.etx'      => 'text/x-setext',
        '.flr'      => 'x-world/x-vrml',
        '.gif'      => 'image/gif',
        '.gz'       => 'application/x-gzip',
        '.h'        => 'text/plain',
        '.htm'      => 'text/html',
        '.html'     => 'text/html',
        '.ico'      => 'image/x-icon',
        '.jfif'     => 'image/pipeg',
        '.jpe'      => 'image/jpeg',
        '.jpeg'     => 'image/jpeg',
        '.jpg'      => 'image/jpeg',
        '.js'       => 'text/javascript',
        '.json'     => 'application/json',
        '.lsf'      => 'video/x-la-asf',
        '.lsx'      => 'video/x-la-asf',
        '.m3u'      => 'audio/x-mpegurl',
        '.mid'      => 'audio/mid',
        '.mov'      => 'video/quicktime',
        '.movie'    => 'video/x-sgi-movie',
        '.mp2'      => 'video/mpeg',
        '.mp3'      => 'audio/mpeg',
        '.mpa'      => 'video/mpeg',
        '.mpe'      => 'video/mpeg',
        '.mpeg'     => 'video/mpeg',
        '.mpg'      => 'video/mpeg',
        '.mpv2'     => 'video/mpeg',
        '.ogg'      => 'application/ogg',
        '.pbm'      => 'image/x-portable-bitmap',
        '.pdf'      => 'application/pdf',
        '.pgm'      => 'image/x-portable-graymap',
        '.png'      => 'image/png',
        '.pnm'      => 'image/x-portable-anymap',
        '.ppm'      => 'image/x-portable-pixmap',
        '.ps'       => 'application/postscript',
        '.qt'       => 'video/quicktime',
        '.ra'       => 'audio/x-pn-realaudio',
        '.ram'      => 'audio/x-pn-realaudio',
        '.ras'      => 'image/x-cmu-raster',
        '.rdf'      => 'application/rdf+xml',
        '.rgb'      => 'image/x-rgb',
        '.rmi'      => 'audio/mid',
        '.rss'      => 'application/rss+xml',
        '.rss2'     => 'application/rss+xml',
        '.rtf'      => 'application/rtf',
        '.snd'      => 'audio/basic',
        '.svg'      => 'image/svg+xml',
        '.text'     => 'text/plain',
        '.tif'      => 'image/tiff',
        '.tiff'     => 'image/tiff',
        '.tsv'      => 'text/plain',
        '.txt'      => 'text/plain',
        '.vcf'      => 'text/x-vcard',
        '.vrml'     => 'x-world/x-vrml',
        '.wav'      => 'audio/x-wav',
        '.wrl'      => 'x-world/x-vrml',
        '.wrz'      => 'x-world/x-vrml',
        '.xaf'      => 'x-world/x-vrml',
        '.xbm'      => 'image/x-xbitmap',
        '.xht'      => 'application/xhtml+xml',
        '.xhtml'    => 'application/xhtml+xml',
        '.xml'      => 'application/xml',
        '.xof'      => 'x-world/x-vrml',
        '.xpm'      => 'image/x-xpixmap',
        '.xwd'      => 'image/x-xwindowdump',
        '.zip'      => 'application/zip',
    ];

    /**
     * 
     * Constructor.
     * 
     * @param array $map An array of additional or override .format mappings
     * to Content-Type values.
     * 
     */
    public function __construct(array $map = [])
    {
        $this->map = array_merge($this->map, $map);
    }

    /**
     * 
     * Given an array of acceptable Content-Type values and an array of 
     * available .format views, picks an acceptable .format view.
     * 
     * @param array $accept An array of acceptable Content-Type values.
     * 
     * @param array $formats An array of available .format views.
     * 
     * @return string An acceptable .format view matching one of the
     * Content-Type values.
     * 
     * @todo Handle '*' variations on $accept.
     * 
     */
    public function matchAcceptFormats(array $accept, array $formats)
    {
        foreach ($accept as $accept_type) {
            foreach ($formats as $format) {
                if (! isset($this->map[$format])) {
                    continue;
                }
                $format_type = $this->map[$format];
                if ($accept_type == $format_type) {
                    return $format;
                }
            }
        }
    }

    /**
     * 
     * Returns the Content-Type for a particular .format file extension.
     * 
     * @param string $format The .format file extension.
     * 
     * @return string The mapped Content-Type value.
     * 
     */
    public function getContentType($format)
    {
        if (isset($this->map[$format])) {
            return $this->map[$format];
        }
    }
}
