<div class="notif_container">
    <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="twbs" width="32" height="32" class="pf_pic">
    <div class="notif_content">
        
        @switch( $notification->get_notif_type() ) 
                @case('commented_post')
                    <h5 class="title">Commented Post</h5>
                @break
                @case('liked_post')
                    <h5 class="title">Liked Post</h5>
                @break
                @default
                    <h5 class="title">Follow Request</h5>
                @break
        @endswitch

        <p class="notif_description"> {{$notification->description_}} </p>
     </div>
     <div class="notif_status" >
        @if( $notification->seen )
                <p class="status-text opacity-50 text-nowrap lead"> seen </p>
        @else
            <p class="status-text opacity-50 text-nowrap lead"> new </p>
        @endif
     </div> 
     <p class="time opacity-50 text-nowrap lead p-2"> {{$notification->time_}} </p>
</div>