<x-theme theme="admin/sidebar2">
    <x-theme-layout>


    <h1>Fure CSS Modal</h1>

    <style>
    .button {
        background: #428bca;
        padding: 1em 2em;
        color: #fff;
        border: 0;
        border-radius: 5px;
        cursor: pointer;
    }

    .button:hover {
        background: #3876ac;
    }
    </style>

    <script>
        let token = document.querySelector('[name="csrf-token"]');
        console.log(token.content);
    </script>


    <button action="/admin/site/menu/code" class="button modal-btn">Click Here</button>

    </x-theme-layout>
</x-theme>
