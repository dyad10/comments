<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class Comments extends Controller
{
    //
	public function saveComment(Request $request) {
		$comment = new Comment();
		$comment->name = $request->input('name');
		$comment->comment = $request->input('comment');
		return $comment->save();
	}

}
