<?php

namespace Vidia\AuthBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    /**
     * Set plain password.
     *
     * @param string
     *
     * @return UserInterface
     */
    public function setPlainPassword($plainPassword);

    /**
     * Get plain password.
     *
     * @return string|null
     */
    public function getPlainPassword();

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles();

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password);

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword();

    /**
     * Set salt.
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt);

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt();

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername();

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials();

    /**
     * Set accessToken.
     *
     * @param string $accessToken
     *
     * @return User
     */
    public function setAccessToken($accessToken);

    /**
     * Get accessToken.
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Set refreshToken.
     *
     * @param string $refreshToken
     *
     * @return User
     */
    public function setRefreshToken($refreshToken);

    /**
     * Get refreshToken.
     *
     * @return string
     */
    public function getRefreshToken();

    /**
     * Set expirationDate.
     *
     * @param \DateTime $expirationDate
     *
     * @return User
     */
    public function setExpirationDate($expirationDate);

    /**
     * Get expirationDate.
     *
     * @return \DateTime
     */
    public function getExpirationDate();

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email);

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Returns the unique field to generate token's the user.
     *
     * @return string The email
     */
    public function getUniqueField();
}
