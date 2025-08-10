<?php

namespace app\controller;

use support\Request;
use support\Response;

class HealthController
{

    public function health(Request $request): Response
    {
        return json(['status' => 'ok']);
    }



}
