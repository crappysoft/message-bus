<?php
declare(strict_types = 1);

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ApiResponse
{
    /**
     * @var integer
     */
    private $httpStatusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array|null
     */
    private $serializationGroups;

    /**
     * @var boolean
     */
    private $isSuccessful;

    /**
     * @param integer $httpStatusCode
     * @param array   $headers
     *
     * @return ApiResponse
     */
    public static function success(int $httpStatusCode = JsonResponse::HTTP_OK, array $headers = []): ApiResponse
    {
        return new self(true, $httpStatusCode, $headers);
    }

    /**
     * @param mixed      $data
     * @param array|null $serializationGroups
     * @param integer    $httpStatusCode
     * @param array      $headers
     *
     * @return ApiResponse
     */
    public static function successWithData($data, array $serializationGroups = null, int $httpStatusCode = JsonResponse::HTTP_OK, array $headers = []): ApiResponse
    {
        return new self(true, $httpStatusCode, $headers, $data, $serializationGroups);
    }

    /**
     * @param mixed      $data
     * @param array|null $serializationGroups
     * @param integer    $httpStatusCode
     * @param array      $headers
     *
     * @return ApiResponse
     */
    public static function error($data, array $serializationGroups = null, int $httpStatusCode = JsonResponse::HTTP_BAD_REQUEST, array $headers = []): ApiResponse
    {
        return new self(false, $httpStatusCode, $headers, $data, $serializationGroups);
    }

    /**
     * @param boolean    $isSuccessful
     * @param integer    $httpStatusCode
     * @param array      $headers
     * @param mixed      $data
     * @param array|null $serializationGroups
     */
    private function __construct(bool $isSuccessful, int $httpStatusCode, array $headers, $data = null, array $serializationGroups = null)
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->headers = $headers;
        $this->data = $data;
        $this->serializationGroups = $serializationGroups;
        $this->isSuccessful = $isSuccessful;
    }

    /**
     * @param NormalizerInterface $normalizer
     *
     * @return JsonResponse
     */
    public function convertToJsonResponse(NormalizerInterface $normalizer): JsonResponse
    {
        $data = null;
        if (null !== $this->data) {
            $data = $normalizer->normalize($this->data, null, ['groups' => $this->serializationGroups]);
        }

        $payload = $this->isSuccessful ? Api::success($data) : Api::error($data);
        $response = new JsonResponse($payload, $this->httpStatusCode, $this->headers);
        $response->headers->set('content-type', 'application/json');
        return $response;
    }
}
