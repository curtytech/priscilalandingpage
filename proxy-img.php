<?php

function proxyGet()
{
    $imageUrl = isset($_GET["url_img"]) ? $_GET["url_img"] : "";
    if (empty($imageUrl) || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        http_response_code(400); // Bad request
        echo "Invalid or missing URL";
        return;
    }

    $context = stream_context_create([
        "http" => [
            "follow_location" => true, // Handle redirections
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36",
        ],
    ]);

    $imageData = @file_get_contents($imageUrl, false, $context);
    if ($imageData === false) {
        http_response_code(500); // Internal server error
        echo "Unable to fetch image";
        return;
    }

    header("Content-Type: image/jpeg");
    echo $imageData;
}

proxyGet();
