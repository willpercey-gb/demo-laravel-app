<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\Handlers\DisplayHandler;
use App\Exceptions\Handlers\ExceptionHandler;
use App\Http\Controllers\Controller;
use App\Response\JsonErrorResponse;
use App\Response\JsonSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Nette\Utils\Json;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Group;
use Spatie\RouteAttributes\Attributes\Post;

#[Group(prefix: '/v1/auth')]
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @return JsonResponse
     */
    #[Post('login')]
    public function login(Request $request): JsonResponse
    {
        $credentials = Json::decode($request->getContent());

        if (!$token = auth()->attempt((array)$credentials)) {
            return new JsonErrorResponse(
                (new DisplayHandler())->setErrors(
                    $this->respondWithErrors('auth.unauthorized')
                )
            );
        }

        return $this->respondWithToken($token);
    }

    /**
     * @return JsonResponse
     */
    #[Get('user')]
    public function user(): JsonResponse
    {
        return new JsonResponse(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    #[Post('logout')]
    public function logout()
    {
        auth()->logout();

        return new JsonSuccessResponse(
            ['message' => 'auth.logged_out']
        );
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    #[Post('refresh')]
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return new JsonResponse(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        );
    }

    protected function respondWithErrors($message): Collection
    {
        return collect(
            [
                //This was a lazy way to get clean errors back from an incompatible interface in a proper application
                //this would become a class within the Exceptions directory
                new class($message) implements ExceptionHandler {
                    public function __construct(private string $message)
                    {
                    }

                    public function getSource(): array
                    {
                        return [
                            'pointer' => 'auth'
                        ];
                    }

                    public function getTitle(): string
                    {
                        return $this->message;
                    }

                    public function getDetail()
                    {
                        return null;
                    }

                    public function getMeta(): ?array
                    {
                        return null;
                    }
                }
            ]
        );
    }
}
