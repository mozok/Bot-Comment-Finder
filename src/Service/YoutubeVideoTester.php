<?php
/*
Copyright 2021 Mozok Evgen

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

namespace App\Service;

use App\Entity\TestRequestInterface;
use App\Service\TesterInterface;
use App\Entity\TestResultInterface;
use App\Entity\YoutubeTestResult;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\TestResult\CommentInterface;
use App\Entity\TestResult\YoutubeComment;

class YoutubeVideoTester implements TesterInterface
{
    /**
     * @param \Google_Client $googleClient
     * @param YoutubeTestResult $testResult
     */
    public function __construct(
        protected \Google_Client $googleClient,
        protected YoutubeTestResult $testResult
    ) {}

    /**
     * {@inheritdoc}
     */
    public function execute(TestRequestInterface $testRequest): TestResultInterface
    {
        $allComments = $this->loadComments($testRequest);
        $invalidComments = $this->filterInvalidComments($allComments);

        $this->testResult->setCommentCollection($invalidComments);

        return $this->testResult;
    }

    /**
     * @param TestRequestInterface $testRequest
     * @return CommentInterface[]
     */
    private function loadComments(TestRequestInterface $testRequest): array
    {
        $this->googleClient->setApplicationName('BotCommentFinder');
        $service = new \Google_Service_YouTube($this->googleClient);

        $videoId = $this->getVideoId($testRequest);
        $nextPageToken = '';

        $queryParams = [
            'videoId' => $videoId,
            'maxResults' => 100,
        ];

        $comments = [];
        do {
            if ($nextPageToken) {
                $queryParams['pageToken'] = $nextPageToken;
            }

            $response = $service->commentThreads->listCommentThreads('snippet,replies', $queryParams);
            array_push(
                $comments,
                ...$this->aggregateComments($response->getItems(), $testRequest)
            );
            $nextPageToken = $response->getNextPageToken();
        } while ($nextPageToken);

        return $comments;
    }

    /**
     * @param TestRequestInterface $testRequest
     * @return string|null
     * @TODO: refactor to properly check URL
     */
    private function getVideoId(TestRequestInterface $testRequest): ?string
    {
        $query = parse_url($testRequest->getVideoUrl())['query'];
        $params = [];
        parse_str($query, $params);
        return $params['v'];
    }

    /**
     * @param \Google\Service\YouTube\CommentThread[] $responseItems
     * @return CommentInterface[]
     */
    private function aggregateComments(array $responseItems, TestRequestInterface $testRequest): array
    {
        $result = [];
        foreach ($responseItems as $responseItem) {
            $text = $responseItem->getSnippet()->getTopLevelComment()->getSnippet()->getTextOriginal();
            if (mb_strlen($text) >= $testRequest->getMinimalCommentLength()) {
                $comment = $this->createYoutubeComment(
                    $responseItem->getSnippet()->getTopLevelComment()
                );
                $result[] = $comment;
            }

            if ($responseItem->getReplies() === null) {
                continue;
            }

            foreach ($responseItem->getReplies()->getComments() as $reply) {
                $text = $reply->getSnippet()->getTextOriginal();
                if (mb_strlen($text) >= $testRequest->getMinimalCommentLength()) {
                    $comment = $this->createYoutubeComment($reply);
                    $result[] = $comment;
                }
            }
        }

        return $result;
    }

    /**
     * @param \Google\Service\YouTube\Comment $googleComment
     * @return CommentInterface
     */
    private function createYoutubeComment(\Google\Service\YouTube\Comment $googleComment): CommentInterface
    {
        $text = $googleComment->getSnippet()->getTextOriginal();
        $comment = new YoutubeComment();
        $comment->setId(
            $googleComment->getId()
        );
        $comment->setText($text);
        $comment->setAuthorUrl(
            $googleComment->getSnippet()->getAuthorChannelUrl()
        );

        return $comment;
    }

    /**
     * Find invalid comments using Levenshtein distance between two strings
     * @param CommentInterface[] $comments
     * @return CommentInterface[]
     * @TODO: refactor to use multiple threads
     */
    private function filterInvalidComments(array $comments): array
    {
        $result = [];
        $foundIds = [];
        foreach ($comments as $comment) {
            foreach ($comments as $commentToCompare) {
                if ($comment->getId() === $commentToCompare->getId()) {
                    continue;
                }

                if (in_array($comment->getId(), $foundIds)) {
                    continue;
                }

                $lev = levenshtein($comment->getText(), $commentToCompare->getText());
                if ($lev < 5) {
                    $result[] = $comment;
                    $foundIds[] = $comment->getId();
                }
            }
        }
        return $result;
    }
}
