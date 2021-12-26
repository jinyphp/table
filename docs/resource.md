# 리소스

## 외형
지니Table은 UI의 외형을 관리하는 3개의 리소스가 존재합니다.
* title.blade.php : $actions['view_title']
* main.blade.php
* edit.blade.php
* config.blade.php

이 UI값은 `actions` 배열 값을 이용하여 사용자 정리를 할 수 있습니다.

## 내부 리소스
지니table은 livewire를 이용하여 동작을 제어합니다.
livewire 동작을 처리하기 위한 리소스를 제공합니다.

* table.blade.php
* form.blade.php

만일 팝업 형태로 LiveWire 내용을 출력하고자 할때에는 ./popup 폴더안에 있는
리소스를 사용합니다.

* form.blade.php
* view.blade.php
* manual.blade.php
* rule.blade.php
