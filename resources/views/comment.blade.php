<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Comment</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">



        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div style="height:100%; overflow-y: auto" class="content" id="displaycomments_app">
				<div v-for="comment in comments" v-bind:key="comment.id" v-bind:comment="comment.comment">
					[[ comment.comment ]]
					<p>[[comment.name]]<i class="fas fa-reply" v-on:click="replyTo(comment.id, comment.comment, comment.level)"></i></p>
				</div>
			</div>
            <div class="content" id="comments_app">
                <div class="title m-b-md">
                    Comments
                </div>

                <div class="links">
					{!! Form::open(['url' => '/comment', '@submit.prevent'=> 'submitComment']) !!}

					<p v-if="errors.length">
					  <b>Please correct the following error(s):</b>
					  <ul>
					    <li v-for="error in errors">[[ error ]]</li>
					  </ul>
					</p>
					<p v-if="originalComment">
						Replying to: [[ originalComment ]]
					</p>


					<div>
					<?php
					    //
						echo Form::label('name', 'Your Name');
						echo Form::text('name', '', ['v-model'=>'name']);
					?>
					</div>
					<div>
					<?php
					
						echo Form::label('comment', 'Your Comment');
						echo Form::textarea('comment', '', ['v-model'=>'comment']);
					?>
					</div>
					<div>
					<?php
						echo Form::submit('Comment!', ['v-on:click' => 'submitComment']);
					?>
					</div>
					
					{!! Form::close() !!}
                </div>
            </div>
        </div>
		<script>
			//comment view
			var displaycomments = new Vue({
				el: '#displaycomments_app',
				delimiters: ['[[', ']]'],
				data: {
					comments: {!! $comments !!}
				},
				methods: {
					addComment: function(comment) {
						this.comments.push(comment);
						vm.$forceUpdate();
						console.log(this.comments);
					},
					replyTo: function(comment_id, comment, level) {
						if(level == 2) {
							for(i = 0; i < displaycomments.comments.length; i++) {
								if(displaycomments.comments[i].id == comment_id) {
									comments.replyTo = displaycomments.comments[i].comment_id;
								}
							}
						}
						else {
							comments.replyTo = comment_id;
						}
						comments.originalComment = comment;
						comments.comment = '';
						comments.name = '';
					}
				}
			});

			var comments = new Vue({
				el: '#comments_app',
				delimiters: ['[[', ']]'],
				data: {
					name: '',
					comment: '',
					replyTo: 0,
					errors: [],
					originalComment: ''
				},
				methods: {
					submitComment: function(e) {
						e.preventDefault();
						if(this.name && this.comment) {
							console.log('validated .... sending ....');
							var payload = {name: this.name, comment: this.comment, replyTo: this.replyTo };
							axios.post('/comment', payload).then(function(data, status, request) {
								this.response = data;
							}).catch(function(data, status, request) {
									console.log('error');
									console.log(status);
							});
							// reinitialize all values
							this.name = '';
							this.comment = '';
							this.replyTo = 0;
							displaycomments.addComment(payload);
						}

						this.errors = [];

						if(!this.name) {
							this.errors.push('Name required');
						}
						if(!this.comment) {
							this.errors.push('Comment required');
						}
						e.preventDefault();
					}
				}
			});
		</script>


    </body>
</html>
