<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
	public function store(Request $request) {
		// Validate data
		//  Check for required fields
		if($request->name && $request->comment) {
			// continue
			$comment = new Comment;
			$comment->name = $request->name;
			$comment->comment = $request->comment;
			$comment->comment_id = $request->comment_id;
			$comment->save();
		}
		else {
			// return exception
			abort(418, 'Required information missing');
		}
	}
}
