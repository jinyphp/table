# wire 연결
livewire에서 컨트롤러의 로직 메서드를 호출할 수 있습니다.

```php
wire:click="call('division','{{$item->division}}')"
```

call메소드는 컨트롤러의 `division`메소드를 호출합니다. 또한 뒤의 값은 인자값 입니다.

예제코드
컨트롤러에 다음과 같은 예제 메소드를 선언합니다.
```php
public function division($wire, $args)
    {
        $div = explode(':',$args[0]);
        $wire->filter['division'] = $div[1];
    }
```

라이브와이어에서 클릭시 컨트롤러의 division 메소드를 호출하며, division 메소드는 라이브와이어의 값을 변경하여
데이터를 필터링 하는 기능을 활성화 합니다.
