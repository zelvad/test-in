<?php

namespace App\Services\Previews;

use App\Models\Preview;
use App\Services\Dto\Previews\CreatePreviewDto;
use App\Services\Previews\Parser\GetPreviewCommand;
use Throwable;

class CreatePreviewCommand
{
    private GetPreviewCommand $previewCommand;

    public function __construct(GetPreviewCommand $previewCommand)
    {
        $this->previewCommand = $previewCommand;
    }

    /**
     * @throws Throwable
     */
    public function createPreview(CreatePreviewDto $dto): Preview
    {
        $previewArray = $this->previewCommand->getPreview($dto->getUrl());

        $newPreview = new Preview;
        $newPreview->url = $dto->getUrl();
        $newPreview->author = $previewArray['author'];
        $newPreview->title = $previewArray['title'];
        $newPreview->description = $previewArray['description'];
        $newPreview->image = $previewArray['image'];
        $newPreview->user_id = auth()->id();
        $newPreview->saveOrFail();

        return $newPreview;
    }
}
