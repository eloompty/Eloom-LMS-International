<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="LMS API",
 *      description="Learning Management System API",
 *      @OA\Contact(
 *          email="support@example.com"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 * 
 * @OA\Server(
 *      url="/api/v1/",
 *      description="LMS API"
 * )
 *
 * @OA\Tag(
 *     name="Students",
 *     description="API Endpoints of Students"
 * )
 * 
 * @OA\Tag(
 *     name="Intakes",
 *     description="API Endpoints of Intakes"
 * )
 * 
 *  @OA\SecurityScheme(
 *      securityScheme="passport",
 *      type="http",
 *      scheme="bearer",
 * )
 */

class ApiController extends Controller
{
    
}
