<?php
// src/AppBundle/Form/DataTransformer/UserToNicknameTransformer.php

namespace AppBundle\Form\DataTransformer;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

use AppBundle\Entity\User;

/**
 * Transforms between a User instance and a username string
 */
class UserToNicknameTransformer implements DataTransformerInterface
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->repository = $doctrine->getManager()->getRepository('AppBundle:User');
    }

    /**
     * Transforms a User instance into a username string.
     *
     * @param User|null $value User instance
     *
     * @return string|null Username
     *
     * @throws UnexpectedTypeException if the given value is not a User instance
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (! $value instanceof User) {
            throw new UnexpectedTypeException($value, 'AppBundle\Entity\User');
        }

        return $value->getNickname();
    }

    /**
     * Transforms a username string into a User instance.
     *
     * @param string $value Username
     *
     * @return User the corresponding User instance
     *
     * @throws UnexpectedTypeException if the given value is not a string
     */
    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (! is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        return $this->repository->findOneByNickname($value);
    }
}