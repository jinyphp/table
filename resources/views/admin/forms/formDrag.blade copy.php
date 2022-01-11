<div>
    {{-- DragForm Script--}}
    <style>
        .dragForms {

        }
        .dragForms .dragging {
            background: #cccccc;
            opacity: 0.5;
        }
    </style>

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

        .jiny.tabbar input[name="__tabbar"] + .tab-header + .tab-content.active {
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

        const jinyTabBar = document.querySelector('.jiny.tabbar');
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


            el.addEventListener('dragenter', function(e) {
                e.preventDefault();
                console.log("dragenter");
                /*
                if(dragFormStart) {
                    let target = findTagParent(e.target, 'nav');
                    console.log(target);

                    let index = target.dataset['index'];
                    let options = jinyTabBar.querySelectorAll('input[name="__tabbar"]');
                    options.forEach(opt=>{
                        console.log(opt);
                        if(opt.value == index) {
                            opt.setAttribute("checked","checked");
                            opt.nextElementSibling.nextElementSibling.style = "display:block;";
                            console.log(opt.nextElementSibling.nextElementSibling);
                        } else {
                            opt.removeAttribute("checked");
                            opt.nextElementSibling.nextElementSibling.style = "display:none;";
                            console.log(opt.nextElementSibling.nextElementSibling);
                        }
                    });
                }
                */

            });

            el.addEventListener('dragover', e => {
                //event.preventDefault();를 해줘야 drop이 가능하다
                e.preventDefault();

            })

            el.addEventListener('dragleave', function(e) {
                e.preventDefault();
                console.log("dragleave");
                /*
                if(dragFormStart) {
                    let target = findTagParent(e.target, 'nav');
                    console.log(target);

                    let index = target.dataset['index'];
                    let options = jinyTabBar.querySelectorAll('input[name="__tabbar"]');
                    options.forEach(opt=>{
                        //console.log(opt.value);
                        if(opt.value == index) {
                            opt.setAttribute("checked","checked");
                            opt.nextElementSibling.nextElementSibling.style = "display:block;";
                        } else {
                            opt.removeAttribute("checked");
                            opt.nextElementSibling.nextElementSibling.style = "display:none;";
                        }
                    });
                }
                */
            });

            el.addEventListener('drop', function(e) {
                e.preventDefault();
                console.log("drop");

                if(dragTabStart) {
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
                    ajaxTabPos();
                }



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



    {{-- DragForm Script--}}
    <style>
        .dragForms {

        }
        .dragForms.dragging {
            background: #cccccc;
            opacity: 0.5;
        }

        .tab-content  ul.form-dragging li {
            background: #f8f8f8;
            border-bottom: 1px solid #eeeeee
        }
    </style>
    <script>

        const dragForms = document.querySelectorAll('.dragForms');
        let dragFormStart;
        let activeTab;
        //console.log(dragForms);

        // 폼그룹 드래그 이동
        //console.log("ul 이벤트 등록");
        /*
        let formGroup = document.querySelectorAll('ul.from-group');
        formGroup.forEach(fg=>{
            fg.addEventListener('dragover', function(e){
                e.preventDefault();
            });
            fg.addEventListener('drop',function(e){
                e.preventDefault();
                console.log("그룹이동");

                let target = findTagParent(e.target, 'ul');
                let tabIndex = target.dataset['tab-index'];
                target.appendChild(dragFormStart);
                //target.insertBefore(dragFormStart);
            });
            fg.addEventListener('dragend', function(e) {
                e.preventDefault();
                console.log("ul-dragend");
                //closeTabBody(); // 열려진 텝Body 모두 닫기
            });
        });
        */


        // formrow 드래그 이동
        dragForms.forEach(el => {
            //continue;

            el.addEventListener('dragstart', function(e) {
                console.log("form-dragstart");
                el.classList.add('dragging');

                dragFormStart = findTagParent(e.target, 'li');
                if(dragFormStart) {
                    // 부모 ul테그에 드래깅 class 추가
                    dragFormStart.parentElement.classList.add('form-dragging');


                    // 숨겨진 텝바 body 출력
                    activeTab = jinyTabBar.querySelector('input[name="__tabbar"]:checked');
                    if(activeTab) { // 텝기능이 확인될때
                        activeTab = activeTab.dataset['index'];
                        console.log("선택탭 = " + activeTab);
                        openTabBody(activeTab);
                    }


                }
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
                console.log("form-drop");

                let target = findTagParent(e.target, 'li');
                if(target) { // 대상 타켓이 선택된 경우
                    console.log(target);
                    dragFormStart.parentElement.classList.remove('form-dragging');

                    if(dragFormStart != target) { // 자기 자신은 제외

                        let pnode = target.parentElement;
                        let tabid = pnode.parentElement.dataset['tabIndex'];
                        console.log("tabid=" + tabid);
                        console.log("target index = " + target.dataset['index']);

                        if(target.dataset['index'] === "999") {
                            //getEventListener(target);
                            //pnode.appendChild(dragFormStart);
                            pnode.insertBefore(dragFormStart,null);

                            dragFormStart.dataset.tabIndex = tabid; // data 속성변경
                            console.log(dragFormStart);
                        } else {
                            // 서로 맞교환 이동
                            srcNext = dragFormStart.nextElementSibling;
                            targetNext = target.nextElementSibling;
                            pnode.insertBefore(dragFormStart, targetNext);
                            pnode.insertBefore(target, srcNext);
                        }




                        // DB 저장
                        ajaxFormPos(tabid);

                    }
                } else {
                    console.log("form-drop 대상 타켓이 없습니다.");

                }

            });

            el.addEventListener('dragend', function(e) {
                e.preventDefault();
                console.log("formrow-dragend");
                el.classList.remove('dragging');

                dragFormStart.parentElement.classList.remove('form-dragging');
                dragFormStart = null;

                // 열려진 텝 모두 닫기
                closeTabBody();


            });
        });


        function openTabBody(activeTab)
        {
            let contents = jinyTabBar.querySelectorAll('.tab-content');
            contents.forEach(tab=>{
                // 마지막 추가쳅은 index가 없음
                if(tab.dataset['tabIndex']) {
                    //console.log(tab.dataset['tabIndex'] + " == " + activeTab);

                    if(tab.dataset['tabIndex'] != activeTab) {
                        tab.style="order:3";
                        tab.classList.add('active');
                        //console.log("블럭설정");
                        //console.log(tab);
                    }
                }

                //console.log(tab.querySelector('.tab-move-zone'));
                let movezone = tab.querySelector('.tab-move-zone');
                if(movezone) {
                    movezone.classList.remove('hidden');
                }
            });
        }


        function closeTabBody()
        {
            //console.log("closeTabBody");
            let contents = jinyTabBar.querySelectorAll('.tab-content');
            contents.forEach(tab=>{
                if(tab.dataset['index'] != activeTab) {
                    tab.removeAttribute('style');
                    tab.classList.remove('active');
                }
                let movezone = tab.querySelector('.tab-move-zone');
                if(movezone) {
                    movezone.classList.add('hidden');
                }
            });
        }


        function ajaxFormPos(tabid) {

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/table/forms/pos");

            let data = new FormData();
            let token = document.querySelector('input[name=_token]').value;
            data.append('_token', token);

            data.append('tabid', tabid)

            let tabs = document.querySelectorAll('.dragForms');
            for(let i=0; i < tabs.length; i++) {
                //data.append("pos[" + tabs[i].dataset['index'] + "]", i+1);
                data.append("pos[" + tabs[i].dataset['index'] + "]", tabs[i].dataset['tabIndex']);
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
