<?php

namespace App\SwaggerAnnotations;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="Authenticate user and generate JWT token",
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="User's email (case-insensitive)",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="password",
 *         in="query",
 *         description="User's password",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Login successful",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="The provided credentials are incorrect.")
 *         )
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="The email field must be a valid email address."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email field must be a valid email address.")
 *                 )
 *             )
 *         )
 *     )
 * )
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *             @OA\Property(property="password_confirmation", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
 *         )
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="The email has already been taken."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email has already been taken.")
 *                 )
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout the authenticated user",
 *     @OA\Header(
 *         header="Authorization",
 *         required=true,
 *         description="Bearer JWT token",
 *         @OA\Schema(type="string", example="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Logout successful"
 *     )
 * )
 */
class AuthAnnotations
{
}
