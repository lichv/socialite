<?php

/*
 * This file is part of the lichv/socialite.
 *
 * (c) lichv <i@lichv.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lichv\Socialite\Providers;

use Lichv\Socialite\AccessTokenInterface;
use Lichv\Socialite\ProviderInterface;
use Lichv\Socialite\User;

/**
 * Class QQProvider.
 *
 * @link http://wiki.connect.qq.com/oauth2-0%E7%AE%80%E4%BB%8B [QQ - OAuth 2.0 登录QQ]
 */
class QpProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The base url of QQ open API.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.mp.qq.com';
    protected $authUrl = 'https://open.mp.qq.com';

    /**
     * User openid.
     *
     * @var string
     */
    protected $openId;

    /**
     * get token(openid) with unionid.
     *
     * @var bool
     */
    protected $withUnionId = false;

    /**
     * User unionid.
     *
     * @var string
     */
    protected $unionId;

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['snsapi_base'];

    /**
     * The uid of user authorized.
     *
     * @var int
     */
    protected $uid;

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->authUrl.'/connect/oauth2/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->baseUrl.'/sns/oauth2/access_token';
    }

    /**
     * Get the Post fields for the token request.
     *
     * @param string $code
     *
     * @return array
     */
    protected function getTokenFields($code)
    {
        return [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
    }

    /**
     * Get the access token for the given code.
     *
     * @param string $code
     *
     * @return \Lichv\Socialite\AccessToken
     */
    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        return $this->parseAccessToken($response->getBody()->getContents());
    }

    /**
     * Get the access token from the token response body.
     *
     * @param string $body
     *
     * @return \Lichv\Socialite\AccessToken
     */
    public function parseAccessToken($body)
    {
        parse_str($body, $token);

        return parent::parseAccessToken($token);
    }

    /**
     * @return self
     */
    public function withUnionId()
    {
        $this->withUnionId = true;

        return $this;
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param \Lichv\Socialite\AccessTokenInterface $token
     *
     * @return array
     */
    protected function getUserByToken(AccessTokenInterface $token)
    {
        $scopes = explode(',', $token->getAttribute('scope', ''));

        if (in_array('snsapi_base', $scopes)) {
            return $token->toArray();
        }

        if (empty($token['openid'])) {
            throw new InvalidArgumentException('openid of AccessToken is required.');
        }

        $response = $this->getHttpClient()->get($this->baseUrl.'/cgi-bin/user/info', [
            'query' => [
                'access_token' => $token->getToken(),
                'openid' => $token['openid'],
            ],
        ]);

        return json_decode($response->getBody(), true);

    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     *
     * @return \Lichv\Socialite\User
     */
    protected function mapUserToObject(array $user)
    {
        return new User([
            'id' => $this->openId,
            'unionid' => $this->unionId,
            'nickname' => $this->arrayItem($user, 'nickname'),
            'name' => $this->arrayItem($user, 'nickname'),
            'email' => $this->arrayItem($user, 'email'),
            'avatar' => $this->arrayItem($user, 'figureurl_qq_2'),
        ]);
    }

    protected function getCodeFields($state = null)
    {
        $fields = array_merge([
            'appid' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'response_type' => 'code',
        ], $this->parameters);

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return $fields;
    }

    /**
     * Remove the fucking callback parentheses.
     *
     * @param string $response
     *
     * @return string
     */
    protected function removeCallback($response)
    {
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }

        return $response;
    }
}
