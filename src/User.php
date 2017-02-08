<?php

/*
 * This file is part of the lichv/socialite.
 *
 * (c) lichv <i@lichv.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lichv\Socialite;

use ArrayAccess;
use JsonSerializable;

/**
 * Class User.
 */
class User implements ArrayAccess, UserInterface, JsonSerializable
{
    use HasAttributes;

    /**
     * User constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * Get the nickname / username for the user.
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->getAttribute('nickname');
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * Get the e-mail address of the user.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    /**
     * Get the avatar / image URL for the user.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->getAttribute('avatar');
    }

    /**
     * Set the token on the user.
     *
     * @param \Lichv\Socialite\AccessTokenInterface $token
     *
     * @return $this
     */
    public function setToken(AccessTokenInterface $token)
    {
        $this->setAttribute('token', $token);

        return $this;
    }

    /**
     * Get the authorized token.
     *
     * @return \Lichv\Socialite\AccessToken
     */
    public function getToken()
    {
        return $this->getAttribute('token');
    }

    /**
     * Alias of getToken().
     *
     * @return \Lichv\Socialite\AccessToken
     */
    public function getAccessToken()
    {
        return $this->token;
    }

    /**
     * Get the original attributes.
     *
     * @return array
     */
    public function getOriginal()
    {
        return $this->getAttribute('original');
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array_merge($this->attributes, ['token' => $this->token->getAttributes()]);
    }
}