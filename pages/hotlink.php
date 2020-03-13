<?php
$image = get_image();

$cache_file = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;

if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/"))
    @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/");

if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/images/"))
    @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/images/");

if (file_exists($cache_file)) {

    $file = read_data($cache_file);
    $viewed = $file["viewed"];
    $file = $file["image"];

    $image_location = LYDS_HOSTING_SUBFOLDER . "{$file["dirname"]}/{$file["filename"]}.{$file["extension"]}";
    $image_location = str_replace($_SERVER["DOCUMENT_ROOT"], "", $image_location);

    header("Content-type: image/{$file["extension"]}");
    echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . $image_location);

    $viewed++;

    file_put_contents($cache_file, serialize([
        "viewed" => $viewed,
        "image" => $file
    ]));

    exit;
}

$file_name = get_map_filename($image);
$files = read_data($file_name);

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

    file_put_contents($cache_file, serialize($data));

    header("Content-type: image/{$file["extension"]}");
    echo $image_data;
    exit;
}

