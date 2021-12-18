<x-theme theme="admin.sidebar2">
    <x-theme-layout>
        <!-- start page title -->
        <x-row >
            <x-col class="col-8">
                <div class="page-title-box">
                    <ol class="m-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Sales</a></li>
                        <li class="breadcrumb-item active">Position</li>
                    </ol>

                    <div class="mb-3">
                        <h1 class="align-middle h3 d-inline">직급</h1>
                        <p>

                        </p>
                    </div>
                </div>
            </x-col>
        </x-row>
        <!-- end page title -->

        <div class="relative">
            <div class="absolute right-0 bottom-4">
                <div class="btn-group">
                    <a href="#" class="btn btn-secondary">메뉴얼</a>
                    <a href="{{route($actions['routename'].".create")}}" class="btn btn-primary">신규추가</a>
                </div>
            </div>
        </div>


        @livewire('WireTable', ['actions'=>$actions])


    </x-theme-layout>
</x-theme>
