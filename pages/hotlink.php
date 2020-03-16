<?php
//stops direct access to this file through http browser :)
if( !defined("LYDS_ENABLE_GENERATION") )
{
    http_response_code(404);
    exit;
}

$image = get_image();

$_ = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;

if (file_exists($_)) {
    $file = read_data($_);
    $viewed = $file["viewed"];
    $file = $file["image"];
    $image_location = LYDS_HOSTING_SUBFOLDER . "{$file["dirname"]}/{$file["filename"]}.{$file["extension"]}";
    $image_location = str_replace($_SERVER["DOCUMENT_ROOT"], "", $image_location);
    $viewed++;

    file_put_contents($_, serialize([
        "viewed" => $viewed,
        "image" => $file
    ]));

    header("Content-type: image/{$file["extension"]}");
    echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . $image_location);
    exit;
}
else
{

    $map = get_map_filename($image);
    $files = read_data($map);

    if (!isset($files[$image]))
        http_response_code(404);
    else {

        $file = $files[$image];
        $image_location = LYDS_HOSTING_SUBFOLDER . "{$file["dirname"]}/{$file["filename"]}.{$file["extension"]}";
        $image_location = str_replace($_SERVER["DOCUMENT_ROOT"], "", $image_location);
        $image_data = file_get_contents($_SERVER["DOCUMENT_ROOT"] . $image_location);

        $data = [
            "image" => $file,
            "viewed" => 1
        ];

        file_put_contents($_, serialize($data));

        header("Content-type: image/{$file["extension"]}");
        echo $image_data;
    }
    exit;
}