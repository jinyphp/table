# DashboardController
데쉬보드를 생성관리하는 controller 입니다.

## 컨트롤러 상속하기

```php
use Jiny\Table\Http\Controllers\DashboardController;
class AdminDashboardController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
        $this->setVisit($this);

        ## Action 정보
    }
}
```

## 기본뷰
dashboard는 기본적으로 `jinytable::dashboard.main`을 기본 페이지로 출력됩니다.
만일 사용자 지정 layout을 지정하고 싶을 경우 action 설정을 추가합니다.

```php
$this->actions['view_main'] = "블레이드파일";
```


