<?php

namespace App\Http\Controllers\Api\V1\Misc;

use App\Core\Misc\Argument;
use App\Core\Misc\ArgumentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Misc\Argument\CreateArgumentRequest;
use App\Http\Requests\Misc\Argument\DeleteArgumentRequest;
use App\Http\Requests\Misc\Argument\GetArgumentRequest;
use App\Http\Requests\Misc\Argument\RestoreArgumentRequest;
use App\Http\Requests\Misc\Argument\UpdateArgumentRequest;
use Illuminate\Http\Request;

class ArgumentController extends Controller
{
    /**
     * @var ArgumentService
     */
    private $argumentService;

    public function __construct(ArgumentService $argumentService)
    {
        $this->argumentService = $argumentService;
    }

    /**
     * Return the arguments.
     */
    public function index(Request $request)
    {
        $paginated_arguments = Argument::query()
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->with_trashed, function ($query, $with_trashed) {
                $query->withTrashed();
            })
            ->orderBy('order', 'asc')
            ->paginate($request->get('per_page', 20));

        return compact('paginated_arguments');
    }

    public function getOne(GetArgumentRequest $request, Argument $argument)
    {
        return compact('argument');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateArgumentRequest $request)
    {
        $argument = $this->argumentService->createArgument($request);

        return compact('argument');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArgumentRequest $request, Argument $argument)
    {
        $this->argumentService->updateArgument($argument, $request);

        return compact('argument');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(DeleteArgumentRequest $request, Argument $argument)
    {
        $argument->delete();
    }

    public function restore(RestoreArgumentRequest $request, $argument)
    {
        $argument = tap(Argument::onlyTrashed()->findOrFail($argument), function ($argument) {
            $argument->restore();
        });

        return compact('argument');
    }
}
