@extends('layouts.app')

@section('content')

{{-- Left Sidebar --}}
<div class="container">
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="{{ route('explore') }}">🔍 Explore</a></li>
                
                <li><a href="#">🔔 Notifications</a></li>
                <li><a href="{{ route('messages.showAllConversations') }}">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="{{ route('groups') }}">👥 Groups</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{ route('profile.show', auth()->id())  }}">👤 {{ auth()->user()->username }}</a>
            </div>
        </div>
    <div class="explore-container">

        <!-- Trends -->
        <div class="trending-section">
            <h2>Trending Topics</h2>
            <ul>
                <li><a href="{{ route('posts.by.hashtag', 'Travel') }}"><img src="https://i.pinimg.com/564x/41/0f/ec/410fecb2c951ee9b149b7cbc3fcaca09.jpg" alt="Travel"><p>#Travel</p></a></li>
                <li><a href="{{ route('posts.by.hashtag', 'Photography') }}"><img src="https://i.pinimg.com/564x/fe/db/89/fedb892f43b64a07acd96c306994f6e1.jpg" alt="Photography"><p>#Photography</p></a></li>
                <li><a href="{{ route('posts.by.hashtag', 'DeliciousFood') }}"><img src="https://i.pinimg.com/564x/14/fb/31/14fb31ea5ed085cc4b07616b2d187842.jpg" alt="Travel"><p>#DeliciousFood</p></a></li>
                <li><a href="{{ route('posts.by.hashtag', 'Art') }}"><img src="https://i.pinimg.com/564x/4e/a3/e9/4ea3e94865555f70d71a8c7a34cd42b0.jpg" alt="Photography"><p>#Art</p></a></li>
                <li><a href="{{ route('posts.by.hashtag', 'Wildlife') }}"><img src="https://i.pinimg.com/564x/2e/c0/77/2ec0773a1fcd847a5bd258ea4bba668e.jpg" alt="Travel"><p>#Wildlife</p></a></li>
                <li><a href="{{ route('posts.by.hashtag', 'Nature') }}"><img src="https://i.pinimg.com/564x/c3/f9/48/c3f9488a3e4e985a6cb62f9f12c5d3bb.jpg" alt="Photography"><p>#Nature</p></a></li>
                <li><a href="{{ route('posts.by.hashtag', 'Adventure') }}"><img src="https://i.pinimg.com/564x/65/81/c0/6581c0c76ea5e610d44f9d51c27348b2.jpg" alt="Travel"><p>#Adventure</p></a></li>
                <li><a href="{{ route('posts.by.hashtag', 'Beaches') }}"><img src="https://i.pinimg.com/564x/c8/50/c1/c850c158071975cb1402434292736609.jpg" alt="Photography"><p>#Beaches</p></a></li>
            </ul>
        </div>


        <!-- Content Discovery -->
        <div class="discovery-section">
            <h2>Trending Users</h2>
            <div class="suggested-users">
                <div class="user-card">
                    
                    <img src="https://media.istockphoto.com/id/1329031407/photo/young-man-with-backpack-taking-selfie-portrait-on-a-mountain-smiling-happy-guy-enjoying.jpg?s=612x612&w=0&k=20&c=WvjAEx3QlWoAn49drp0N1vmxAgGObxWDpoXtaU2iB4Q=" alt="User 1">
                    <p>Ethan</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="https://st.depositphotos.com/2069237/2453/i/450/depositphotos_24537371-stock-photo-hiker-portrait.jpg" alt="User 1">
                    <p>Isabella</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="https://thumbs.dreamstime.com/b/outdoor-fashion-portrait-handsome-stylish-tourist-man-posing-beach-outdoor-fashion-portrait-handsome-stylish-tourist-181622160.jpg" alt="User 1">
                    <p>Jackson</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="https://media.istockphoto.com/id/655931794/photo/backpacker-with-laptop-sits-on-the-top-view-point-under-mountain-settelment.jpg?s=612x612&w=0&k=20&c=8Hr0g7Nz9S1RxTI6Gix-OBhZvtD3IJDWkwoZjXVXmmw=" alt="User 1">
                    <p>Aria</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="https://media.istockphoto.com/id/1208321964/photo/enjoying-the-amazing-sights-with-some-of-his-favourite-sounds.jpg?s=612x612&w=0&k=20&c=cnPRuWxPShErW9XVEAkSpufFGFwIOPWxWbsrDRaTFFs=" alt="User 1">
                    <p>Mason</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="https://st4.depositphotos.com/12985790/20516/i/450/depositphotos_205163866-stock-photo-smiling-traveler-hat-backpack-tourist.jpg" alt="User 1">
                    <p>Wyatt</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="https://img.freepik.com/premium-photo/smiling-tourist-woman-with-backpack-looking-away-near-canyon_171337-67237.jpg" alt="User 1">
                    <p>Olivia</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="https://img.freepik.com/premium-photo/young-woman-with-hat-mountain-peak_216977-182.jpg" alt="User 1">
                    <p>Scarlett</p>
                    <button>Follow</button>
                </div>
                <!-- Add more user cards as needed -->
            </div>
        </div>

        <!-- Travel Inspiration -->
        <div class="trending-section">
            <h2>Explore Bucket List Cities</h2>
            <ul>
                <li>
                    <img src="https://media.tacdn.com/media/attractions-splice-spp-674x446/07/03/1c/9c.jpg" alt="Travel">
                    <p>Paris</p>
                </li>
                <li>
                    <img src="https://www.deferias.pt/wp-content/uploads/2021/10/Dublin-1.jpg" alt="Travel">
                    <p>Dublin</p>
                </li>
                <li>
                    <img src="https://static.independent.co.uk/s3fs-public/thumbnails/image/2018/04/10/13/tokyo-main.jpg" alt="Travel">
                    <p>Tokyo</p>
                </li>
                <li>
                    <img src="https://www.studying-in-uk.org/wp-content/uploads/2019/05/study-in-london-1068x641.jpg" alt="Travel">
                    <p>London</p>
                </li>
                <li>
                    <img src="https://cdn.contexttravel.com/image/upload/w_1500,q_60/v1661527052/blog/Our%20Top%2013%20Venice%20Attractions%20and%20Their%20Histories:%20What%20They%20Are%20and%20Why%20You%20Need%20to%20Visit%20%20%28venice%20attractions%29/venice_attractions_hero.jpg" alt="Travel">
                    <p>Venice</p>
                </li>
                <li>
                    <img src="https://www.feelporto.com/blog/wp-content/uploads/2021/07/28_visitar-Porto-1200x630.jpg" alt="Travel">
                    <p>Oporto</p>
                </li>
                <li>
                    <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0a/56/1d/c8/vista-dall-alto-del-bagno.jpg?w=600&h=400&s=1" alt="Travel">
                    <p>Budapest</p>
                </li>
                <li>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQRSkl8sMKRiCBf6lzInYccUhGXHs6N11vu10qc5gYoSjdG-IDuEVJ0II-bHMyolFHxMI8&usqp=CAU" alt="Travel">
                    <p>Vienna</p>
                </li>
            </ul>
        </div>
        <!-- Suggested Groups -->
        <div class="suggested-groups-section">
            <h2>Suggested Groups</h2>
            <div class="suggested-groups">
                <div class="group-card">
                    <img src="https://i.insider.com/5c8faf085dfa99080c7f1463?width=700" alt="Group 1">
                    <p>Travel Bloggers</p>
                    <button>Join</button>
                </div>
                <div class="group-card">
                    <img src="https://www.sonia-jaeger.com/wp-content/uploads/2016/08/office-koh-tao-e1471018338396.jpg" alt="Group 2">
                    <p>Digital Nomad</p>
                    <button>Join</button>
                </div>
                <div class="group-card">
                    <img src="https://assets.gqindia.com/photos/62c4236b5b6a37ba7f473d6f/1:1/w_1079,h_1079,c_limit/solo_travel_top-image.jpg" alt="Group 3">
                    <p>Solo Travellers</p>
                    <button>Join</button>
                </div>
                <div class="group-card">
                    <img src="https://imageio.forbes.com/specials-images/imageserve/5c5ca76131358e2a162e99ee/960x0.jpg?format=jpg&width=960" alt="Group 4">
                    <p>International Students</p>
                    <button>Join</button>
                </div>
                <div class="group-card">
                    <img src="https://i.pinimg.com/564x/dc/66/02/dc6602e5eca97eef0e9499bd1546c601.jpg" alt="Group 5">
                    <p>VanLife</p>
                    <button>Join</button>
                </div>
                <!-- Add more group cards as needed -->
            </div>
        </div>

    </div>
    <link href="{{ url('css/explore.css') }}" rel="stylesheet">
@endsection
