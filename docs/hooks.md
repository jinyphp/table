# hooks
지니Table은 controller와 실제 동작을 처리하는 livewire 모듈로 나누어져 있습니다.
실제 livewire 동작을 수행하지 전에 필요한 커스텀 작업을 hook 기능을 통하여 처리할 수 있습니다.



## 목록 
목록은 테이블을 조회하여 데이터를 출력합니다. index Hook는 2개의 모드가 존재합니다.
데이터를 패치하기 전에 설정해야 되는 동작과 데이터를 패치 이후에 동작해야 하는 hook입니다.

### 패치전 동작하는 hook
목록을 검색하기 전에 livewire에 값을 설정해 주어야 하는 경우가 있습니다. 이때에는 `hookIndexing()` 메소드를
선언합니다. 이 메소드가 먼저 실행된 후에, 실제 dbfetch 작업이 실행됩니다.

```php
public function hookIndexing()
{

}
```

### 데이터 fetch후 호출 됩니다.
```php
public function hookIndexed($rows)
{
    //$this->wire->aaa = "hello";
    return $rows;
}
```

## 생성폼이 실행될때 호출됩니다.
```php
public function hookCreating()
{

}
```

## 신규 데이터 DB 삽입전에 호출됩니다.

```php
public function hookStoring($form)
{
    return $form;
}
```

```php
public function hookStored($form)
{
    return $form;
}
```

## 수정폼이 실행될때 호출됩니다.

```php
public function hookEditing($form)
{
    return $form;
}
```

```php
public function hookEdited($form)
{
    return $form;
}
```

## 수정된 데이터가 DB에 적용되기 전에 호출됩니다.

```php
public function hookUpdating($form)
{
    return $form;
}
```

```php
public function hookUpdated($form)
{
    return $form;
}
```


## 데이터가 삭제되기 전에 호출됩니다.
삭제 동작을 실행허기 전에 처리해야 되는 기능을 hook기능을 통하여 실행할 수 있습니다.
delete 동작전, 선택하고자 하는 데이터를 읽어 매개변수로 전달합니다.
또한 결과도 같이 반환을 해야 합니다.

### delete 동작이 실행하기 전에 호출됩니다.
```php
public function hookDeleting($row)
{
    return $row;
}
```

### delete 동작이 실행 완료된 후에 호출됩니다.
```php
public function hookDeleted($row)
{
    return $row;
}
```