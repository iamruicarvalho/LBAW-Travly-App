<div class="d-flex">
        <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="twbs" width="32" height="32" class="rounded-circle flex-shrink-0">
        <div class="d-flex gap-2 w-100 justify-content-between">
                <div>

                @switch( $notification->get_notif_type() ) 
                        @case('commented_post')
                                <h5 class="mb-0">Commented Post</h5>
                        @break
                        @case('liked_post')
                                <h5 class="mb-0">Liked Post</h5>
                        @break
                        @default
                                <h5 class="mb-0">Follow Request</h5>
                        @break
                @endswitch

                <p class="mb-0 opacity-75"> {{$notification->description_}} </p>
                </div>
                <div class="d-flex justify-content-end flex-row">
                        <div class="p-2" >
                                @if( $notification->seen )
                                        <p class="opacity-50 text-nowrap lead"> seen </p>
                                @else
                                        <p class="opacity-50 text-nowrap lead"> new </p>
                                @endif
                        </div>  
                        <p class="opacity-50 text-nowrap lead p-2"> {{$notification->time_}} </p>
                </div>
        </div>
        </a>
</div>