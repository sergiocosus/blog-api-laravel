<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserCommentController extends Controller {
    /**
     * Return the user's comments.
     */
    public function index(Request $request, User $user) {
        $paginated_comments = $user->comments()
            ->latest()
            ->paginate($request->get('limit', 20));

        return $paginated_comments;
    }
}
