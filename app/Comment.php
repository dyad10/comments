<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Comment extends Model
{
    //

	public static function getComments() {
		$l1comments = Comment::whereNull('comment_id')->get();
		$l2comments = Comment::whereIn('id', $l1comments)->get();
		return $l1comments->toJson();
	}
	public function store(Request $request) {
		// Validate data
		//  Check for required fields
		if($request->name && $request->comment) {
			// continue
			$comment = new Comment;
			$comment->name = $request->name;
			$comment->comment = $request->comment;
			$comment->comment_id = $request->comment_id;
			return $comment->save();
		}
		else {
			// return exception
			abort(418, 'Required information missing');
		}
	}
}
