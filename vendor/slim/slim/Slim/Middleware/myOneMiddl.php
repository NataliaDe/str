<?php

namespace \Slim\Middleware;

class myOneMiddle extends \Slim\MyMiddleware
{
    public function call()
    {
         // Get reference to application
        $app = $this->app;

        // Run inner middleware and application
        $this->next->call();

        // Capitalize response body
        $res = $app->response;
        $body = $res->getBody();
        $res->setBody(strtoupper($body));
    }
}