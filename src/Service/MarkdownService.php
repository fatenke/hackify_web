<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MarkdownService
{
    private MarkdownConverter $converter;
    private UserRepository $userRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;

        // Configure the CommonMark environment with extensions
        $config = [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 100,
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AutolinkExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Convert markdown to HTML and process @mentions
     */
    public function convertToHtml(string $markdown): string
    {
        // First convert basic markdown to HTML
        $html = $this->converter->convert($markdown)->getContent();
        
        // Then process @mentions - this happens after markdown conversion
        // so we're manipulating the final HTML
        return $this->processMentions($html);
    }

    /**
     * Parse @mentions in the text and convert them to links to user profiles
     * Format: @username will link to the user's profile
     */
    private function processMentions(string $text): string
    {
        return preg_replace_callback(
            '/@([a-zA-Z0-9_]+)/',
            function ($matches) {
                $username = $matches[1];
                $user = $this->userRepository->findOneBy(['nomUser' => $username]);
                
                if ($user) {
                    $profileUrl = $this->urlGenerator->generate('app_profile_show', [
                        'id' => $user->getId()
                    ]);
                    return sprintf('<a href="%s" class="user-mention">@%s</a>', $profileUrl, $username);
                }
                
                // If user not found, keep the original @mention
                return $matches[0];
            },
            $text
        );
    }
} 