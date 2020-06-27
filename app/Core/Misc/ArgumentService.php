<?php

namespace App\Core\Misc;

use Illuminate\Http\Request;

class ArgumentService
{
    public function createArgument(Request $request)
    {
        $post = new Argument();

        return $this->updateArgument($post, $request);
    }

    public function updateArgument(Argument $argument, Request $request)
    {
        $argument->fill($request->only([
            'question',
            'answer',
            'order',
        ]));

        $argument->save();

        return $argument;
    }
}
