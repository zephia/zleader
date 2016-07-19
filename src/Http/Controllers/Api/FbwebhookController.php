<?php

namespace Zephia\ZLeader\Http\Controllers\Api;

class FbwebhookController extends Controller
{
    public function store(Request $request)
    {
        if($request->hub_verify_token == 'test_token'){
            
        }

        return $request->hub_challenge;
    }
}