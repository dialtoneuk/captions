<?php

function get_page()
{

    if (isset($_GET["page"]) && is_numeric($_GET["page"]))
        return abs($_GET["page"]);
    else
        return 0;
}

function get_image()
{

    if (isset($_GET["images"]) && is_numeric($_GET["images"]))
        return abs($_GET["images"]);
    else
        return 0;
}

function generate_hotlinks($max_images = 0)
{

    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/"))
        @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/");

    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/cache/images/"))
        @mkdir($_SERVER["DOCUMENT_ROOT"] . "/cache/images/");

    $opened_files = [];

    for ($image = 0; $image < $max_images; $image++) {

        if ($image & LYDS_MODULUS_NUMBER > 2) //will remove every odd number
            continue;

        if (($image & 4) <= 2) //will roughly halve that
            continue;

        $cache_file = "cache/images/{$image}" . LYDS_DATA_FILEEXTENSION;
        $file_name = get_map_filename($image);

        if (!isset($opened_files[$file_name]))
            $opened_files[$file_name] = read_data($file_name);

        $file = $opened_files[$file_name][$image];

        $data = [
            "image" => $file,
            "viewed" => 0
        ];

        file_put_contents($cache_file, serialize($data));
    }
}

function generate_lists($max_images = 0)
{

    $opened_files = [];
    $images = [];
    $counter = 0;
    $page = 0;


    for ($i = 0; $i < $max_images; $i++) {

        $file_name = "/data/list{$page}" . LYDS_DATA_FILEEXTENSION;
        $file = get_map_filename($i);

        if (!isset($opened_files[$file]))
            $opened_files[$file] = read_data($file);

        if (isset($opened_files[$file][$i]))
            $images[$i] = $opened_files[$file][$i];

        if ($counter >= LYDS_PAGE_MAX) {

            file_put_contents($_SERVER["DOCUMENT_ROOT"] . $file_name, serialize($images));
            $images = [];
            $counter = 0;
            $page++;
        }

        $counter++;
    }
}

function generate_stats()
{

    $counter = 1;
    $stats = [];
    $stats["last_generated"] = time();
    $stats["image_count"] = 0;

    if (file_exists(LYDS_MAP_FILENAME . "0" . LYDS_DATA_FILEEXTENSION)) {
        $data = read_data(LYDS_MAP_FILENAME . "0" . LYDS_DATA_FILEEXTENSION);
        $stats["image_count"] += count($data);
    }

    while (file_exists(LYDS_MAP_FILENAME . "{$counter}" . LYDS_DATA_FILEEXTENSION)) {

        $data = read_data(LYDS_MAP_FILENAME . "{$counter}" . LYDS_DATA_FILEEXTENSION);
        $stats["image_count"] += count($data);
        $counter++;
    }

    if (file_exists("confirmations" . LYDS_DATA_FILEEXTENSION)) {

        $data = read_data("confirmations" . LYDS_DATA_FILEEXTENSION);

        if (!isset($stats["confirmation_count"]))
            $stats["confirmation_count"] = count($data);
        else
            $stats["confirmation_count"] += count($data);
    }

    file_put_contents("stats" . LYDS_DATA_FILEEXTENSION, serialize($stats));
}

function generate_map_files()
{

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . LYDS_IMAGE_FOLDER));
    $sorted = [];
    $counter = 0;

    foreach ($iterator as $file) {

        if ($file->isDir())
            continue;

        $file = pathinfo($file->getPathname());

        if ($file["extension"] != "jpg" && $file["extension"] !== "png" && $file["extension"] !== "gif")
            continue;

        if ($counter === 0) {

            if (!isset($sorted[0]))
                $sorted[0] = [];

            $sorted[0][$counter] = $file;
        } else {

            if (!isset($sorted[0]))
                $sorted[0] = [];

            $sorted[$counter & LYDS_MODULUS_NUMBER][$counter] = $file;
        }

        $counter++;
    }

    foreach ($sorted as $key => $value) {

        if (empty($key) || $key == 0)
            $key = "0";

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/" . LYDS_MAP_FILENAME . "{$key}" . LYDS_DATA_FILEEXTENSION, serialize($value));
    }
}

function make_link($location, array $parameters = [])
{

    $string = "";

    foreach ($parameters as $index => $parameter)
        if ($string === "")
            if (empty($parameter) || $parameter == "")
                $string = "?" . $index;
            else
                $string = "?" . $index . "=" . $parameter;
        else
            if (empty($parameter) || $parameter == "")
                $string .= "&" . $index;
            else
                $string .= "&" . $index . "=" . $parameter;

    return (LYDS_HOSTING_SUBFOLDER . $location . $string);
}

function get_map_filename($image)
{

    if ($image === 0)
        $file_number = 0;
    else
        $file_number = $image & LYDS_MODULUS_NUMBER;

    if ($file_number == 0)
        $file_number = "0";

    return sprintf("%s{$file_number}%s", LYDS_MAP_FILENAME, LYDS_DATA_FILEEXTENSION);
}

function read_data($file_name = "map0" . LYDS_DATA_FILEEXTENSION)
{

    if (file_exists($file_name))
        return (unserialize(file_get_contents($file_name)));
    else
        return [];
}