<?php

namespace App\Services\Previews;

use App\Models\Preview;
use App\Services\Dto\Previews\CreatePreviewDto;
use App\Services\Previews\Parser\GetPreviewCommand;

class CreatePreviewCommand
{
    private GetPreviewCommand $command;

    /**
     * @param GetPreviewCommand $command
     */
    public function __construct(GetPreviewCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Добавление нового превью
     * @param CreatePreviewDto $dto
     * @param GetPreviewCommand $command
     * @return Preview
     * @throws \Throwable
     */
    public function createPreview(CreatePreviewDto $dto): Preview
    {
        $previewArr = $this->command->getPreview($dto->getUrl());

        $newPreview = new Preview;
        $newPreview->url = $dto->getUrl();
        $newPreview->author = $previewArr['author'];
        $newPreview->title = $previewArr['title'];
        $newPreview->description = $previewArr['description'];
        $newPreview->image = $previewArr['image'];
        $newPreview->user_id = auth()->id();
        $newPreview->saveOrFail();

        return $newPreview;
    }
}
