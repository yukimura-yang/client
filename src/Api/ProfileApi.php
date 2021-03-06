<?php
declare(strict_types=1);

namespace R6API\Client\Api;

use R6API\Client\Api\Type\PlatformType;
use R6API\Client\Exception\ApiException;
use R6API\Client\Model\Profile;

/**
 * API implementation to manage the profiles.
 *
 * @author Baptiste Leduc <baptiste.leduc@gmail.com>
 */
class ProfileApi extends AbstractApi implements ProfileApiInterface
{
    const URL = '/v2/profiles';

    /**
     * {@inheritdoc}
     */
    public function get(string $platform, string $value, string $key = 'nameOnPlatform'): array
    {
        // check $platform is part of PlatformType enum
        if (!PlatformType::isValidValue($platform)) {
            throw new ApiException(sprintf('"%s" isn\'t a valid value from PlatformType enum.', $platform));
        }

        // 3 accepted keys: nameOnPlatform, idOnPlatform, userId
        $acceptedFilterKeys = [
            'nameOnPlatform',
            'idOnPlatform',
            'userId'
        ];
        if (!in_array($key, $acceptedFilterKeys)) {
            throw new ApiException(sprintf('"%s" doesn\'t exists as valid key.', $key));
        }

        $parameters = [
            'platformType' => constant(PlatformType::class.'::_PROFILES_'.$platform),
            $key => $value
        ];

        $data = $this->resourceClient->getResource(self::URL, $parameters);
        return $this->serializer->deserialize(
            $data,
            Profile::class.'[]',
            'json'
        );
    }
}
