<?php
declare(strict_types=1);

@error_reporting ( E_ALL );
@ini_set ('display_errors', '1');
require_once 'vendor/autoload.php';

use Linchaker\ImagePRS\ImagePRS;

/**
 * input
 * - img link
 * - stream img
 * - page with images
 */
$crop = [];

if (isset($argv[2])) {
    $crop[] = $argv[2];
}
if (isset($argv[3])) {
    $crop[] = $argv[3];
}

$imagePRS = new ImagePRS();

$image = $imagePRS->save($argv[1], $crop);
echo '----';
print_r($image);
