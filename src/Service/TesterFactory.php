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

class TesterFactory
{
    /**
     * @param YoutubeVideoTester $youtubeVideoTester
     */
    public function __construct(
        private YoutubeVideoTester $youtubeVideoTester
    ) {}

    /**
     * @param TestRequestInterface $testRequest
     * @return TesterInterface
     * @throws \Exception
     */
    public function create(TestRequestInterface $testRequest): TesterInterface
    {
        $postUrl = $testRequest->getVideoUrl();
        if (str_contains($postUrl, 'youtube')) {
            return $this->youtubeVideoTester;
        }

        throw new \Exception('Tester Service Not found, check post URL');
    }
}