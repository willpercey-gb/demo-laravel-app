<?php

namespace App\Http\Controllers\V1\Phonebook;

use App\Events\Commands\CreatePhoneBookEntry;
use App\Events\Commands\UpdatePhonebookEntry;
use App\Http\Controllers\V1\AbstractController;
use App\Models\Phonebook\Entry;
use App\Repositories\Phonebook\EntryRepository;
use App\Response\JsonSuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nette\Utils\Json;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Group;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

#[Group(prefix: '/v1/phonebook/entries')]
#[Middleware('auth:api')]
class EntryController extends AbstractController
{
    public function __construct(private EntryRepository $repository, Request $request)
    {
        parent::__construct($request);
    }

    #[Get('/')]
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->repository->paginate(72, page: $request->get('page') ?? 1);

        return new JsonResponse(
            data: $this->encoder->encodeData($paginator),
            json: true
        );
    }

    #[Get('/{uuid}')]
    public function show(Entry $entry): JsonResponse
    {
        return new JsonResponse(
            data: $this->encoder->encodeData($entry),
            json: true
        );
    }

    #[Post('/')]
    public function create(Request $request): JsonResponse
    {
        $content = Json::decode($request->getContent());

        $message = new CreatePhonebookEntry((array)$content->data->attributes);
        $message->validate();
        event($message);

        return new JsonSuccessResponse(
            data:   ['id' => $message->getUuid()],
            status: BaseResponse::HTTP_ACCEPTED
        );
    }

    #[Patch('/{uuid}')]
    public function update(Entry $entry, Request $request): JsonResponse
    {
        $content = Json::decode($request->getContent());

        $message = new UpdatePhonebookEntry((array)$content->data->attributes);
        $message->setEntryUuid($entry->uuid);
        $message->validate();
        event($message);

        return new JsonSuccessResponse(
            data:   ['id' => $message->getEntryUuid()],
            status: BaseResponse::HTTP_ACCEPTED
        );
    }

    #[Delete('/{uuid}')]
    public function delete(Entry $entry): JsonResponse
    {
        $entry->deleteOrFail();

        return new JsonSuccessResponse(status: BaseResponse::HTTP_NO_CONTENT);
    }
}
