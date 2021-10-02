<?php

namespace App\Services\Previews\Parser;

use App\Exceptions\Previews\RobotsTxtException;
use RobotsTxtParser\RobotsTxtParser;

class CheckRobotsRulesCommand
{
    /**
     * @throws RobotsTxtException
     */
    public function checkRobotsRules(string $parseUrl): bool
    {
        if (!$robotsTxtRules = $this->loadRobotsFileRules($parseUrl)) {
            return true;
        }

        try {
            $this->checkRules($robotsTxtRules, $parseUrl);
            return true;
        } catch (RobotsTxtException $exception) {
            return false;
        }
    }

    /**
     * @throws RobotsTxtException
     */
    private function loadRobotsFileRules(string $parseUrl)
    {
        $domain = $this->getDomainFromUrl($parseUrl);
        $robotsTxtUrl = $this->generateUrlToRobotsFileFromDomain($domain);
        $robotsTxtFile = $this->loadRobotsFileFromUrl($robotsTxtUrl);
        $robotsTxtArr = $this->parseRobotsFile($robotsTxtFile);

        if (!$robotsTxtFile or empty($robotsTxtArr)) {
            return false;
        }

        return $robotsTxtArr;
    }

    /**
     * @throws RobotsTxtException
     */
    private function getDomainFromUrl(string $url)
    {
        if (!$domain = parse_url($url, PHP_URL_HOST)) {
            throw new RobotsTxtException('Ошибка парсинга домена!');
        }

        return $domain;
    }

    private function generateUrlToRobotsFileFromDomain(string $domain): string
    {
        return "http://{$domain}/robots.txt";
    }

    private function loadRobotsFileFromUrl(string $url)
    {
        try {
            return file_get_contents($url);
        } catch (\Throwable $exception) {
            return false;
        }
    }

    private function parseRobotsFile(string $robotsTxtFile): array
    {
        $robotsParser = new RobotsTxtParser($robotsTxtFile);
        return $robotsParser->getRules();
    }

    /**
     * @throws RobotsTxtException
     */
    private function checkRules(array $rules, string $parseUrl)
    {
        if ($this->checkExistsDisallow($rules)) {
            $this->checkDisallowAll($rules);
            $this->checkDisallowUrl($rules, $parseUrl);
        }
    }

    private function checkExistsDisallow(array $rules): bool
    {
        return isset($rules['*']['disallow']);
    }

    /**
     * @throws RobotsTxtException
     */
    private function checkDisallowAll(array $rules)
    {
        foreach ($rules as $rule) {
            if ($rule == '/') {
                throw new RobotsTxtException('URL запрещен к индексации.');
            }
        }
    }

    /**
     * @throws RobotsTxtException
     */
    private function checkDisallowUrl(array $rules, string $parseUrl)
    {
        $pathParseUrl = $this->getPathFromUrl($parseUrl);

        foreach ($rules['*']['disallow'] as $rule) {
            if ($rule == $pathParseUrl) {
                throw new RobotsTxtException('URL запрещен к индексации.');
            }
        }
    }

    /**
     * @throws RobotsTxtException
     */
    private function getPathFromUrl(string $parseUrl)
    {
        if (!$parseUrl = parse_url($parseUrl, PHP_URL_PATH)) {
            throw new RobotsTxtException('Ошибка парсинга URL.');
        }

        return $parseUrl;
    }
}
