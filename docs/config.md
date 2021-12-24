# JsonController
입력된 폼에 따라서 Config PHP 파일을 생성합니다.

## 폼입력값을 json 파일로 저장합니다.


입력폼의 내용을 json 파일로 저장을 합니다.
```php
@livewire('WireConfig', ['actions'=>$actions])
```

## 파일명 지정
테이블명을 지정하면, 동일한 이름으로 `/config` 폴더안에 파일이 생성됩니다.

```php
$this->actions['filename'] = "파일명";
```
