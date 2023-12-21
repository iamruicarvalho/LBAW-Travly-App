<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <a href="{{ url('home') }}" class="back-btn">Back to Home</a>
        <!-- Tab navigation -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#users" data-toggle="tab">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#comments" data-toggle="tab">Comments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#posts" data-toggle="tab">Posts</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="users">
                <!-- Users management content -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name_ }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.banUser', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Ban</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <div class="tab-pane" id="comments">
                <!-- Comments management content -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)
                            <tr>
                                <td>{{ $comment->commentid }}</td>
                                <td>{{ $comment->id }}</td>
                                <td>{{ $comment->description_ }}</td>
                                <td>
                                    <!-- Remove Comment Button -->
                                    <form action="{{ route('admin.removeComment', $comment->commentid) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <p>   </p>
            <div class="tab-pane" id="posts">
                <!-- Comments management content -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Content</th>
                            <th>Author</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->postid }}</td>
                            <td>{{ $post->description_ }}</td>
                            <td>{{ $post->content_ }}</td>
                            <td>{{ $post->created_by }}</td>
                        
                            <td>
                                <form action="{{ route('admin.deletePost', $post->postid) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
