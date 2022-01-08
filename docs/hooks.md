# hooks
지니Table은 controller와 실제 동작을 처리하는 livewire 모듈로 나누어져 있습니다.
실제 livewire 동작을 수행하지 전에 필요한 커스텀 작업을 hook 기능을 통하여 처리할 수 있습니다.



## 목록 
목록은 테이블을 조회하여 데이터를 출력합니다. index Hook는 2개의 모드가 존재합니다.
데이터를 패치하기 전에 설정해야 되는 동작과 데이터를 패치 이후에 동작해야 하는 hook입니다.

### 패치전 동작하는 hook
목록을 검색하기 전에 livewire에 값을 설정해 주어야 하는 경우가 있습니다. 
이때에는 `hookIndexing()` 메소드를 선언합니다. 이 메소드가 먼저 실행된 후에, 실제 dbfetch 작업이 실행됩니다.

정상동작
```php
public function hookIndexing($wire)
{
}
```

비정상동작
`hookIndexing`는 보통 nested된 데이터를 조회할 경우 많이 사용됩니다. 만일 사전 데이터 설정시 오류로 인하여
index 동작을 제한해야 하는 경우 반환값을 지정합니다. `hookIndexing` 반환값이 있는 경우, 다음 스텝의 동작을
진행하지 않습니다.
ex)
```php
public function hookIndexing($wire)
    {
        if($user = Auth::user()) {
            $email = $user->email;
            $row = DB::table('hr_employee')->where('email', $email)->first();

            $this->wire->actions['where'] = [
                'employee' => ['like'=>$row->id.":%"]
            ];

            return false;
        }

        return view("jinytable::error.message",[
            'message'=>"오류가 있습니다."
        ]);
    }
```


### 데이터 fetch후 호출 됩니다.
`hookIndexed`는 DB 테이블을 조회한 `rows` 값을 전달 받습니다. 전달받은 rows 값을 이용하여
후작업 후킹을 할 수 있습니다. 작업후에는 반드시 다시 `rows`값을 리턴해 주어야만 결과를 출력할 수 있습니다.

```php
public function hookIndexed($wire, $rows)
{
    //$this->wire->aaa = "hello";
    return $rows;
}
```

## 생성폼이 실행될때 호출됩니다.
```php
public function hookCreating($wire, $value)
{

}
```

## 신규 데이터 DB 삽입전에 호출됩니다.

```php
public function hookStoring($wire,$form)
{
    return $form;
}
```

`hookStored` 는 DB에 새로운 데이터를 삽입이 성공되었을 때 동작하는
후크메소드 입니다. 
데이터를 입력후 id값은 `form['id]`값으로 확인할 수 있습니다.
 
```php
    public function hookStored($wire, $form)
    {
        $id = $form['id'];
    }
```

## 수정폼이 실행될때 호출됩니다.

```php
public function hookEditing($wire, $form, $old)
{
    return $form;
}
```

```php
public function hookEdited($wire, $form, $old)
{
    return $form;
}
```

## 수정된 데이터가 DB에 적용되기 전에 호출됩니다.

DB 테이블을 조작하기 전에 실행되는 후크동작입니다.
```php
public function hookUpdating($form)
{
    return $form;
}
```

DB 수정이 완료된 후에 실행되는 후크 메소드 입니다.
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

db에서 데이터가 삭제되기 호출되는 후크 입니다.
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


### 선택삭제

```php
public function hookCheckDeleting($selected)
{

}
```
