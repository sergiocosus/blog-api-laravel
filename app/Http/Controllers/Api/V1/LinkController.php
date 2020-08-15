<?php


namespace App\Http\Controllers\Api\V1;


use App\Core\Link\Link;
use App\Core\Link\LinkService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Link\CreateLinkRequest;
use App\Http\Requests\Link\DeleteLinkRequest;
use App\Http\Requests\Link\UpdateLinkRequest;
use Illuminate\Http\Request;

class LinkController extends Controller {

    /**
     * @var LinkService
     */
    private $linkService;


    /**
     * LinkService constructor.
     */
    public function __construct(LinkService $linkService) {
        $this->linkService = $linkService;
    }

    public function get(Request $request) {
        $paginated_links = Link::query()
            ->with('categories')
            ->when($request->search, function ($query, $search) {
                $query->search($search, null, true, true);
            })
            ->when($request->with_trashed, function ($query, $with_trashed) {
                $query->withTrashed();
            })->paginate();

        return compact('paginated_links');
    }

    public function getOne(Link $link) {
        return compact('link');
    }

    public function store(CreateLinkRequest $request) {
        $link = $this->linkService->createLink($request->only([
            'title',
            'url',
            'description',
            'category_ids',
        ]));

        $link->categories()->sync($request->category_ids);

        return compact('link');
    }

    public function update(UpdateLinkRequest $request, Link $link) {
        $this->linkService->updatePost($link, $request->only([
            'title',
            'url',
            'description',
            'category_ids',
        ]));

        return compact('link');
    }

    public function delete(DeleteLinkRequest $request, Link $link) {
        $link->delete();

        return response()->noContent();
    }

    public function restore(DeleteLinkRequest $request, $linkId) {
        $link = Link::withTrashed()->find($linkId);
        $link->restore();

        return compact('link');
    }
}
