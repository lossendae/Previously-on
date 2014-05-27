<?php

Route::filter('xsrf', function()
{
//    $sessionToken = Session::token();
//    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//        if ($sessionToken != Request::header('X-XSRF-TOKEN'))
//        {
//            $response  = ['error' => ['message' => 'Token Mismatch']];
//            return Response::json($response, 401);
//        }
//    }
});
