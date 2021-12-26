# hooks

/**
     * Livewire 동작후 실행되는 메서드ed
     */
## 목록 데이터 fetch후 호출 됩니다.
```php
public function hookIndexed($rows)
{
    //$this->wire->aaa = "hello";
    return $rows;
}
```

## 생성폼이 실행될때 호출됩니다.
```php
public function hookCreated()
{

}
```

## 신규 데이터 DB 삽입전에 호출됩니다.

```php
public function hookStored($form)
{
    return $form;
}
```

## 수정폼이 실행될때 호출됩니다.

```php
public function hookEdited($form)
{
    return $form;
}
```

## 수정된 데이터가 DB에 적용되기 전에 호출됩니다.

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

```php
public function hookDeleted($row)
{
    return $row;
}
```
