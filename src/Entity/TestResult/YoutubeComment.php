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

namespace App\Entity\TestResult;

use App\Entity\TestResult\CommentInterface;

class YoutubeComment implements CommentInterface
{
    protected int|string $id;
    protected string $text;
    protected ?string $link = null;
    protected ?string $authorUrl;

    /**
     * {@inheritdoc}
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(int|string $commentId): void
    {
        $this->id = $commentId;
    }

    /**
     * {@inheritdoc}
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * {@inheritdoc}
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * {@inheritdoc}
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorUrl(): ?string
    {
        return $this->authorUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthorUrl(string $url): void
    {
        $this->authorUrl = $url;
    }
}
