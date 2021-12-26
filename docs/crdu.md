# 목록표시
지니Table은 자동으로 index() 메소드를 호출하여 목록을 출력합니다.
기본 index는 미리 설정되어 있어서 선언을 하지 않아도 됩니다. 다만, 이전에 index에 무언가를 작업을 하고 싶다면
오버라이딩을 통하여 index를 재정의 할 수 있습니다.

```php
    public function index(Request $request)
    {
        $code = DB::table('menus')->where('id',$request->id)->first();
        if ($code) {
            return parent::index($request);
        } else {
            return "존재하지 않는 메뉴 코드 입니다.";
        }
    }
```

## 후킹
index는 지정된 테이블의 데이터를 읽어 처리합니다.
만일 테이블을 읽고 처리한후에 후작업이 필요한 경우 후크를 설정할 수 있습니다.

```php
public function hookCreating($value)
    {

    }
```
