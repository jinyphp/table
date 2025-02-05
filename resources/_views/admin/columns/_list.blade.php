



<style>
    .th-resize {
        background-color: #ccc;
        width: 5px;
        height: 100%;
        cursor: w-resize;
    }
    </style>



<style>
    .dragtable:hover {
        background-color: #f8f8f8;
    }

    .dragtable.dragging {
        opacity: 0.5;
        background-color: #333333;
    }

    .drag-grip {
        cursor:move;
    }

    .dragitem {
        background-color: #333333;
    }
</style>

<form>
    @csrf
</form>

<x-datatable>
    @php
        $uri = "/".$this->actions['route']['uri'];
        $cols = DB::table('table_columns')
            ->where('uri',$uri)
            ->where('enable',1)
            ->orderby('pos',"asc")->get();
    @endphp
    <thead>
        {{--
        <th> {!! xWireLink('uri', "orderBy('uri')") !!}</th>
        --}}
        @php
            $thead = []; //new \Jiny\Html\CTag('thead');

            $th = new \Jiny\Html\CTag('th',true);
                $chkall = new \Jiny\Html\CTag('input', false);
                $chkall->setAttribute('type', "checkbox");
                $chkall->setAttribute('wire:model', "selectedall");
                $chkall->addClass("form-check-input");
            $th->addItem($chkall);
            $th->addStyle("width:20px;");
            $thead []= $th;

            foreach($cols as $i => $col) {
                $th = new \Jiny\Html\CTag('th',true);
                $th->addClass("dragitem");

                // 드래그 그립아이콘
                $dragGrip = xSpan();
                $dragGrip->addHtml('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="inline-block"
                fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16">
                <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>');
                $dragGrip->addClass("drag-grip");
                $th->addItem($dragGrip);




                $flex = (new \Jiny\Html\CTag('span',true))
                ->addStyle("display:inline-block; width:5px; height:100%; background-color: #000000");
                $th->addItem($flex);

                // 드래그
                /*
                $th->addClass('dragtable');
                $th->setAttribute('draggable',"true");
                $th->setAttribute('data-id',$col->id);
                $th->setAttribute('data-pos',$col->pos);
                $th->setAttribute('data-index',$i);
                */


                if($col->display) {
                    //$th->addHtml("-");
                    $th->addStyle("width:1px; padding:5; background-color:#eeeeee;");
                    $move = xSpan();
                    $move->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                    </svg>');
                    $move->setAttribute("wire:click","columnHidden('".$col->id."')");
                    $th->addHtml($move);

                } else {
                    // 축소버튼
                    $move = xSpan();
                    $move->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-arrows-angle-contract" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M.172 15.828a.5.5 0 0 0 .707 0l4.096-4.096V14.5a.5.5 0 1 0 1 0v-3.975a.5.5 0 0 0-.5-.5H1.5a.5.5 0 0 0 0 1h2.768L.172 15.121a.5.5 0 0 0 0 .707zM15.828.172a.5.5 0 0 0-.707 0l-4.096 4.096V1.5a.5.5 0 1 0-1 0v3.975a.5.5 0 0 0 .5.5H14.5a.5.5 0 0 0 0-1h-2.768L15.828.879a.5.5 0 0 0 0-.707z"/>
                    </svg>');
                    $move->setAttribute("wire:click","columnHidden('".$col->id."')");
                    $move->addStyle("margin-right:5px;valign:center;");
                    $th->addHtml($move);


                    if($col->sort && $col->sort != "") {
                        $title = xWireLink($col->title, "orderBy('".$col->field."')");
                    } else {
                        $title = $col->title;
                    }

                    $th->addItem(xSpan($title));
                    //$thead->addItem($th);

                }


                $th->addItem(
                    xDiv("")->addClass("th-resize")
                );


                $thead []= $th;
            }
        @endphp

        @foreach ($thead as $th)
            {!! $th !!}
        @endforeach

    </thead>

    @if(!empty($rows))
    <tbody>
        @foreach ($rows as $item)

        {{-- row-selected --}}
        @if(in_array($item->id, $selected))
        <tr class="row-selected">
        @else
        <tr>
        @endif

            <td width='20'>
                <input type='checkbox' name='ids' value="{{$item->id}}"
                class="form-check-input"
                wire:model="selected">
            </td>




            {{--
            <td>
                {!! $popupEdit($item, $item->uri) !!}
            </td>
            --}}


            @php
                $tbody = []; //new \Jiny\Html\CTag('thead');
                foreach($cols as $col) {
                    $td = new \Jiny\Html\CTag('td', true);

                    if($col->display) {
                        //$td->addHtml("-");
                        $td->addStyle("width:1px; padding:5; background-color:#eeeeee;");
                    } else {
                        $field = $col->field;
                        $title = $item->$field;
                        if($col->edit) {
                            $title = $popupEdit($item, $title);
                        }

                        $td->addItem($title);
                        if($col->width) {
                            $td->addStyle("width:".$col->width.";");
                        }
                    }

                    $tbody []= $td;
                }
            @endphp

            @foreach ($tbody as $td)
                {!! $td !!}
            @endforeach


        </tr>
        @endforeach

    </tbody>
    @endif
</x-datatable>

@if(empty($rows))
<div>
    목록이 없습니다.
</div>
@endif


<script>
    function findTagParent(el, tag) {
        tag = tag.toUpperCase();
        while(el.tagName != tag) {
            el = el.parentElement;
        }
        return el;
    }

    function dragable(status)
    {
        // "thead > tr > th"
        if(status) {
            document.querySelectorAll(".drag-grip").forEach(el=>{
                el.setAttribute('draggable',true);
                console.log(el);
            });
        } else {
            document.querySelectorAll(".drag-grip").forEach(el=>{
                el.setAttribute('draggable',false);
                console.log(el);
            });
        }
    }

    // 드래그 클립 선택시 드래그 모드로 전환
    const dragGrip = document.querySelectorAll(".drag-grip");
    dragGrip.forEach(el=>{
        el.addEventListener('mousedown',function(e){
            e.preventDefault();
            //dragable(true);
        });

        el.addEventListener('mouseup',function(e){
            e.preventDefault();
            //dragable(false);
        });
    });

    const draggables = document.querySelectorAll('.dragtable');
    //console.log(draggables);

    const thead = document.querySelector('thead');
    var dragStartIndex, start_id, start_pos;
    var dragTargetIndex, target_id, target_pos;
    draggables.forEach(el => {
        // 드래그 이벤트 등록

        el.addEventListener('dragstart', (e)=>{
            //console.log('dragstart');
            el.classList.add('dragging');
            dragStartIndex = e.target.dataset['index'];
            start_id = e.target.dataset['id'];
            start_pos = e.target.dataset['pos'];
            //console.log(dragStartIndex);
        });

        // enter -> over -> leave
        el.addEventListener('dragenter', (e)=>{
            e.preventDefault();
            //console.log('dragenter');
        });

        el.addEventListener('dragover', (e)=>{
            e.preventDefault();
            //console.log('dragover');
            //el.classList.remove('dragging');
        });

        el.addEventListener('dragleave', (e)=>{
            e.preventDefault();
            //console.log('dragleave');
        });

        el.addEventListener('drop', (e)=>{
            e.preventDefault();
            el.classList.remove('dragging');
            //console.log('drop');
            dragTargetIndex = e.target.dataset['index'];
            target_id = e.target.dataset['id'];
            target_pos = e.target.dataset['pos'];
            //console.log(dragTargetIndex);
            swapItem(dragTargetIndex, dragStartIndex);
        });

        el.addEventListener('dragend', (e)=>{
            //console.log('dragend');
            el.classList.remove('dragging');
        });
    });

    function swapItem(dragTargetIndex, dragStartIndex) {
        //console.log("move = " +   dragTargetIndex + ","+ dragStartIndex);
        if(!dragTargetIndex) return;
        if(!dragStartIndex) return;

        // DB columns 정렬 수정 요청
        let token = document.querySelector('input[name=_token]').value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/admin/table/column/drag");

        let data = new FormData();
        data.append('start', dragStartIndex);
        data.append('start_id', start_id);
        data.append('start_pos', start_pos);

        data.append('target', dragTargetIndex);
        data.append('target_id', target_id);
        data.append('target_pos',target_pos);
        data.append('_token', token);

        xhr.onload = function() {
            var data = JSON.parse(this.responseText);
            console.log(data);
            console.log("테이블 갱신요청");
            Livewire.emit('refeshTable'); // 라이브와이어 테이블 갱신
        }

        xhr.send(data);
    }
</script>

<!-- 사이즈조절-->


<script>
    var m_pos;
    function resize(e){
        var parent = resize_el.parentNode;
        var dx = m_pos - e.x;
        m_pos = e.x;
        parent.style.width = (parseInt(getComputedStyle(parent, '').width) + dx) + "px";
    }

    var resize_el = document.getElementById("resize");

    resize_el.addEventListener("mousedown", function(e){
        m_pos = e.x;
        document.addEventListener("mousemove", resize, false);
    }, false);

    document.addEventListener("mouseup", function(){
        document.removeEventListener("mousemove", resize, false);
    }, false);
</script>
