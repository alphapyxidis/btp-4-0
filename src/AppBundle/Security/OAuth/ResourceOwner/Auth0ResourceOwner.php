<?php

namespace AppBundle\Security\OAuth\ResourceOwner;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth2ResourceOwner;

/**
 * Auth0ResourceOwner.
 */
class Auth0ResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritdoc}
     */
    protected $paths = array(
        'identifier' => 'user_id',
        'userid' => 'user_id',
        'nickname' => 'nickname',
        'realname' => 'name',
        'email' => 'email',
        'profilepicture' => 'picture',
    );

    /**
     * {@inheritdoc}
     */
    protected function doGetTokenRequest($url, array $parameters = array())
    {
        return $this->httpRequest(
            $url,
            http_build_query($parameters, '', '&'),
            $this->getRequestHeaders(),
            'POST'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function doGetUserInformationRequest($url, array $parameters = array())
    {
        return $this->httpRequest(
            $url,
            http_build_query($parameters, '', '&'),
            $this->getRequestHeaders()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $auth0Client = base64_encode(json_encode(array(
            'name' => 'HWIOAuthBundle',
            'version' => 'unknown',
            'environment' => array(
                'name' => 'PHP',
                'version' => \PHP_VERSION,
            ),
        )));

        $resolver->setDefaults(array(
            'authorization_url' => '{base_url}/authorize?auth0Client='.$auth0Client,
            'access_token_url' => '{base_url}/oauth/token',
            'infos_url' => '{base_url}/userinfo',
            'auth0_client' => $auth0Client,
        ));

        $resolver->setRequired(array(
            'base_url',
        ));

        $normalizer = function (Options $options, $value) {
            return str_replace('{base_url}', $options['base_url'], $value);
        };

        $resolver->setNormalizer('authorization_url', $normalizer);
        $resolver->setNormalizer('access_token_url', $normalizer);
        $resolver->setNormalizer('infos_url', $normalizer);
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    private function getRequestHeaders(array $headers = array())
    {
        if (isset($this->options['auth0_client'])) {
            $headers['Auth0-Client'] = $this->options['auth0_client'];
        }

        return $headers;
    }
}
