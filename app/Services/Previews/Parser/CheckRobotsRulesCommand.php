<?php

namespace App\Services\Previews\Parser;

use RobotsTxtParser\RobotsTxtParser;

class CheckRobotsRulesCommand
{
    /**
     * Проверка возможности парсинга страницы
     * @param string $parseUrl
     * @return bool
     */
    public function checkRobotsRules(string $parseUrl): bool
    {
        if (!$robotsTxtRules = $this->loadRobotsFileRules($parseUrl)) {
            return true;
        }

        return $this->checkRules($robotsTxtRules, $parseUrl);
    }

    /**
     * Загрузка правил из файла robots.txt
     * @param string $parseUrl
     * @return array|false
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
     * Загрузка домена ссылки
     * @param string $url
     * @return array|false|int|string|null
     */
    private function getDomainFromUrl(string $url)
    {
        return parse_url($url, PHP_URL_HOST);
    }

    /**
     * Генерация ссылки на robots.txt
     * @param string $domain
     * @return string
     */
    private function generateUrlToRobotsFileFromDomain(string $domain): string
    {
        return "http://{$domain}/robots.txt";
    }

    /**
     * Загрузка robots.txt
     * @param string $url
     * @return false|string
     */
    private function loadRobotsFileFromUrl(string $url)
    {
        try {
            if ($robotsTxt = file_get_contents($url)) {
                return $robotsTxt;
            }

            return false;
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * Парсинг файла robots.txt
     * @param string $robotsTxtFile
     * @return array
     */
    private function parseRobotsFile(string $robotsTxtFile): array
    {
        return (new RobotsTxtParser($robotsTxtFile))->getRules();
    }

    /**
     * Проверка возможности парсинга URL
     * @param array $rules
     * @param string $parseUrl
     * @return bool
     */
    private function checkRules(array $rules, string $parseUrl): bool
    {
        if (!$this->checkExistsDisallow($rules)) {
            return true;
        }

        $disallowALlURLs = $this->checkDisallowAll($rules);
        $disallowThisUrl = $this->checkDisallowUrl($rules, $parseUrl);

        if ($disallowALlURLs or $disallowThisUrl) {
            return false;
        }

        return true;
    }

    /**
     * Проверка запрета индексации всех страниц
     * @param array $rules
     * @return bool
     */
    private function checkDisallowAll(array $rules): bool
    {
        foreach ($rules as $rule) {
            if ($rule == '/') {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверка существования исключений
     * @param array $rules
     * @return bool
     */
    private function checkExistsDisallow(array $rules): bool
    {
        return isset($rules['*']['disallow']);
    }

    /**
     * Проверка не заблокирован ли url к индексации
     * @param array $rules
     * @param string $parseUrl
     * @return bool
     */
    private function checkDisallowUrl(array $rules, string $parseUrl): bool
    {
        $pathParseUrl = $this->getPathFromUrl($parseUrl);

        foreach ($rules['*']['disallow'] as $rule) {
            if ($rule == $pathParseUrl) {
                return true;
            }
        }

        return false;
    }

    /**
     * Парсинг пути URL
     * @param string $parseUrl
     * @return array|false|int|string|null
     */
    private function getPathFromUrl(string $parseUrl)
    {
        return parse_url($parseUrl, PHP_URL_PATH);
    }
}
