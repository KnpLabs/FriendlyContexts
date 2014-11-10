<?php

namespace Knp\FriendlyExtension\Http;

class HttpContentTypeGuesser
{
    private static $contentTypeTable = [
        // application and text types
        'json'       => 'application/json',
        'javascript' => ['application/javascript', 'text/javascript'],
        'xml'        => ['application/xml', 'text/xml'],
        'rss'        => 'application/rss',
        'pdf'        => 'application/pdf',
        'soap'       => 'application/soap+xml',
        'atom'       => 'application/atom+xml',
        'stream'     => 'application/octet-stream',
        'html'       => ['text/html', 'application/xhtml+xml'],
        'xhtml'      => ['application/xhtml+xml', 'text/html'],
        'zip'        => 'application/zip',
        'gzip'       => 'application/gzip',
        'font'       => 'application/font-woff',
        'dtd'        => 'applciation/xml-dtd',
        'ecmascript' => 'application/ecmascript',
        'postscript' => 'application/postscript',
        'cmd'        => 'text/cmd',
        'css'        => 'text/css',
        'csv'        => 'text/csv',
        'plaintext'  => 'text/plain',
        'text'       => 'text/plain',
        'rtf'        => 'text/rtf',
        'vcard'      => 'text/vcard',
        'abc'        => 'text/vnd.abc',
        // audio and video types
        'ogg'        => ['application/ogg', 'audio/ogg', 'video/ogg'],
        'l24'        => 'audio/L24',
        'mp4'        => ['audio/mp4', 'video/mp4'],
        'mpeg'       => ['audio/mpeg', 'video/mpeg'],
        'opus'       => 'audio/opus',
        'vorbis'     => 'audio/vorbis',
        'realaudio'  => 'audio/vnd.rn-realaudio',
        'wave'       => 'audio/vnd.wave',
        'webm'       => ['audio/webm', 'video/webm'],
        'avi'        => 'video/avi',
        'quicktime'  => 'video/quicktime',
        'wmv'        => 'video/x-ms-wmv',
        'matroska'   => 'video/x-matroska',
        'flv'        => 'video/x-flv',
        // image types
        'gif'        => 'image/gif',
        'jpeg'       => 'image/jpeg',
        'pjpeg'      => 'image/pjpeg',
        'png'        => 'image/png',
        'svg'        => 'image/svg+xml',
    ];

    public function guess($shortType)
    {
        $shortType = strtolower($shortType);

        if (!isset(self::$contentTypeTable[$shortType])) {
            throw new \InvalidArgumentException(sprintf(
                'No short content type has been found for "%s"',
                $shortType
            ));
        }

        if (!is_array(self::$contentTypeTable[$shortType])) {
            return [self::$contentTypeTable[$shortType]];
        }

        return self::$contentTypeTable[$shortType];
    }

    public function exists($shortType)
    {
        $shortType = strtolower($shortType);

        return isset(self::$contentTypeTable[$shortType]);
    }

    public function getKey($contentType)
    {
        foreach (self::$contentTypeTable as $key => $types) {
            $types = is_array($types) ? $types : [$types];

            foreach ($types as $type) {
                if (strtolower($type) === strtolower($contentType)) {

                    return $key;
                }
            }
        }

        return false;
    }
}
