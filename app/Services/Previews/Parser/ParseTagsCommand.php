<?php

namespace App\Services\Previews\Parser;

use Exception;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class ParseTagsCommand
{
    /**
     * @throws Exception
     */
    public function getTags(string $url): array
    {
        $crawlerPage = $this->loadPageAndInitCrawler($url);

        return $this->parseTags($crawlerPage, $url);
    }

    /**
     * @throws Exception
     */
    private function loadPageAndInitCrawler(string $url): Crawler
    {
        try {
            $html = $this->loadHtml($url);

            return $this->initCrawler($html);
        } catch (Exception $e) {
            throw new Exception(
                sprintf('Не удалось загрузить страницу %s.', $url)
            );
        }
    }

    private function initCrawler(string $html): Crawler
    {
        return new Crawler($html);
    }

    private function loadHtml(string $url): string
    {
        return file_get_contents($url);
    }

    private function parseTags(Crawler $crawler, string $url): array
    {
        return [
            'author' => $this->getAuthorOrSiteNameTag($crawler) ?? $this->parseDomainFromUrl($url),
            'title' => $this->getTitleTag($crawler),
            'description' => $this->getOgDescriptionTag($crawler) ?? $this->getDescriptionTag($crawler),
            'image' => $this->getOgImageTag($crawler)
        ];
    }

    private function getAuthorOrSiteNameTag(Crawler $crawler): ?string
    {
        return $this->getAuthorTag($crawler) ?? $this->getOgSiteNameTag($crawler);
    }

    private function getAuthorTag(Crawler $crawler): ?string
    {
        return $crawler
                ->filter('head > meta[name="Author"]')
                ->extract(['content'])[0] ?? null;
    }

    private function getOgSiteNameTag(Crawler $crawler): ?string
    {
        return $crawler
                ->filter('head > meta[property="og:site_name"]')
                ->extract(['content'])[0] ?? null;
    }

    private function parseDomainFromUrl(string $url)
    {
        return parse_url($url, PHP_URL_HOST);
    }

    private function getTitleTag(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('head > title')->text();
        } catch (Throwable $exception) {
            return null;
        }
    }

    private function getDescriptionTag(Crawler $crawler): ?string
    {
        return $crawler
                ->filter('head > meta[name="description"]')
                ->extract(['content'])[0] ?? null;
    }

    private function getOgDescriptionTag(Crawler $crawler): ?string
    {
        return $crawler
            ->filter('head > meta[name="og:description"]')
            ->extract(['content'])[0] ?? null;
    }

    private function getOgImageTag(Crawler $crawler): ?string
    {
        return $crawler
                ->filter('head > meta[property="og:image"]')
                ->extract(['content'])[0] ?? null;
    }
}
