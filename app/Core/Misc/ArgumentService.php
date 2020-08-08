<?php

namespace App\Core\Misc;

use Illuminate\Http\Request;

class ArgumentService
{
    public function createArgument(Request $request)
    {
        $argument = new Argument();
        $maxArgumentOrder = Argument::max('order');
        $argument->order = $maxArgumentOrder + 1;

        return $this->updateArgument($argument, $request);
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
