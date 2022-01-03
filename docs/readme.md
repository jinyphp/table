# Table

## WireTable
DB에서 데이터를 읽어서 테이블을 출력합니다.

## JsonTable
Json 파일 또는 uri를 통하여 얻은 값을 테이블로 출력합니다.

## WireFiles
디렉터리를 읽어 파일을 목록화 하여 출력합니다.


## Json 데이터를 이용한 테이블 목록 구성하기
Json파일을 읽어서 테이블로 목록을 구성할 수 있습니다. 이를 위하여 `JsonTable` 컴포넌트를 사용합니다.

```php
@livewire('JsonTable', ['actions'=>$actions])
```

