参考 overtrue/socialite 
#Requirement

PHP >= 5.4
Installation

$ composer require "overtrue/socialite:~1.0"
Usage

authorize.php:

<?php
use Overtrue\Socialite\SocialiteManager;

$config = [
    'github' => [
        'client_id'     => 'your-app-id',
        'client_secret' => 'your-app-secret',
        'redirect'      => 'http://localhost/socialite/callback.php',
    ],
];

$socialite = new SocialiteManager($config);

$response = $socialite->driver('github')->redirect();

echo $response;// or $response->send();
callback.php:

<?php

// ...
$user = $socialite->driver('github')->user();

$user->getId();        // 1472352
$user->getNickname();  // "overtrue"
$user->getName();      // "安正超"
$user->getEmail();     // "anzhengchao@gmail.com"
...
Configuration

Now we support the following sites:

facebook, github, google, linkedin, weibo, qq, wechat and douban.

Each drive uses the same configuration keys: client_id, client_secret, redirect.

example:

...
  'weibo' => [
    'client_id'     => 'your-app-id',
    'client_secret' => 'your-app-secret',
    'redirect'      => 'http://localhost/socialite/callback.php',
  ],
...
Scope

Before redirecting the user, you may also set "scopes" on the request using the scope method. This method will overwrite all existing scopes:

$response = $socialite->driver('github')
                ->scopes(['scope1', 'scope2'])->redirect();
Redirect URL

You may also want to dynamic set redirect，you can use the following methods to change the redirect URL:

$socialite->redirect($url);
// or
$socialite->withRedirectUrl($url)->redirect();
// or
$socialite->setRedirectUrl($url)->redirect();
WeChat scopes:

snsapi_base, snsapi_userinfo - Used to Media Platform Authentication.
snsapi_login - Used to web Authentication.
Additional parameters

To include any optional parameters in the request, call the with method with an associative array:

$response = $socialite->driver('google')
                    ->with(['hd' => 'example.com'])->redirect();
User interface

Standard user api:


$user = $socialite->driver('weibo')->user();
{
  "id": 1472352,
  "nickname": "overtrue",
  "name": "安正超",
  "email": "anzhengchao@gmail.com",
  "avatar": "https://avatars.githubusercontent.com/u/1472352?v=3",
  "original": {
    "login": "overtrue",
    "id": 1472352,
    "avatar_url": "https://avatars.githubusercontent.com/u/1472352?v=3",
    "gravatar_id": "",
    "url": "https://api.github.com/users/overtrue",
    "html_url": "https://github.com/overtrue",
    ...
  },
  "token": {
    "access_token": "5b1dc56d64fffbd052359f032716cc4e0a1cb9a0",
    "token_type": "bearer",
    "scope": "user:email"
  }
}
You can fetch the user attribute as a array key like this:

$user['id'];        // 1472352
$user['nickname'];  // "overtrue"
$user['name'];      // "安正超"
$user['email'];     // "anzhengchao@gmail.com"
...
Or using method:

$user->getId();
$user->getNickname();
$user->getName();
$user->getEmail();
$user->getAvatar();
$user->getOriginal();
$user->getToken();// or $user->getAccessToken()
Get original response from OAuth API

The $user->getOriginal() method will return an array of the API raw response.

Get access token Object

You can get the access token instance of current session by call $user->getToken() or $user->getAccessToken() or $user['token'] .

Get user with access token

$accessToken = new AccessToken(['access_token' => $accessToken]);
$user = $socialite->user($accessToken);
Custom Session or Request instance.

You can set the request with your custom Request instance which instanceof Symfony\Component\HttpFoundation\Request.


$request = new Request(); // or use AnotherCustomRequest.

$socialite = new SocialiteManager($config, $request);
Or set request to SocialiteManager instance:

$socialite->setRequest($request);
You can get the request from SocialiteManager instance by getRequest():

$request = $socialite->getRequest();
Set custom session manager.

By default, the SocialiteManager use Symfony\Component\HttpFoundation\Session\Session instance as session manager, you can change it as following lines:

$session = new YourCustomSessionManager();
$socialite->getRequest()->setSession($session);
Your custom session manager must be implement the Symfony\Component\HttpFoundation\Session\SessionInterface.
