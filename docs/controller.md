# 컨트롤러
지니Table은 Livewire를 응용하여 쉽게 CRUD 작업을 처리할 수 있는 컨트롤러를 제공합니다.

## LiveController
Fontside를 위한 CRUD 컨트롤러 입니다. 기본 controller 대신에 LiveController를 상속하여
사용합니다. 

```php
use Jiny\Table\Http\Controllers\LiveController;
class TranslateController extends LiveController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);

        // 테이블 설정
    }
}
```

LiveController 에는 CRUD를 위한 다양한 메소드들이 선언되어 있습니다.
또한, Livewire와 상호 작용을 위하여 초기화 작업이 필요합니다. 이를 생성 메소드에 같이 정의해 주면 됩니다.

## AdminController
