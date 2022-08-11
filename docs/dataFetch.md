# dataFetch
후크를 이용하여 livewire의 dataFetch 데이터 조작을 제어할 수 있습니다.

## 테이블
리소스는 테이블명을 지정할때, `$db`객체를 생성합니다. 이후, 후크를 통하여 DB에 조건을 추가할 수 있습니다.

## setUserRelation
`setUserRelation`는 사용자 정보와 일치하는 레코드만 테이블에서 조회할 수 있는 조건을 추가합니다.
```php
setUserRelation($id=null, $relation=null)
```

### 인증 User 레코드
테이블에 `user_id` 필드가 있을 경우 현재 인증된 사용자id와 일치하는 레코드만을 조회합니다.
```php
$wire->setUserRelation();
```

또는 직접 사용자 id값을 지정하여 측정 데이터만의 레코드를 조회 할 수 있습니다.

```php
$wire->setUserRelation(2);
```

### 외부 relation 테이블을 참조하여 검색하기
M:N의 복수의 조건을 가지는 사용자 레코드의 경우 외부 관계테이블을 추가하여 레코드를 조회할 수 있습니다.
첫번째 id인자값을 null로 전달하는 경우 현재 인증된 사용자id와 일치되는 관계형 테이블을 조회합니다.
조회된 관계 테이블을 이용하여 실제 레코드를 검색합니다.

```php
$wire->setUserRelation(null, 'jinyerp_tms_project_users');
```

또는 특정 사용자 id를 지정할 수도 있습니다.

```php
$wire->setUserRelation(2, 'jinyerp_tms_project_users');
```

## where 조건 추가
데이터조회는 총 3가지의 조건을 추가하여 데이터를 제한합니다.
* action where 정보
* 사용자 where 정보
* filter where 정보

### actions Where

