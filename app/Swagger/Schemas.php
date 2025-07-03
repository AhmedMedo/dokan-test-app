<?php

/**
 * @OA\Schema(
 *   schema="Post",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="content", type="string"),
 *   @OA\Property(property="user", type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 *   ),
 *   @OA\Property(property="category", type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 *   ),
 *   @OA\Property(property="comment_count", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
/**
 * @OA\Schema(
 *   schema="Comment",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="content", type="string"),
 *   @OA\Property(property="user", type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string")
 *   ),
 *   @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */ 