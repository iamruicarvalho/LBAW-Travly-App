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
     @if($notification->get_notif_type() == "request_follow")
        <div class="follow_req">
        @if(Auth::user()->canAcceptRequestFrom($notification->sends_notif))
            <form method="POST" action="{{ route('request.acceptFriend') }}" id="accept_req">
                @csrf
                <input type="hidden" id="to" name="to" value="{{$notification->sends_notif}}" />
                <input type="hidden" id="notifType" name="notifType" value='accepted_follow' />
                <button type="submit" class="a-r_button">Accept</button>
            </form>
            <form method="POST" action="{{ route('request.rejectFriend') }}" id="accept_req">
                @csrf
                <input type="hidden" id="to" name="to" value="{{$notification->sends_notif}}" />
                <input type="hidden" id="notifType" name="notifType" value='rejected_follow' />
                <button type="submit" class="a-r_button">Reject</button>
            </form>
        @else
            @if(Auth::user()->isFriend($notification->sends_notif))
                <p class="notif_unav"> Follow request already accepted </p>
            @else
                <p class="notif_unav"> Friend request unavailable </p>
            @endif
        @endif
        </div>
     @endif
     <div class="notif_status" >
        @if( $notification->seen )
                <p class="status-text opacity-50 text-nowrap lead"> seen </p>
        @else
            <p class="status-text opacity-50 text-nowrap lead"> new </p>
        @endif
     </div> 
     <p class="time opacity-50 text-nowrap lead p-2"> {{$notification->time_}} </p>
</div>