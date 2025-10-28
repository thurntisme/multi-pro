<?php

declare(strict_types=1);

namespace App\Core;

use App\Helpers\Flash;

abstract class Controller
{
    protected Request $request;

    protected Response $response;

    protected TemplateViewerInterface $viewer;

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setViewer(TemplateViewerInterface $viewer): void
    {
        $this->viewer = $viewer;
    }

    protected function view(string $template, array $data = [], array $flash = ['type' => '', 'msg' => '']): Response
    {
        if ($flash['type'] !== '' && $flash['msg'] !== '') {
            Flash::add($flash['type'], $flash['msg']);
        }
        $this->response->setBody($this->viewer->render($template, $data));

        return $this->response;
    }

    protected function json(
        mixed $data = null,
        int $status_code = 200,
        string $message = "OK",
        array $errors = [],
        array $pagination = [],
    ): Response {
        $this->response->setStatusCode($status_code);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setBody($this->setJsonResponse($data, $status_code, $message, $errors, $pagination));

        return $this->response;
    }

    private function setJsonResponse(
        mixed $data,
        int $statusCode,
        string $message,
        array $errors,
        array $pagination,
    ): string {
        $response = [
            "status" => $statusCode >= 200 && $statusCode < 300 ? "success" : "error",
            "code" => $statusCode,
            "message" => $message,
            "data" => $data,
            "meta" => [
                "timestamp" => gmdate("c"), // ISO8601
                "requestId" => uniqid("req_", true)
            ]
        ];

        if (count($errors) > 0) {
            $response["errors"] = $errors;
        }
        if (count($pagination) > 0) {
            $response["pagination"] = $pagination;
        }

        return json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    protected function redirect(string $url): Response
    {
        $this->response->redirect($url);

        return $this->response;
    }

    protected function reload(): Response
    {
        $this->response->reload();

        return $this->response;
    }
}
