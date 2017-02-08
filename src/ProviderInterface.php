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

interface ProviderInterface
{
    /**
     * Redirect the user to the authentication page for the provider.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect();

    /**
     * Get the User instance for the authenticated user.
     *
     * @param \Lichv\Socialite\AccessTokenInterface $token
     *
     * @return \Lichv\Socialite\User
     */
    public function user(AccessTokenInterface $token = null);
}
