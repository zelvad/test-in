<?php

namespace App\Http\Controllers;

use App\Http\Requests\Previews\CreatePreviewRequest;
use App\Services\Previews\CreatePreviewCommand;
use App\Services\Previews\GetPreviewsCommand;
use Illuminate\Http\JsonResponse;
use Throwable;

class PreviewController extends Controller
{
    /**
     * Список пред. просмотра
     * @param GetPreviewsCommand $command
     * @return JsonResponse
     */
    public function index(GetPreviewsCommand $command): JsonResponse
    {
        return response()->json($command->getPreviews());
    }

    /**
     * Создание нового превью
     * @param CreatePreviewRequest $request
     * @param CreatePreviewCommand $command
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CreatePreviewRequest $request, CreatePreviewCommand $command): JsonResponse
    {
        return response()->json(
            $command->createPreview($request->getDto())
        );
    }
}
