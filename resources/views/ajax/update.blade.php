<x-theme theme="admin.sidebar2">
    <x-theme-layout>
        <!-- start page title -->
        @if (isset($actions['view_title']))
            @includeIf($actions['view_title'])
        @endif
        <!-- end page title -->

        @includeIf("jinytable::ajax.ajaxUpdate")

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
                        method:'put',
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

            let btnDelete = form.querySelector('[name="_delete"]');
            btnDelete.addEventListener('click',function(e){
                e.preventDefault();

                let confirm = window.confirm("I really want to delete it.");
                if(confirm) {
                    let ajax = form.querySelector('[name="_ajax"]');
                    if(ajax) {
                        let url = form.action;
                        let id = form.querySelector('[name="_id"]').value;
                        let token = form.querySelector('[name="_token"]').value;
                        //console.log("delete"+id);
                        //console.log("token"+token);

                        fetch(url, {
                            method: 'DELETE',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({
                                _token: token,
                                id:id
                            })
                        }).then(function(response){
                            return response.text();
                        }).then(function(text){
                            console.log(text);
                        }).catch(function(error){
                            //console.log(error);
                        });
                    } else {
                        let method = form.querySelector('[name="_method"]');
                        method.value = "DELETE";
                        form.submit();
                    }
                }

            });
        </script>

        {{-- 퍼미션 알람--}}
        {{--
        @include("jinytable::error.popup.permit")
        --}}
    </x-theme-layout>
</x-theme>
