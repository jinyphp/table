

{{-- 삭제버튼 --}}
{{--
<x-button danger small id="selected-delete" disabled>
    {{$slot}}
</x-button>
--}}

<x-button danger small id="selected-delete" wire:click="popupDeleteOpen" disabled>
    {{$slot}}
</x-button>

<script>
    let checkid = document.querySelectorAll('input[name=ids]:checked');
    console.log(checkid);
    if(checkid.length >0) {
        document.querySelector("#selected-delete").removeAttribute("disabled");
    } else {
        document.querySelector("#selected-delete").setAttribute("disabled", true);
    }
</script>



@push('scripts')
<script>



    function selectTableRow()
    {
        // 선택, 해제
        var selected = 0;
        let datatable = document.querySelector(".datatable");
        let rowCheck = datatable.querySelectorAll('tbody tr [type=checkbox]');
        let allCheck = datatable.querySelector("thead tr [type=checkbox]");

        var selectedNum = document.querySelector("#selected-num");
        var btnDelete = document.querySelector("#selected-delete");
        if(btnDelete) {
            btnDelete.addEventListener("click",function(e){
                // 체크된 항목만 선택
                let item = [];
                let check = datatable.querySelectorAll('input[name=ids]:checked');
                check.forEach(el=>{
                    // 항목의 value값만을 추출하여 배열에 저장
                    item.push(parseInt(el.attributes.value.value))
                });
                //console.log(item);
                //ajaxCheckDelete(item);
            });
        }

        function ajaxCheckDelete(item)
        {
            let token = document.querySelector('input[name=_token]').value;
            if(token) {
                //console.log(token);
                //AJAX 호출
                if (item.length>0 && confirm("are you delete")) {
                    //console.log(item);
                    let url = "{{ url()->current() }}";
                    //console.log(url);
                    // AJAX 선택삭제
                    fetch(url, {
                        method: 'DELETE',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            _token: token,
                            ids:item
                        })
                    }).then(function(response) {
                        response.json().then(function(json) {
                            // process your JSON further
                            //console.log(json);
                            Livewire.emit('refeshTable');
                        });
                    }).catch(function(error) {
                        // Error
                        //console.log(error);
                    });

                }
            } else {
                console.log("cannot find csrf token");
            }

        }


        allCheck.addEventListener("click",function(e){
            checkAll(e.target.checked);

            if(e.target.checked) {
                selected = rowCheck.length;
                if(btnDelete) btnDelete.removeAttribute("disabled");
            } else {
                selected = 0;
                if(btnDelete) btnDelete.setAttribute("disabled", true);
            }
        });

        rowCheck.forEach(el=> {

            el.addEventListener("click",function(e){
                let tr = findTagParent(e.target, "tr");

                if(e.target.checked) {
                    _selected(tr);
                    selected++;
                } else {
                    _unselected(tr);
                    selected--;
                }

                selectedNum.innerText = selected;

                if (selected == rowCheck.length) {
                    allCheck.checked = true;
                } else {
                    allCheck.checked = false;
                }

                if(btnDelete) {
                    if(selected) {
                        btnDelete.removeAttribute("disabled");
                    } else {
                        btnDelete.setAttribute("disabled",true);
                    }
                }

            });

        });

        function checkAll(checked) {
            selected = 0;
            rowCheck.forEach(el=> {
                el.checked = checked;

                let tr = findTagParent(el, "tr");
                if (tr) {
                    if (checked) {
                        _selected(tr);
                        selected++;
                    } else {
                        _unselected(tr);
                    }
                }
            });

            selectedNum.innerText = selected;
        }

        function _selected(tr) {
            tr.classList.add('row-selected');
        }

        function _unselected(tr) {
            tr.classList.remove('row-selected');
        }

        function findTagParent(el, tag) {
            tag = tag.toUpperCase();
            while(el.tagName != tag) {
                el = el.parentElement;
            }
            return el;
        }

    }

    // document.addEventListener("DOMContentLoaded", selectTableRow());
    selectTableRow();

</script>
@endpush
