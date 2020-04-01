<?php

namespace App\Http\Controllers;

use App\Model\Like;
use App\Model\Reply;
use App\Events\LikeEvent;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('JWT');
    }

    public function likeIt(Reply $reply)
    {
        $reply->likes()->create([ #reply_id will be added automatically
            'user_id' => auth()->id()
        ]);

        broadcast(new LikeEvent($reply->id, 1))->toOthers();
    }

    public function unlikeIt(Reply $reply)
    {
        $reply->likes()->where('user_id', auth()->id())->first()->delete();

        broadcast(new LikeEvent($reply->id, 0))->toOthers();
    }
}
