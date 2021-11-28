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

interface TestResultInterface
{
    /**
     * @return CommentInterface[]
     */
    public function getCommentCollection(): array;

    /**
     * @param CommentInterface[] $commentCollection
     */
    public function setCommentCollection(array $commentCollection): void;

    /**
     * @param CommentInterface $comment
     */
    public function addComment(CommentInterface $comment): void;
}
