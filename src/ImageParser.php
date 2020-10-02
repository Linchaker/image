<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;

class ImageParser implements Parser
{
    use ParserPatterns;

    protected const ALLOW_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    protected $content;
    protected $link;
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
        // image
        if (in_array(substr($path, -3, 3), self::ALLOW_IMAGE_EXTENSIONS)) {
            // already image
            return file_get_contents($path);
        }

        // get headers to check stream
        $headers = get_headers($path, 1);
        // maybe Content-Type is array
        $contentType = [];
        if (is_array($headers['Content-Type'])) {
            $contentType = array_merge($contentType, $headers['Content-Type']);
        } else {
            $contentType[] = $headers['Content-Type'];
        }

        // stream, and this image type
        if (count(preg_grep("/^image/", $contentType)) > 0) {
            return file_get_contents($path);
        }

        // text page - try find image on the page
        if (count(preg_grep("/^text/", $contentType)) > 0) {
            $img = $this->parseOne($path);
            if ($img !== 'false') {
                return file_get_contents($img);
            }

        }

        return 'false';
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

    // hery
    /*public function findAllImages()
    {
        if ($count_found = preg_match_all($this->pattern_all_image_link, $this->content, $links)) {
            echo "Found: $count_found links";
            echo "<pre>";
            print_r($links);
        }
    }*/
}
