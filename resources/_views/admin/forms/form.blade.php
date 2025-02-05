<div>
    탭기능, 드래그 x
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

</div>
