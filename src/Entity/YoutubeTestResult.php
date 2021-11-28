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

use App\Entity\TestResult\CommentInterface;
use App\Entity\TestResultInterface;

class YoutubeTestResult implements TestResultInterface
{
    /**
     * @var CommentInterface[]
     */
    protected array $commentCollection = [];

    /**
     * {@inheritdoc}
     */
    public function getCommentCollection(): array
    {
        return $this->commentCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function setCommentCollection(array $commentCollection): void
    {
        $this->commentCollection = $commentCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function addComment(CommentInterface $comment): void
    {
        $commentCollection = $this->getCommentCollection();
        $commentCollection[] = $comment;
        $this->setCommentCollection($commentCollection);
    }
}
