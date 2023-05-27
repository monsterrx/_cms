<?php

/* Todo: Uncomment this when deploying to production of RXCMS */
/*require '../_cms/vendor/autoload.php';
require '../_cms/vendor/firebase/php-jwt/src/JWT.php';*/

// Todo: Comment this when deploying to production of RXCMS
require '../vendor/autoload.php';
use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$payload = [
    "sub" => "1", // unique user id string
    "name" => "Sean Philip Cruz", // full name of user

    // Optional custom user root path
    // "https://claims.tiny.cloud/drive/root" => "/johndoe",

    "exp" => time() + 60 * 10 // 10 minute expiration
];
$private_key = <<<EOD
        -----BEGIN PRIVATE KEY-----
        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
        tQ21VAdJjSxps3THvHS7ed/hfZCVCw==
        -----END PRIVATE KEY-----
        EOD;

try {
    $token = JWT::encode($payload, $private_key, 'RS256');
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['token' => $token]);
} catch (Exception $exception) {
    http_response_code(500);
    header('Content-Type: application/json');
    return $exception->getMessage();
}
