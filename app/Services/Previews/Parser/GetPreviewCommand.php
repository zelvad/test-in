<?php

namespace App\Services\Previews\Parser;

use Exception;

class GetPreviewCommand
{
    private ParseTagsCommand $tagsCommand;
    private CheckRobotsRulesCommand $rulesCommand;
    private PreviewCacheCommand $cacheCommand;

    public function __construct(ParseTagsCommand $tagsCommand, CheckRobotsRulesCommand $rulesCommand, PreviewCacheCommand $cacheCommand)
    {
        $this->tagsCommand = $tagsCommand;
        $this->rulesCommand = $rulesCommand;
        $this->cacheCommand = $cacheCommand;
    }

    /**
     * @throws Exception
     */
    public function getPreview(string $parseUrl)
    {
        if (!$this->rulesCommand->checkRobotsRules($parseUrl)) {
            return $parseUrl;
        }

        $tags = $this->cacheCommand->getCache($parseUrl);

        if (!$tags) {
            $tags = $this->tagsCommand->getTags($parseUrl);
            $this->cacheCommand->putCache($parseUrl, $tags);
        }

        return $tags;
    }
}
