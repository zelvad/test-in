<?php

namespace App\Services\Previews\Parser;

use Exception;
use Symfony\Component\DomCrawler\Crawler;

class ParseTagsCommand
{
    /**
     * Загрузка тегов страницы
     * @param string $url
     * @return array
     * @throws Exception
     */
    public function getTags(string $url): array
    {
        return $this->parseTags(
            $this->loadPageAndInitCrawler($url), $url
        );
    }

    /**
     * Загрузка страницы и инициализация Crawler
     * @param string $url
     * @return Crawler
     * @throws Exception
     */
    private function loadPageAndInitCrawler(string $url): Crawler
    {
        try {
            return $this->initCrawler(
                $this->loadHtml($url)
            );
        } catch (Exception $e) {
            throw new Exception(sprintf('Не удалось загрузить страницу %s.', $url));
        }
    }

    /**
     * Инициализация Crawler
     * @param string $html
     * @return Crawler
     * @throws Exception
     */
    private function initCrawler(string $html): Crawler
    {
        return new Crawler($html);
    }

    /**
     * Загрузка страницы
     * @param string $url
     * @return false|string
     */
    private function loadHtml(string $url): string
    {
        return file_get_contents($url);
    }

    /**
     * Парсинг тегов страницы
     * @param Crawler $crawler
     * @param string $url
     * @return array
     */
    private function parseTags(Crawler $crawler, string $url): array
    {
        return [
            'author' => $this->getAuthorOrSiteNameTag($crawler) ?? $this->getDomainFromUrl($url),
            'title' => $this->getTitleTag($crawler),
            'description' => $this->getDescriptionTag($crawler),
            'image' => $this->getOgImageTag($crawler)
        ];
    }

    /**
     * Загрузка тега автора или названия сайта
     * @param Crawler $crawler
     * @return string
     */
    private function getAuthorOrSiteNameTag(Crawler $crawler): ?string
    {
        return $this->getAuthorTag($crawler) ?? $this->getOgSiteNameTag($crawler);
    }

    /**
     * Загрузка автора страницы
     * @param Crawler $crawler
     * @return string|null
     */
    private function getAuthorTag(Crawler $crawler): ?string
    {
        return $crawler
                ->filter('head > meta[name="Author"]')
                ->extract(['content'])[0] ?? null;
    }

    /**
     * Загрузка названия сайта
     * @param Crawler $crawler
     * @return string|null
     */
    private function getOgSiteNameTag(Crawler $crawler): ?string
    {
        return $crawler
                ->filter('head > meta[property="og:site_name"]')
                ->extract(['content'])[0] ?? null;
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
     * Загрузка заголовка страницы
     * @param Crawler $crawler
     * @return string|null
     */
    private function getTitleTag(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('head > title')->text();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    /**
     * Загрузка описания страницы
     * @param Crawler $crawler
     * @return string|null
     */
    private function getDescriptionTag(Crawler $crawler): ?string
    {
        return $crawler
            ->filter('head > meta[name="description"]')
            ->extract(['content'])[0] ?? null;
    }

    /**
     * Загрузка изображения страницы
     * @param Crawler $crawler
     * @return mixed|null
     */
    private function getOgImageTag(Crawler $crawler): ?string
    {
        return $crawler
                ->filter('head > meta[property="og:image"]')
                ->extract(['content'])[0] ?? null;
    }
}
