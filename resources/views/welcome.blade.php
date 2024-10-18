<x-app-layout>
    <div class="w-75 mx-auto">
        <a class="" href="{{route('create.post')}}">
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
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <!-- Like Button with Icon -->
                        <button class="btn btn-outline-danger like-button" data-post-id="{{ $item->id }}">
                            <i class="fa {{ auth()->user() && $item->likes->where('user_id', auth()->id())->count() ? 'fa-heart' : 'fa-heart-o' }}"></i>
                            Like
                        </button>
                        <span class="ml-2">{{ $item->likes->count() }} Likes</span>
                    </div>

                    <div class="text-muted">
                        <span class="small">Posted on: {{ $item->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mt-3 border-top pt-2">
                    <h6>Comments:</h6>
                    @if($item->comments->isEmpty())
                        <p class="text-muted">No comments yet. Be the first to comment!</p>
                    @else
                        @foreach ($item->comments as $comment)
                            <div class="mb-2">
                                <strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}
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

                $.ajax({
                    url: '/post/' + postId + '/like',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (button.find('i').hasClass('fa-heart')) {
                            button.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                        } else {
                            button.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
