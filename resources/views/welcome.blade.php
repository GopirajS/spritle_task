
<x-app-layout>
    <div class="w-75 mx-auto">
        <a class="" href="{{route('create.post')}}">
            <button type="button" class="btn btn-outline-secondary">Create you blog</button>
        </a>

        
        @foreach ($getAllPost as $item)
            <div class="card mt-2 mb-5">
                <div class="card-header d-flex justify-content-between">
                    <span>
                        {{ $item->user->name ?? 'Unknown User' }}  <!-- Display the user name -->
                    </span>
                    <span class="text-muted">
                        {{ $item->created_at->format('d M Y, h:i A') }}  <!-- Display post creation time -->
                    </span>
                </div>
                <div class="card-body">
                  
                    {!! Str::markdown($item->post) !!}

                  
                </div>
            </div>
        @endforeach

        
    </div>

</x-app-layout>