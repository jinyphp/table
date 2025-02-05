<style>
/*
    .dragtable:hover {
        background-color: #f8f8f8;
    }

    .dragtable.dragging {
        opacity: 0.5;
        background-color: #333333;
    }



    .dragitem {
        background-color: #333333;
    }
    */

    .drag-target {
        opacity: 0.5;
    }

    .drag-grip {
        opacity: 0;
        cursor:move;
    }

    .drag-grip:hover {
        opacity: 100;
    }
</style>


<style>
    /* Code By Webdevtrick ( https://webdevtrick.com ) */
    table * {
        box-sizing: border-box;
    }

    table {
        min-width: 100%;
        width: auto;
        -webkit-box-flex: 1;
        flex: 1;
        display: grid;
        border-collapse: collapse;

        /*
        grid-template-columns:
            minmax(100px, auto)
            minmax(100px, auto)
            minmax(100px, auto)
            minmax(100px, auto)
            minmax(100px, auto)
            minmax(100px, auto)
            minmax(100px, auto)
            minmax(100px, auto);
        */
    }

    thead,
    tbody,
    tr {
        display: contents;
    }

    th,
    td {
        padding: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background: #5cb85c;
        text-align: left;
        font-weight: normal;
        /* font-size: 1rem; */
        color: white;
        position: relative;
    }

    th:last-child {
        border: 0;
    }



    .resize-handle {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        background: black;
        opacity: 0;
        width: 5px;
        cursor: col-resize;
    }

    /*
    th:hover .resize-handle {
        opacity: 0.3;
    }
    */

    .resize-handle:hover,
    .header--being-resized .resize-handle {
        opacity: 0.5;
    }



    td {
        padding-top: 10px;
        padding-bottom: 10px;
        color: #808080;
    }

    tr:nth-child(even) td {
        background: #f8f6ff;
    }
</style>

