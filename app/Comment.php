<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Comment extends Model
{
    //

	public static $maxdepth = 3;

	public static function getComments() {
		$comments = [];
		foreach(Comment::whereIn('comment_id', array(0))->get() as $l1comment) {
			$l1comment->level = 0;
			$comments[] = $l1comment;

			foreach(Comment::whereIn('comment_id', array($l1comment->id))->get() as $l2comment) {
				$l2comment->level = 1;
				$comments[] = $l2comment;

				foreach(Comment::whereIn('comment_id', array($l2comment->id))->get() as $l3comment) {
					$l3comment->level = 2;
					$comments[] = $l3comment;
				}
			}
		}
		return json_encode($comments);
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
