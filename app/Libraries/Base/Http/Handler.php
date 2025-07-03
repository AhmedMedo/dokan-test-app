<?php

namespace App\Libraries\Base\Http;

use BadMethodCallException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

abstract class Handler extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @param  string  $method
     * @param  array  $parameters
     * @return Response|array
     */
    public function callAction($method, $parameters)
    {
        if ($method !== '__invoke') {
            throw new BadMethodCallException('Only __invoke method can be called on handler.');
        }

        return $this->{$method}(...array_values($parameters));
    }
} 