<form>
    @csrf
    @php
        function checkAll()
        {
            $th = new \Jiny\Html\CTag('th',true);
                $chkall = new \Jiny\Html\CTag('input', false);
                $chkall->setAttribute('type', "checkbox");
                $chkall->setAttribute('wire:model', "selectedall");
                $chkall->addClass("form-check-input");
            $th->addItem($chkall);
            //$th->addStyle("width:20px;");
            $th->setAttribute('data-type', "numeric");//data-type="numeric"
            $th->setAttribute('data-id',0);
            return $th;
        }

        function headHidden($col)
        {
            // 축소버튼
            $move = xSpan();
            $move->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
            </svg>');

            $move->setAttribute("wire:click","columnHidden('".$col->id."')");
            //$move->addStyle("margin-right:5px;valign:center;");
            return xDiv($move);
        }

        function headDisplay($col)
        {
            $move = xSpan();
            $move->addHtml('<svg class="inline-block" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>');
            $move->setAttribute("wire:click","columnHidden('".$col->id."')");
            return xDiv($move);
        }
    @endphp

    @php
        $uri = "/".$this->actions['route']['uri'];
        $cols = DB::table('table_columns')
            ->where('uri',$uri)
            ->where('enable',1)
            ->orderby('pos',"asc")->get();


        $grid_columns = "37px ";
        foreach($cols as $col) {
            if($col->display) {
                $grid_columns .= "37px ";
            } else {
                if($col->width) {
                    $grid_columns .= "minmax(40px, ".$col->width.") ";
                } else {
                    $grid_columns .= "minmax(30px, 1fr) ";
                }

            }
        }
    @endphp

    <table style="grid-template-columns:{{$grid_columns}};">
        <thead>

            @php
            $tr = new \Jiny\Html\CTag('tr',true);

            $tr->addItem(checkAll());


            ######
            foreach($cols as $i => $col) {
                $th = new \Jiny\Html\CTag('th',true);

                // 드래그
                $th->addClass('dragtable');
                $th->setAttribute('draggable',"true");
                $th->setAttribute('data-id',$col->id);
                $th->setAttribute('data-pos',$col->pos);
                $th->setAttribute('data-index',$i+1); // allcheck 때문에 +1

                if($col->display) {
                    $th->setAttribute("data-type", "hidden");

                    // 확대버튼
                    $th->addItem(headDisplay($col));

                } else {
                    $th->setAttribute("data-type", "text-long");

                    $flex = xDiv()->addClass("flex justify-between");
                        $left = xDiv();

                        // 드래그 그립아이콘
                        $dragGrip = xSpan();
                        $dragGrip->addHtml('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="inline-block"
                        fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16">
                        <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>');
                        $dragGrip->addClass("drag-grip");
                        $left->addItem($dragGrip);

                        if($col->sort && $col->sort != "") {
                            $title = xWireLink($col->title, "orderBy('".$col->field."')");
                        } else {
                            $title = $col->title;
                        }
                        $left->addItem($title);

                        $flex->addItem($left);

                        // 축소버튼
                        $flex->addItem(headHidden($col));

                    $th->addItem($flex);

                    // resize-handle
                    $th->addItem( xSpan()->addClass("resize-handle") );
                }

                $tr->addItem($th);
            }
            @endphp
            {!! $tr !!}
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

                <td>
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
                            //$td->addStyle("width:1px; padding:5; background-color:#eeeeee;");
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
    </table>
</form>
<hr>


@if(empty($rows))
<div>
    목록이 없습니다.
</div>
@endif


{{-- 드래그 drop columns --}}
<script>

    function findTagParent(el, tag) {
        tag = tag.toUpperCase();
        while(el.tagName != tag) {
            el = el.parentElement;
            if(el.tagName == "BODY") return null;
        }
        return el;
    }

    var draggables = document.querySelectorAll('.dragtable');
    const thead = document.querySelector('thead');
    var dragStartIndex, start_id, start_pos;
    var dragTargetIndex, target_id, target_pos;
    var startElement;

    const onDragStart = function (){
        console.log('dragstart');

        // 드래그 클릭 시작점 저장
        let target = findTagParent(this, "th");

        startElement = target;
        start_id = target.dataset['id'];
        start_pos = target.dataset['pos'];
        dragStartIndex =findHeadIndex(start_id);
    };

    function findHeadIndex(id) {
        let th = thead.querySelectorAll('tr > th');
        for (let i=0; i<th.length; i++) {
            if(th[i].dataset['id'] == id) {
                return i;
            }
        }
    }

    const onDrop = function()
    {
        //e.preventDefault();
        console.log('drop');

        // drop 위치저장
        let target = findTagParent(this, "th");
        target_id = target.dataset['id'];
        target_pos = target.dataset['pos'];

        dragTargetIndex =findHeadIndex(target_id);

        // 컬럼 리사이즈 교환
        exchangeResize(start_id, target_id);

        // 해더위치 교환
        exchangeHeader(target);

        // Tbody 셀 교환
        exchangeTbodyCell(dragStartIndex, dragTargetIndex);

        // swap ajax 호출
        swapItemAjax(dragTargetIndex, dragStartIndex);
    }

    function exchangeResize(start_id, target_id) {
        // 컬럼 리사이즈 교환
        for(var i=0; i< columns.length; i++) {
            if(columns[i].id == start_id) {
                var s = i;
            }
            if(columns[i].id == target_id) {
                var t = i;
            }
        }

        var temp = columns[s];
        columns[s] = columns[t];
        columns[t] = temp;
    }

    function exchangeHeader(target) {
        //방법2 : 각각의 위치를 기억하여 옴기기
        let srcNext = startElement.nextSibling;
        let targetNext = target.nextSibling;

        let pnode = target.parentElement;
        pnode.insertBefore(startElement, targetNext);
        pnode.insertBefore(target, srcNext);
    }

    function exchangeTbodyCell(dragStartIndex, dragTargetIndex) {
        let tr = document.querySelectorAll('tbody > tr');

        console.log(dragStartIndex + "=>" + dragTargetIndex);
        for(let i=0; i<tr.length; i++) {
            let tds = tr[i].querySelectorAll('td');

            let srcNext = tds[dragStartIndex].nextSibling;
            let targetNext = tds[dragTargetIndex].nextSibling;

            tr[i].insertBefore(tds[dragStartIndex], targetNext);
            tr[i].insertBefore(tds[dragTargetIndex], srcNext);
        }
    }

    function enableTableCoulumDrag(el)
    {
        el.setAttribute('draggable',true);
        el.addEventListener('dragstart', onDragStart);
        el.addEventListener('drop', onDrop);
    }

    function disableTableCoulumDrag(el)
    {
        el.setAttribute('draggable',false);
        el.removeEventListener('dragstart', onDragStart);
        el.removeEventListener('drop', onDrop);
    }

    const onDragenter = function() {
        //console.log('dragenter');
    }

    const onDragover = function(e) {
        e.preventDefault();
        console.log('dragover');
        //console.log('dragover, ' + "start = " + start_id + ", target = "+ target_id);
    }

    const onDragend = function() {
        console.log('dragend');
    }

    const onDragleave = function() {
        console.log('dragleave');
    }

    // 드래그 이벤트 등록
    function setTableColumnDrag() {
        draggables.forEach(el => {
            el.setAttribute('draggable', true);

            el.addEventListener('dragstart', onDragStart);
            // enter -> over -> leave
            el.addEventListener('dragenter', onDragenter);
            el.addEventListener('dragover', onDragover);
            el.addEventListener('dragleave',onDragleave);
            el.addEventListener('drop', onDrop);
            el.addEventListener('dragend', onDragend);
        });
    }

    function unsetTableColumnDrag() {
        draggables.forEach(el => {
            el.setAttribute('draggable', false);

            el.removeEventListener('dragstart', onDragStart);
            // enter -> over -> leave
            el.removeEventListener('dragenter', onDragenter);
            el.removeEventListener('dragover', onDragover);
            el.removeEventListener('dragleave',onDragleave);
            el.removeEventListener('drop', onDrop);
            el.removeEventListener('dragend', onDragend);
        });
    }

    function swapItemAjax(dragTargetIndex, dragStartIndex) {

        if(!dragTargetIndex) return;
        if(!dragStartIndex) return;

        // DB columns 정렬 수정 요청
        let token = document.querySelector('input[name=_token]').value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/api/table/column/pos");

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
            //console.log("테이블 갱신요청");
            //Livewire.emit('refeshTable'); // 라이브와이어 테이블 갱신
        }

        xhr.send(data);
    }

    // 테이블 컬럼 드래그를 허용합니다.
    setTableColumnDrag();

