<?php

namespace App\Twig;

use App\Service\MarkdownService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownExtension extends AbstractExtension
{
    private MarkdownService $markdownService;

    public function __construct(MarkdownService $markdownService)
    {
        $this->markdownService = $markdownService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('parse_markdown', [$this, 'parseMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    public function parseMarkdown(string $content): string
    {
        return $this->markdownService->convertToHtml($content);
    }
} 