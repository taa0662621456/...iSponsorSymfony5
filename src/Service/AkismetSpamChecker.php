<?php

namespace App\Service;

use App\Entity\Review\ReviewProduct;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AkismetSpamChecker
{

    private HttpClientInterface $client;
    private string $endpoint;

    public function __construct(HttpClientInterface $client, string $akismetKey)
    {
        $this->client = $client;
        $this->endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $akismetKey);
    }

    /**
     * @return int Spam score: 0: not spam, 1: maybe spam, 2: blatant spam
     *
     * @throws RuntimeException|TransportExceptionInterface if the call did not work
     */
    public function getSpamScore(ReviewProduct $comment, array $context): int
    {
        $response = $this->client->request('POST', $this->endpoint, [
            'body' => array_merge($context, [
                'blog' => 'https://guestbook.example.com',
                'comment_type' => 'comment',
//                'comment_author' => $comment->getAuthor(),
//                'comment_author_email' => $comment->getEmail(),
//                'comment_content' => $comment->getText(),
//                'comment_date_gmt' => $comment->getCreatedAt()->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? 1 : 0;
    }
}
