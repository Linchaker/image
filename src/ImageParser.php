<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;

class ImageParser implements Parser
{
    use ParserPatterns;

    protected const ALLOW_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    protected $content;
    protected $path;
    protected $host;
    protected $validAnchors = ['origin', 'default', 'main', 'оригинал'];

    /**
     * parse type of path
     * get image data
     * @param string $path image, link, or page with images
     * @return string
     */
    public function parse(string $path): string
    {
        $this->path = $path;

        if ($this->checkImageByExtension()) {
            return file_get_contents($path);
        }

        if (!$this->checkPathIsUrl()) {
            return 'false';
        }

        $contentType = $this->getContentTypesFromUrl();

        if ($this->checkImageByContentType($contentType)) {
            return $this->getImage();
        }

        // text page - try find image on the page
        if (count(preg_grep("/^text/", $contentType)) > 0) {
            $img = $this->parseOne($path);
            if ($img !== 'false') {
                return $this->getImage($img);
            }
        }

        return 'false';
    }

    /**
     * @return string image data
     */
    private function getImage($image = null): string
    {
        $image = $image ?? $this->path;

        return file_get_contents($image);
    }
    /**
     * check path for
     * @return bool true if is available image
     */
    private function checkImageByExtension(): bool
    {
        return in_array(substr($this->path, -3, 3), self::ALLOW_IMAGE_EXTENSIONS);
    }

    /**
     * check if path is image by MimeType from http Content-Type
     * @param $contentType
     * @return bool
     */
    private function checkImageByContentType($contentType): bool
    {
        $imagesMimeTypePattern = "~^image/(". implode('|', self::ALLOW_IMAGE_EXTENSIONS) .")~";

        return (bool)preg_grep($imagesMimeTypePattern, $contentType);
    }

    /**
     * check path
     * @return bool true if is available url
     */
    private function checkPathIsUrl(): bool
    {
        $headers = @get_headers($this->path);
        $headers = (is_array($headers)) ? implode( "\n ", $headers) : $headers;

        $availableHttpResponseCodePattern = '#^HTTP/.*\s+[(200|301|302)]+\s#i';
        return (bool)preg_match($availableHttpResponseCodePattern, $headers);
    }

    private function getContentTypesFromUrl(): array
    {
        $headers = get_headers($this->path, 1);

        $contentType = [];
        if (is_array($headers['Content-Type'])) {
            $contentType = array_merge($contentType, $headers['Content-Type']);
        } else {
            $contentType[] = $headers['Content-Type'];
        }
        return $contentType;
    }

    /**
     * parsing page for one best/biggest image
     * @param $link
     * @return string
     */
    protected function parseOne($link): string
    {
        $this->link = $link;

        // save host if can
        $url = parse_url($link);
        $this->host  = isset($url['scheme']) ? $url['scheme'].':' : '';
        $this->host .= isset($url['host']) ? '//' . $url['host'] : '';

        $this->content = file_get_contents($link);

        return $this->findBiggestImage();

    }

    /**
    * search image link with name or anchor "origin"
    * or take image that name includes biggest number
    * priority by anchor first, after name
    */
    public function findBiggestImage(): string
    {
        $image = $this->getImageWithAnchor();

        if ($image !== 'false') {
            return $image;
        }
        return $this->getBiggestImage();
    }

    /**
     * search best image by anchor
     * @return string
     */
    protected function getImageWithAnchor(): string
    {
        if (preg_match_all($this->pattern_image_link_with_anchor(), $this->content, $out)) {

            // check for relative links and add host if need
            foreach ($out['links'] as &$link) {
                if (preg_match('~^/\w+~', $link) === 1) {
                    $link = $this->host . $link;
                }
            }
            unset($link);

            return end($out['links']);
        }
        return 'false';
    }

    /**
     * search best image by name
     */
    // hery
    protected function getBigImages()
    {
        return 'false';
    }
}
