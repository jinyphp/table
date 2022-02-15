<x-theme theme="admin.sidebar2">
    <x-theme-layout>

        <!-- start page title -->
        @if (isset($actions['view_title']))
            @includeIf($actions['view_title'])
        @endif
        <!-- end page title -->

        @includeIf("jinytable::ajax.ajaxCreate")

        <script>
            let form = document.querySelector('form#main');
            form.addEventListener('submit', function(e){
                e.preventDefault();

                let ajax = form.querySelector('[name="_ajax"]');
                if(ajax) {
                    // ajax 호출
                    let url = form.action;
                    let formData = new FormData(this);
                    let searchParams = new URLSearchParams();
                    for(let pair of formData) {
                        searchParams.append(pair[0], pair[1]);
                    }

                    fetch(url, {
                        method:'post',
                        body: searchParams
                    }).then(function(response){
                        return response.text();
                    }).then(function(text){
                        //console.log(text);
                    }).catch(function(error){
                        //console.log(error);
                    });

                } else {
                    // 일반 submit redirect
                    form.submit();
                }



            });

        </script>

        {{-- 퍼미션 알람--}}
        {{--
        @include("jinytable::error.popup.permit")
        --}}
    </x-theme-layout>
</x-theme>
