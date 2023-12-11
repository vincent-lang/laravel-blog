<x-app-layout>
    <aside class="sticky top-20">
        <div class="absolute mt-20 ml-32 bg-gray-400 p-5 rounded-lg text-blue-600 overflow-x-auto max-h-80">
            <div class="text-black font-bold pb-4">
                <div class="mb-4">
                    <h1>side menu:</h1>
                </div>
            </div>
            <div class="text-blue-600 font-normal hover:bg-gray-500 p-0.5 rounded-lg">
                <a href="#top">back to the top</a>
            </div>
            <div>
                @foreach($posts as $post)
                <div class="flex-row hover:bg-gray-500 rounded-lg p-1 overflow-x-auto">
                    <a href="#{{$post->id}}">{{$post->title}}</a>
                </div>
                @endforeach
            </div>
        </div>
    </aside>
    <div id="top" class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('posts.store') }}">
            @csrf
            <input name="title" placeholder="{{ __('title?') }}" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mb-4">{{ old('title') }}</input>
            <textarea name="message" placeholder="{{ __('What\'s on your mind?') }}" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Post') }}</x-primary-button>
        </form>

        <div class="mt-6 shadow-sm divide-y">
            @foreach ($posts as $post)
            <div id="{{$post->id}}" class="p-6 flex space-x-2 bg-white mt-6 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ $post->user->name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{ $post->created_at->format('j M Y, g:i a') }}</small>
                            @unless ($post->created_at->eq($post->updated_at))
                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>
                        @if ($post->user->is(auth()->user()))
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('posts.edit', $post)">
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('posts.delete', $post)">
                                    {{ __('Delete') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif
                    </div>
                    <p class="mt-4 text-lg text-gray-900 font-bold mb-8 mt-6">{{ $post->title }}</p>
                    <p class="mt-4 text-lg text-gray-900 mb-4">{{ $post->message }}</p>
                    @if ($post->user->isNot(auth()->user()))
                    <div>
                        <form method="POST" action="{{ route('comments.store', $post) }}">
                            @csrf
                            <textarea name="content" placeholder="{{ __('Comment here?') }}" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            <x-primary-button class="mt-4">{{ __('Post') }}</x-primary-button>
                        </form>
                    </div>
                    @endif
                    <h3 class="mt-4 font-bold text-sky-600">comments:</h3>
                    @foreach($comments as $comment)
                    @if($post->id == $comment->post_id)
                    <div class="flex flex-col border-solid border-2 border-grey-500 rounded-lg pl-1 mt-6">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            @foreach($users as $user)
                            @if($comment->user_id == $user->id)
                            <span class="ml-6">{{$user->name}}</span>
                            @endif
                            @endforeach
                            <small class="ml-6 text-sm text-gray-600">{{ $comment->created_at->format('j M Y, g:i a') }}</small>
                            @unless ($comment->created_at->eq($comment->updated_at))
                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>
                        <div class="mt-2 ml-4">
                            {{$comment->content}}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>