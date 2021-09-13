<?php

namespace App\Services\Previews\Parser;

use Exception;

class GetPreviewCommand
{
    private ParseTagsCommand $tagsCommand;
    private CheckRobotsRulesCommand $rulesCommand;

    /**
     * @param ParseTagsCommand $tagsCommand
     * @param CheckRobotsRulesCommand $rulesCommand
     */
    public function __construct(ParseTagsCommand $tagsCommand, CheckRobotsRulesCommand $rulesCommand)
    {
        $this->tagsCommand = $tagsCommand;
        $this->rulesCommand = $rulesCommand;
    }

    /**
     * Генерация превью страницы
     * @param string $parseUrl
     * @return array|string
     * @throws Exception
     */
    public function getPreview(string $parseUrl)
    {
        if (!$this->rulesCommand->checkRobotsRules($parseUrl))
            return $parseUrl;

        return $this->tagsCommand->getTags($parseUrl);
    }
}
