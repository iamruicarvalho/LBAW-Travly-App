@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="{{ route('explore') }}">🔍 Explore</a></li>
                <li><a href="{{ route('notifications') }}">🔔 Notifications</a></li>
                <li><a href="{{ route('messages.showAllConversations') }}">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="{{ route('groups.showGroups') }}">👥 Groups</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{ route('profile.show', auth()->id())  }}">👤 {{ auth()->user()->username }}</a>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="welcome-post">
            @php
                $user = App\Models\User::find($post->created_by);
            @endphp
            <div class="post-header">
                <p class="user-name">{{ $user->name_ }}</p>
            </div>
            <div class="post-content">
                <p class="post-description">{{ $post->description_ }}</p>
            </div>
            @if(pathinfo($post->content_, PATHINFO_EXTENSION) == 'png' || pathinfo($post->content_, PATHINFO_EXTENSION) == 'jpg' || pathinfo($post->content_, PATHINFO_EXTENSION) == 'jpeg')
            <div class="post-image">
                <img src="{{ asset('postimage/' . $post->content_) }}">
            </div>
            @endif

            <div class="post-details">
                <a href="{{ url('/posts/' . $post->postid . '/likes') }}" class="show-details"> {{ $post->likes_ }} likes</a>
                <a class="show-details"> {{ \Carbon\Carbon::parse($post->time_)->diffForHumans() }}</a>
            </div>

            @if (Auth()->user() == $user)
                <a onclick="return confirm('Are you sure you want to delete this?')" href="{{ url('my_posts_del', $post->postid) }}" class="btn btn-danger">Delete</a>
                <a href="{{ url('post_update_page', $post->postid) }}" class="btn btn-primary">Update</a>
            @endif

            <!-- Adicione um formulário para adicionar novos comentários -->
            @auth
                @if (Auth()->user()->id == $post->created_by)
                    <!-- <p>You cannot comment on your own post.</p> -->
                @else
                    <form action="{{url('user_comment')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-post-section">
                            <textarea name="comment" class="comment-input" placeholder="Add a comment..."></textarea>
                            <input type="hidden" name="postid" value="{{ $post->postid }}">
                            <input type="submit" value="Add Comment" class="btn btn-outline-secondary">
                        </div>
                    </form>
                @endif
            @else
                <p>Please log in to leave a comment.</p>
            @endauth

            <div class="comments-section">
                <h3>Comments:</h3>

                @forelse($comments as $comment)
                    <div class="comment-item">
                        <div class="comment-details">
                            <p class="comment-description">{{ $comment->description_ }}</p>
                            Commented by: <a href="{{ route('profile.show', $comment->user->id) }}" class="comment-author">{{ $comment->user->username }}</a>
                            <p class="comment-time">Posted on: {{ \Carbon\Carbon::parse($comment->time_)->diffForHumans() }}</p>
                            
                            @if (Auth()->user()->id == $comment->id)
                                <!-- this can only appear if I am the author of the comment -->
                                <form action="{{ url('comments/' . $comment->commentid) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="comment">{{ $comment->description_ }}</textarea>
                                    <button type="submit">Salvar Edição</button>
                                </form>
                            @endif
                            @if (Auth()->user()->id == $post->created_by || Auth()->user()->id == $comment->id)     
                                <!-- $comment->id refers to the id of the comment author -->
                                <!-- this can only appear if I am the owner of the account or the author of the comment -->
                                <form action="{{ route('comments.destroy', $comment->commentid) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure to delete this?')">Delete</button>
                                </form>
                            @endif
                        </div>
                        <hr style="background-color: black; height: 2px;">
                    </div>
                @empty
                    <p>No comments yet.</p>
                @endforelse
            </div>

        </div>



        {{-- Right Sidebar --}}
        <div class="right-sidebar">
            <div class="search-bar">
                {{-- Your search bar HTML goes here --}}
                <input type="text" placeholder="🔍 Search...">
            </div>
            <div class="suggested-groups">
            <!-- Your suggested groups content goes here -->
            <h3>Suggested Groups</h3>
            <!-- Display suggested groups -->
            <ul>
                <li>
                <div class="group-item">
                    <img src="https://visitworld.today/media/blog/previews/KXyZ9HqVwd5zhbBvkREQrjJTLMRQ9JQW8VETjX23.webp" alt="Italia Lovers Avatar">
                    <div class="group-details">
                    <p class="group-name">Italia Lovers</p>
                    <button class="join-button">Join</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="group-item">
                    <img src="https://static.independent.co.uk/2022/08/24/12/iStock-1146262403.jpg?width=1200&height=900&fit=crop" alt="I heart Japan Avatar">
                    <div class="group-details">
                    <p class="group-name">I heart Japan</p>
                    <button class="join-button">Join</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="group-item">
                    <img src="https://heymondo.com/blog/wp-content/uploads/2022/03/10_tips_to-travel-on-a-budget.jpg" alt="Budget Travel Avatar">
                    <div class="group-details">
                    <p class="group-name">Budget travel</p>
                    <button class="join-button">Join</button>
                    </div>
                </div>
                </li>
                <a href="{{ route('explore') }}" class="see-more-link">See More</a>

                <!-- Add more suggested groups as needed -->
            </ul>
            </div>

            <div class="people-near-you">
            <!-- Your people near you content goes here -->
            <h3>Trending Users</h3>
            <!-- Display people near you -->
            <ul>
                <li>
                <div class="person-item">
                    <img src="https://bgn2018media.s3.amazonaws.com/wp-content/uploads/2021/08/27082436/gettyimages-1201669316-612x612-1.jpg" alt="Bessie Cooper Avatar">
                    <div class="person-details">
                    <p class="person-name">Bessie Cooper</p>
                    <button class="follow-button">Follow</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="person-item">
                    <img src="https://img.freepik.com/free-photo/woman-mountain-with-travel-bag-looking-map_1303-11185.jpg" alt="Olivia Silva Avatar">
                    <div class="person-details">
                    <p class="person-name">Olivia Silva</p>
                    <button class="follow-button">Follow</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="person-item">
                    <img src="https://jooinn.com/images/travelling-boy-1.jpg" alt="Joseph Martini Avatar">
                    <div class="person-details">
                    <p class="person-name">Joseph Martini</p>
                    <button class="follow-button">Follow</button>
                    </div>
                </div>
                </li>
                <a href="{{ route('explore') }}" class="see-more-link">See More</a>

                <!-- Add more people as needed -->
            </ul>
            </div>

            <div class="trending-hashtags">
                {{-- Your trending hashtags content goes here --}}
                <h3>Trending Hashtags</h3>
                <!-- Display trending hashtags -->
                <ul>
                    <li>#Travel</li>
                    <li>#Adventure</li>
                    <li>#Explore</li>
                    <!-- Add more hashtags as needed -->
                </ul>
                <a href="{{ route('explore') }}" class="see-more-link">See More</a>
            </div>
        </div>
    </div>
    <link href="{{ url('css/home.css') }}" rel="stylesheet">
@endsection
