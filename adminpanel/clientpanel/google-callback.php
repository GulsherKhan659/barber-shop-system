<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $oauth = new Google_Service_Oauth2($client);
        $googleUser = $oauth->userinfo->get();

        $googleId = $googleUser->id;
        $email = $googleUser->email;
        $name = $googleUser->name;

        $existingUser = $db->select("users", "*", ["google_id" => $googleId]);

        if (empty($existingUser)) {
            $db->insert("users", [
                "shop_id" => 1,
                "name" => $name,
                "email" => $email,
                "phone" => '', 
                "password" => '',
                "role" => "user",
                "google_id" => $googleId
            ]);
            $userId = $db->lastInsertId();
        } else {
            $userId = $existingUser[0]['id'];
        }

        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'user',
            'shop_id' => 1
        ];

        header("Location: service_tab.php");
        exit;
    } else {
        echo "Error during Google login: " . $token['error_description'];
    }
} else {
    echo "No code received from Google.";
}
