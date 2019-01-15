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

            <div class="content" id="displaycomments_app">
				<p v-if="comments.length">
					<ul> <li v-for="comment in comments">[[ comment.name ]] : [[ comment.comment ]]</li> </ul>
				</p>
				<p v-else>
					There are no comments yet
				</p>
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
			var displaycomments = new Vue({
				el: '#displaycomments_app',
				delimiters: ['[[', ']]'],
				data: {
					comments: {!! $comments !!}
				}
			});

			var comments = new Vue({
				el: '#comments_app',
				delimiters: ['[[', ']]'],
				data: {
					message: 'Hello!',
					name: '',
					comment: '',
					errors: []
				},
				methods: {
					submitComment: function(e) {
						e.preventDefault();
						if(this.name && this.comment) {
							console.log('validated .... sending ....');
							var payload = {name: this.name, comment: this.comment};
							axios.post('/comment', payload).then(function(data, status, request) {
								this.response = data;
							}).catch(function(data, status, request) {
									console.log('error');
									console.log(status);
							});
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
