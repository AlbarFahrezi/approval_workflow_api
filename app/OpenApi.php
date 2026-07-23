<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Approval Workflow API",
 *     version="1.0.0",
 *     description="REST API Approval Workflow"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Masukkan token dengan format: Bearer {token}"
 * )
 */
class OpenApi
{
}