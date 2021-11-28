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

namespace App\Controller;

use App\Entity\TestRequestInterface;
use App\Service\TesterFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\YoutubeTestRequest;
use App\From\Type\TestRequestType;
use Symfony\Component\HttpFoundation\RequestStack;

class TestController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack
    ) {}

    #[Route('/')]
    public function testVideo(
        Request $request,
        TesterFactory $testerFactory
    ): Response {

        $testRequest = new YoutubeTestRequest();
        $testRequest->setMinimalCommentLength(20);
        $form = $this->createForm(TestRequestType::class, $testRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TestRequestInterface $testRequest */
            $testRequest = $form->getData();

            $tester = $testerFactory->create($testRequest);
            $testResult = $tester->execute($testRequest);

            $session = $this->requestStack->getSession();
            $session->set('comments', $testResult->getCommentCollection());
            $session->set('test_video', $testRequest->getVideoUrl());

            return $this->redirectToRoute('test_success');
        }

        return $this->renderForm('home.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/test-success', name: 'test_success')]
    public function testSuccess(Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $comments = $session->get('comments');
        $testVideo = $session->get('test_video');
        return $this->render('test_success.html.twig', [
            'test_video' => $testVideo,
            'comments' => $comments
        ]);
    }
}