</script>



{{-- 드래그 사이즈 조절 --}}
<script>

        const min = 20;

        // The max (fr) values for grid-template-columns
        const columnTypeToRatioMap = {
            numeric: "1fr",
            'text-short': "1fr",
            'text-long': "1fr",
            'check': "1fr",
            'hidden':"37px"
        };

        const table = document.querySelector('table');

        const columns = [];
        let headerBeingResized;

        // The next three functions are mouse event callbacks

        // Where the magic happens. I.e. when they're actually resizing
        const onMouseMove = e => requestAnimationFrame(() => {
            console.log('onMouseMove');

            // Calculate the desired width
            horizontalScrollOffset = document.documentElement.scrollLeft; // x 축 방향으로 스크롤한 거리
            const width = horizontalScrollOffset + e.clientX - headerBeingResized.offsetLeft;

            // Update the column object with the new size value

            const column = columns.find(({ header }) => header === headerBeingResized);
            column.size = Math.max(min, width) + 'px'; // Enforce our minimum

            // For the other headers which don't have a set width, fix it to their computed width
            columns.forEach(column => {
                if (column.size.startsWith('minmax')) {// isn't fixed yet (it would be a pixel value otherwise)
                    column.size = parseInt(column.header.clientWidth, 10) + 'px';
                }
            });


            /*
                Update the column sizes
                Reminder: grid-template-columns sets the width for all columns in one value
            */
            table.style.gridTemplateColumns = columns.
            map(({ header, size }) => size).
            join(' ');
        });


        // Clean up event listeners, classes, etc.
        const onMouseUp = () => {
            console.log('onMouseUp');

            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mouseup', onMouseUp);

            headerBeingResized.classList.remove('header--being-resized');

            setTableColumnDrag(); // 컬럼 드래그를 재활성화 합니다.
            setAjaxTableResize(); // 사이즈 조정값을 DB에 저장합니다.

            headerBeingResized = null;
        };

        function setAjaxTableResize()
        {
            // DB columns 정렬 수정 요청
            let token = document.querySelector('input[name=_token]').value;
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/api/table/column/resize");

            let data = new FormData();

            for(let i=0; i < columns.length; i++) {
                data.append("size[" + columns[i].id + "]", columns[i].size);
            }
            data.append('_token', token);

            xhr.onload = function() {
                var data = JSON.parse(this.responseText);
                console.log(data);
            }

            xhr.send(data);
        }

        // 테이블 해더 설정
        function setTableColumnResize() {

            table.querySelectorAll('th').forEach(header => {

                // 각 해더열에 대한 기본폭 설정값 적용
                const max = columnTypeToRatioMap[header.dataset.type];
                const th_id = header.dataset['id'];
                columns.push({
                    header,
                    size: `minmax(${min}px, ${max})`, // grid-template-columns: 초기값
                    id: th_id
                });

                // 사이즈 조절바 이벤트 설정
                let handle = header.querySelector('.resize-handle');
                if (handle) {
                    handle.addEventListener('mousedown', function(e){
                        console.log('initResize');

                        headerBeingResized = e.target.parentNode;
                        headerBeingResized.classList.add('header--being-resized');

                        unsetTableColumnDrag();

                        // 이벤트 등록
                        window.addEventListener('mouseup', onMouseUp);
                        window.addEventListener('mousemove', onMouseMove);
                    });
                }

            });

        }

        setTableColumnResize();

</script>


