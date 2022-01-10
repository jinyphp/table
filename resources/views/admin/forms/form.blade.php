<div>
    <style>
        .dragtab {

        }

        .dragtab.dragging {
            opacity: .5;
        }
    </style>
    <style>
        /** jiny tabbar with radio */
        .jiny.tabbar {
            display: flex;
            flex-wrap: wrap;
        }

        .jiny.tabbar input[name="__tabbar"] {
            display: none;
        }
        .jiny.tabbar .tab-header {
            padding: 0.3rem;
            min-width: 8em;
            background: #ffffff;
            text-align: center;
            cursor: text;
            margin-bottom: -1px;
            z-index:2;
            border-bottom: 1px solid #cccccc;
        }

        .jiny.tabbar .tab-header.dragtab {
            cursor: move;
        }


        .jiny.tabbar .tab-header:hover {
            background: #def2fb;
        }

        .jiny.tabbar label {
            padding: 10px;
            /*background: #e2e2e2;*/
            font-weight: bold;
            cursor: pointer;
        }

        .jiny.tabbar label:hover {
            color: #2791ce;
        }

        .jiny.tabbar .tab-content {
            width: 100%;
            padding: 20px;
            background: #fff;
            order: 1;
            display: none;
            border-top: 1px solid #cccccc;
            z-index:1;

        }
        .jiny.tabbar .tab-content h2 {
            font-size: 3em;
        }

        .jiny.tabbar input[name="__tabbar"]:checked + .tab-header + .tab-content {
            display: block;
        }

        .jiny.tabbar input[name="__tabbar"]:checked + .tab-header {
            /* background: #e2e2e2; */
            border-bottom: 2px solid #0275b8;
        }

        .jiny.tabbar input[name="__tabbar"]:checked + .tab-header label {
            color: #0275b8;
        }

    </style>

    <form>
        @csrf
        {!! xFormBuilder($actions, "nav-bordered") !!}
    </form>

    <script>
        function findTagParent(el, tag) {
            tag = tag.toUpperCase();
            while(el.tagName != tag) {
                el = el.parentElement;
                if(el.tagName == "BODY") return null;
            }
            return el;
        }

        //const dragTab = document.querySelectorAll('.dragtab');
        const dragtab = document.querySelectorAll('.dragtab');
        let dragTabStart;
        dragtab.forEach(el => {
            // 드래그 이동기능 활성화
            //el.setAttribute('draggable', true);

            el.addEventListener('dragstart', function(e) {
                console.log("ragstart");
                el.classList.add('dragging');
                dragTabStart = findTagParent(e.target, 'nav');
            });

            el.addEventListener('dragenter', () => {
                //console.log("dragenter");
            });

            el.addEventListener('dragover', e => {
                //event.preventDefault();를 해줘야 drop이 가능하다
                e.preventDefault();

            })

            el.addEventListener('dragleave', () => {
                //console.log("dragleave");
            });

            el.addEventListener('drop', function(e) {
                e.preventDefault();
                console.log("drop");

                let target = findTagParent(e.target, 'nav');
                let pnode = target.parentElement;

                // option 이동
                let srcOpt = dragTabStart.previousElementSibling;// option
                let dstOpt = target.previousElementSibling;// option
                pnode.insertBefore(srcOpt , target);
                pnode.insertBefore(dstOpt, dragTabStart);

                // Header 이동
                srcNext = dragTabStart.nextElementSibling;
                targetNext = target.nextElementSibling;
                pnode.insertBefore(dragTabStart, targetNext);
                pnode.insertBefore(target, srcNext);

                // content 이동
                srcOpt = dragTabStart.nextElementSibling.nextElementSibling;// article
                dstOpt = target.nextElementSibling.nextElementSibling;// article
                pnode.insertBefore(target.nextElementSibling, srcOpt);
                pnode.insertBefore(dragTabStart.nextElementSibling, dstOpt);

                //console.log(target);
                ajaxTabPos()

            });

            el.addEventListener('dragend', () => {
                console.log("dragend");
                el.classList.remove('dragging');
                dragTabStart = null;
            });
        });

        function ajaxTabPos() {

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/table/forms/tabpos");

            let data = new FormData();
            let token = document.querySelector('input[name=_token]').value;
            data.append('_token', token);

            let tabs = document.querySelectorAll('.dragtab');
            for(let i=0; i < tabs.length; i++) {
                data.append("pos[" + tabs[i].dataset['index'] + "]", i+1);
            }

            xhr.onload = function() {
                var data = JSON.parse(this.responseText);
                console.log(data);
            }

            xhr.send(data);

        }
    </script>








    @if ($popupTabbar)
    <x-dialog-modal wire:model="popupTabbar" maxWidth="xl">

        <x-slot name="content">
            <p class="p-8">{{$popupTabbarMessage}}</p>


            <x-form-hor>
                <x-form-label>탭이름</x-form-label>
                <x-form-item>
                    {!! xInputText()
                        ->setWire('model.defer',"tabname")
                        ->setWidth("standard")
                    !!}
                </x-form-item>
            </x-form-hor>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between">
            @if (isset($tabid))
                <div>
                    <x-button danger wire:click="popupTabbarDelete">삭제</x-button>
                </div>
                <div>
                    <x-button secondary wire:click="popupTabbarClose">취소</x-button>
                    <x-button success wire:click="popupTabbarSave">수정</x-button>
                </div>
            @else
                <div></div>
                <div>
                    <x-button secondary wire:click="popupTabbarClose">취소</x-button>
                    <x-button primary wire:click="popupTabbarSave">저장</x-button>
                </div>
            @endif
            </div>
        </x-slot>
    </x-dialog-modal>
    @endif

</div>
