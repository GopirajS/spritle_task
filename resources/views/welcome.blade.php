<x-app-layout>
    <div class="w-75 mx-auto">
        <a class="" href="{{ route('create.post') }}">
            <button type="button" class="btn btn-outline-secondary">Create your blog</button>
        </a>
      
        @foreach ($getAllPost as $item)
            <div class="card mt-2 mb-5 shadow-sm">
                <div class="card-header d-flex justify-content-between bg-light">
                    <span class="font-weight-bold">
                        {{ $item->user->name ?? 'Unknown User' }} <!-- Display the user name -->
                    </span>
                    <span class="text-muted small">
                        {{ $item->created_at->format('d M Y, h:i A') }} <!-- Display post creation time -->
                    </span>
                </div>

                <div class="card-body">
                    {!! Str::markdown($item->post) !!}
                </div>

                <div class="card-footer bg-white">
                    
                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted">
                            <strong>{{ $item->comments->count() }}</strong> Comments
                        </span>

                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-danger like-button" data-post-id="{{ $item->id }}">
                                <i class="fa {{ auth()->user() && $item->likes->where('user_id', auth()->id())->count() ? 'fa-heart' : 'fa-heart-o' }}"></i>
                            </button>
                            <span class="ml-2">{{ $item->likes->count() }} </span>  <span class="p-2" >Likes</span>
                        </div>
                       
                        
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-3 border-top pt-2">
                        <h6>Comments:</h6>
                        @if ($item->comments->isEmpty())
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        @else
                            @foreach ($item->comments as $comment)
                                <div class="mb-2 d-flex align-items-start">
                                    <div class="mr-2">
                                        <div class="badge badge-secondary rounded-circle"
                                            style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; background-color: black">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                           
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}
                                        <br>
                                        <span
                                            class="text-muted small">{{ $comment->created_at->format('d M Y, h:i A') }}</span>
                                       
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Comment Form -->
                    <form action="{{ route('post.comment', $item->id) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="form-group">
                            <textarea name="comment" class="form-control" rows="2" placeholder="Write a comment..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">Post Comment</button>
                    </form>
                </div>
            </div>
        @endforeach


    </div>

    <script>
        $(document).ready(function() {
            $('.like-button').click(function() {
                var postId = $(this).data('post-id');
                var button = $(this);
                var likesCountSpan = button.next('span'); // Get the likes count span
    
                $.ajax({
                    url: '/post/' + postId + '/like',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Increment likes count
                            var currentCount = parseInt(likesCountSpan.text());
                            likesCountSpan.text(currentCount + 1);
                            toastr.success(response.message);
                        } else {
                            // Decrement likes count
                            var currentCount = parseInt(likesCountSpan.text());
                            if (currentCount > 0) { // Ensure count doesn't go below 0
                                likesCountSpan.text(currentCount - 1);
                            }
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</x-app-layout>
