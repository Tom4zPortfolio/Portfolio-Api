<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;
use ArrayObject;

final class OpenApiFactory implements OpenApiFactoryInterface
{

    public function __construct(private OpenApiFactoryInterface $decorated) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $pathItem = new Model\PathItem(
            ref: 'JWT Refresh Token',
            post: new Model\Operation(
                operationId: 'postCredentialsRefresh',
                tags: ['Refresh Token'],
                responses: [
                    '200' => [
                        'description' => 'new JWT',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'token' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'JWT Token not found',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'code' => ['type' => 'integer', 'example' => 401],
                                        'message' => ['type' => 'string', 'example' => 'Invalid refresh token.'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Refresh JWT token',
                requestBody: new Model\RequestBody(
                    content: new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'refresh_token' => ['type' => 'string'],
                                ],
                                'required' => ['refresh_token'],
                            ],
                        ],
                    ])
                )
            )
        );

        $openApi->getPaths()->addPath('/api/refresh', $pathItem);

        return $openApi;
    }


}
