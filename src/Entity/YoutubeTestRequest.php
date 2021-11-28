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

namespace App\Entity;

use App\Entity\TestRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class YoutubeTestRequest implements TestRequestInterface
{
    #[Assert\NotBlank]
    protected string $videoUrl;

    #[Assert\NotBlank]
    protected int $minimalCommentLength;

    /**
     * {@inheritdoc}
     */
    public function getVideoUrl(): string
    {
        return $this->videoUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setVideoUrl(string $videoUrl): void
    {
        $this->videoUrl = $videoUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinimalCommentLength(): int
    {
        return $this->minimalCommentLength;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinimalCommentLength(int $length): void
    {
        $this->minimalCommentLength = $length;
    }
}