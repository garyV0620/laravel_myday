<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('mydays.store') }}">
            @csrf
            <textarea
                id="mind"
                name="message"
                placeholder="{{ __('What\'s on your mind?') }}"
                class="p-6 shadow-2xl block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md ">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Myday') }}</x-primary-button>
        </form>
      
        
        <div class="p-6 shadow-xl mt-6 bg-white shadow-sm rounded-lg divide-y my-days-content">

            <x-loader/>
            
            {{-- for reference only (remove by ajax) --}}
            {{-- @foreach ($mydays as $myday)
                <div class="p-6 flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800">{{ $myday->user->name }}</span>
                                <small class="ml-2 text-sm text-gray-600">{{ $myday->created_at->format('j M Y, g:i a') }}</small>
                                @unless ($myday->created_at->eq($myday->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                @endunless
                            </div>
                            @if ($myday->user->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('mydays.edit', $myday)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('mydays.destroy', $myday) }}">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link :href="route('mydays.destroy', $myday)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        <p class="mt-4 text-lg text-gray-900">{{ $myday->message }}</p>
                    </div>
                </div>
            @endforeach --}}
            {{-- end --}}
        </div>

    </div>
    <script>
        
        const  mydayDiv = $('.my-days-content');
        mydayDiv.removeClass('bg-white shadow-sm shadow-xl');

        //function to determine the ago in the comment or myday dates
        function timeAgo(inputDate1, inputDate2){
            const rtf = new Intl.RelativeTimeFormat("en", { numeric: "auto" });
            const MINPERDAY = 1440;
            const MINPERHOUR = 60;
            const MAXDAY = 2;
            let diff;
            let date1 = moment(inputDate1);
            let date2 = moment(inputDate2);

            if(!date1.isValid() || !date2.isValid()){
                return "invalid dates";
            }
            
            let diffMinute = date2.diff(date1, 'minute');

            if(Math.abs(diffMinute) >= MINPERDAY){
                let dayNow = rtf.format(Math.ceil(diffMinute/MINPERDAY), "day");

                if(Math.abs(Math.ceil(diffMinute/MINPERDAY)) > MAXDAY){
                    return moment(date2).format('D MMM YYYY, h:mm a');
                }else if(dayNow != 'today'){ 
                    return dayNow;
                }
            }
            if(Math.abs(diffMinute) >= MINPERHOUR){
                let hourNow = Math.ceil(diffMinute/MINPERHOUR);
                let minNow = Math.abs(diffMinute - (hourNow * MINPERHOUR ));
          
                return (minNow > 0) ? minNow + " min " + rtf.format(hourNow, "hour") : rtf.format(hourNow, "hour");
            }
            return (diffMinute == 0) ? "just now" : rtf.format(diffMinute, "minute");
            
        }

        function getMyday(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           
            $.ajax({
                type: 'GET',
                url: 'mydays/ajaxMyday',
                dataType: 'json',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    // console.log(response);
                    
                    var completeMyday = '';
                    $.each(response, function(key, myday){
                        var dateStr = myday.created_at;
                        var formattedDate =  timeAgo(moment(), dateStr);

                        let editUrl = '{{ route("mydays.edit", ":id") }}'.replace(':id', myday.id);
                        let deleteUrl = '{{ route("mydays.destroy", ":id") }}'.replace(':id', myday.id);
                        let visitOther = '{{ route("mydays.visitOther", ["id"=>":id"]) }}'.replace(':id', myday.user.id);

                        let isEdited = '';
                        if (myday.created_at != myday.updated_at) {
                            isEdited = `
                                 <small class="text-sm text-gray-600"> &middot; {{ __('edited') }} </small>
                            `;
                        }
                        let isUser ='';
                        if (myday.user.id == {{auth()->id()}}) {
                            isUser = `
                                <div class='flex'>
                                    <x-add-edit-link href="${editUrl}">
                                        <img src="{{ asset('img/PencilSquare.svg') }}">
                                        {{ __('Edit') }}
                                    </x-add-edit-link>
                                    <div class="on-edit">
                                        <form method="POST" action="${deleteUrl}">
                                            @csrf
                                            @method('delete')
                                            <x-add-edit-link href="${deleteUrl}" onclick="event.preventDefault();if(!confirm('Are you sure?')){return false}; this.closest('form').submit();">
                                                <img src="{{ asset('img/Trash.svg') }}">
                                                {{ __('Del') }}
                                            </x-add-edit-link>
                                        </form>
                                    </div>
                                </div>
                            `;
                        }
                        let comments ='';
                        if(myday.comment.length > 0){
                            $.each(myday.comment, function(key, comment){
                                var formattedDateComment =  timeAgo(moment(), comment.created_at);
                                let visitOtherComment = '{{ route("mydays.visitOther", ["id"=>":id"]) }}'.replace(':id', comment.user_id);
                                let isEditComment ='';
                                if (comment.created_at != comment.updated_at) {
                                    isEditComment = `
                                        <small class="text-xs text-gray-600"> &middot; {{ __('edited') }} </small>
                                    `;
                                }
                                comments += `
                                    <div class='bg-gray-100 max-w-full pl-3 pt-1 mb-1'>
                                        <a href="${visitOtherComment}"><span class="text-slate-600 font-bold text-base">${comment.author.name}</span></a> <span  class="text-slate-700 text-base" >${comment.comment}</span>
                                        <p class="-mt-2"><span class="text-xs text-slate-400">${formattedDateComment}</span> ${isEditComment}</p>
                                    </div>
                                `;
                            });
                        }
                      
    
                        let mydayBody = `
                        <div class="p-6 flex space-x-2 mb-5 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <a href="${visitOther}"><span class="text-slate-400">${myday.user.name}</span></a>
                                        <small class="ml-2 text-sm text-slate-400">${formattedDate}</small>
                                        ${isEdited}
                                    </div>
                                   ${isUser}
                                </div>
                                <p class="mt-4 text-2xl text-gray-900">${myday.message}</p>
                                <div class="mt-5 commentSection" data-id="${myday.id}">
                                    ${comments}
                                </div>
                                <textarea style="border:dashed 1px black;" class="w-full mt-5 border-double" rows="2" onfocus="isCommentClick=true;clearInterval(ajaxTick)" onfocusout="var isType=$(this).val(); if(isCommentClick && isType==''){ajaxTick = setInterval(getMyday,2000);} isCommentClick=false;"></textarea>
                                <input type="hidden" value="${myday.id}" class='myday_id'>
                                <x-secondary-button class="float-right" onclick="var textareaVal = $(this).siblings('textarea') ;commentAjax(${myday.id}, textareaVal.val()); if(textareaVal.val() != ''){ajaxTick = setInterval(getMyday,2000)} $('textarea').val('')" >{{ __('Comment') }}</x-secondary-button>
                            </div>
                        </div>
                        `;
                        completeMyday += mydayBody;
                    });
                   
                    mydayDiv.empty();
                    mydayDiv.addClass('bg-white shadow-sm shadow-xl');
                    mydayDiv.append(completeMyday);
                },
                error: function(jqXHR,error, errorThrown) {  
                    window.location.href= "{{ route('login') }}";
                }
            });
        }

        function commentAjax(myday_ids, textareaVal){
            // alert(myday_ids + textareaVal );
            if(textareaVal != ''){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                $.ajax({
                    type: 'post',
                    url: "{{ route('comments.store') }}",
                    dataType: 'json',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        myday_id : myday_ids,
                        comment : textareaVal
                    },
                    success:function(response){
                        // console.log(response);
                        let visitOtherComment = '{{ route("mydays.visitOther", ["id"=>":id"]) }}'.replace(':id', response.commentsInfo.user_id);
                        var formattedDateComment =  timeAgo(moment(), response.commentsInfo.created_at);
                        let isEditComment ='';
                        if (response.commentsInfo.created_at != response.commentsInfo.updated_at) {
                            isEditComment = `
                                <small class="text-xs text-gray-600"> &middot; {{ __('edited') }} </small>
                            `;
                        }
                        let newComment = `
                            <div class='bg-gray-100 max-w-full pl-3 pt-1 mb-1'>
                                <a href="${visitOtherComment}"><span class="text-slate-600 font-bold text-base">${response.user.name}</span></a> <span  class="text-slate-700 text-base" >${response.commentsInfo.comment}</span>
                                <p class="-mt-2"><span class="text-xs text-slate-400">${formattedDateComment}</span> ${isEditComment}</p>
                            </div>
                        `;
                        $('[data-id="'+myday_ids+'"]').append(newComment);
                    },
                    error:function(){

                    }
                });
            }
          
        }

        var ajaxTick = setInterval(getMyday,2000);
        // getMyday();
        
        //typing effect
        $(document).ready(function(){
            var isCommentClick;
            var ph = "What's on your mind? ",
            searchBar = $('#mind'),
            // placeholder loop counter
            phCount = 0;

            // function to return random number between
            // with min/max range
            function randDelay(min, max) {
                return Math.floor(Math.random() * (max-min+1)+min);
            }

            // function to print placeholder text in a 
            // 'typing' effect
            function printLetter(string, el) {
                // split string into character seperated array
                var arr = string.split(''),
                    input = el,
                    // store full placeholder
                    origString = string,
                    // get current placeholder value
                    curPlace = $(input).attr("placeholder"),
                    // append next letter to current placeholder
                    placeholder = curPlace + arr[phCount];
                    
                setTimeout(function(){
                    // print placeholder text
                    $(input).attr("placeholder", placeholder);
                    // increase loop count
                    phCount++;
                    // run loop until placeholder is fully printed
                    if (phCount < arr.length) {
                        printLetter(origString, input);
                    }
                // use random speed to simulate
                // 'human' typing
                }, randDelay(50, 90));
            }  

            // function to init animation
            function placeholder() {
                $(searchBar).attr("placeholder", "");
                printLetter(ph, searchBar);
            }
            placeholder();
        });
     
    </script>
  
</x-app-layout>