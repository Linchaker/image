<?php
declare(strict_types=1);

namespace Linchaker\ImagePRS;

trait ParserPatterns
{
    protected $pattern_absolut_link = '
            [a-z]*   # protocol
            :?       # if not protocol, no :
            //       # // must have
            [a-z0-9.\-]+[.][a-z]{2,4} # domain
            [a-zA-z0-9/._\-]+ # uri
            #  [a-zA-Z]{3,4}# extension
            (?:jpg|png|gif)';

    protected $pattern_relative_link = '
            /
            [a-zA-z0-9/._\-]+ # uri
            #  [a-zA-Z]{3,4}# extension
            (?:jpg|png|gif)';

    protected $pattern_all_image_link = '~
        (
        [a-z]*   # protocol
        :?       # if not protocol, no :
        //       # // must have
        [a-z0-9.\-]+[.][a-z]{2,4} # domain
        [a-zA-z0-9/._\-]+ # uri
        #  [a-zA-Z]{3,4}# extension
        (?:jpg|png|gif)
        )~xu';

    /**
     * pattern get image link from tag a
     * where anchor start with one of $this->validAnchors array
     */
    protected function pattern_image_link_with_anchor()
    {
        return '~      

        # begin a tag
        <a \s+ (?:[^>]*? \s+)?
        # attribute href
        href=[\"\']
        # get text of href - must be image link
        (?<links>
            # link can be absolut or relative
            (?:
            '.$this->pattern_absolut_link.'
            |
            '.$this->pattern_relative_link.'
            )
        )
        # end href
        [\"\']
        # maybe have other attributes
        .*?>
        # get anchor
        (?:
        .*
        (?:'.implode('|', $this->validAnchors).')
        .*
        )
        <\/a>
        ~uix';
    }
}
