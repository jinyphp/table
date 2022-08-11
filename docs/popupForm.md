# popupForm
라이브와이어를 이용하여 팝업창 동작을 처리합니다.

## create
신규 데이터를 삽입하는 동작을 처리합니다.

팝업창을 활성화 하기 위해서는 emit 버튼을 배치 합니다.
이를 쉽게 처리하기 위하여 `x-btn-emitCreate` 컴포넌트를 제공합니다.
```php
<x-btn-emitCreate>
    <span>신규추가</span>
</x-btn-emitCreate>
```

버튼을 클릭하여 라이브와이어의 create 메소드가 호출됩니다.

### 후킹:: hookCreating
create 폼이 출력되기 전에 동작을 합니다. 사용자 정의값을 추가하고자 할때 유용합니다.
초기값을 지정할때 유용합니다.

```php
## 생성폼이 실행될때 호출됩니다.
public function hookCreating($wire, $value)
{
    // 생략가능
    return $form; // 설정시 form 입력 초기값으로 설정됩니다.
}
```

초기값을 설정한 후에는 반드시 `$form`을 반환해 주어야만, 값이 설정이 됩니다.

다음은 생성 레코드에 인증id를 추가하는 예시 입니다.
```php
## 생성폼이 실행될때 호출됩니다.
    public function hookCreating($wire, $value)
    {
        // 인증 사용자 id를 추가합니다.
        $user = Auth::user();
        if($user) {
            $form['user_id'] = $user->id;
        }

        return $form; // 설정시 form 입력 초기값으로 설정됩니다.
    }
```

### 후킹:: store
새로운 데이터를 삽입하는 `store`는 2개의 후크를 설정할 수 있습니다.
데이터 삽입전에 전처리를 위한 `hookStoring`과 데이터를 삽입후 동작하는 후처리 `hookStored` 입니다.

데이터를 삽입하게 되면 `$form['id']` 값으로 입력된 레코드의 id값을 확인 할 수 있습니다. 
또는 
```php
$wire->last_id 
```
값으로도 확인 가능합니다.
