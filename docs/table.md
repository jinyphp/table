# table 출력
데이터베이스 입출력을 윈한 테이블 컴포넌트입니다.

## table 출력
데이터베이스 테이블을 출력하는 테이블 컴포넌트입니다.

### 라이브와이어
`livewire`를 통하여 동작하는 테이블 컴포넌트 입니다.

#### table
지정한 테이블의 모든 데이터를 출력합니다. 간단한 데이터와 변경기능이 필요없을때 유용하게 사용할 수 기본 테이블 컴포넌트 입니다.

```php
@livewire('table', ['actions'=>$actions])
```
#### table-filter: 조건필터 포함 테이블
페이지네이션과 검색 필터를 가지는 테이블 컴포넌트 입니다.

```php
@livewire('table-filter', ['actions'=>$actions])
```




* table-delete : 선택삭제 기능을 포함하는 테이블

#### table-delete-create : 
`table-delete` + 생성기능 포함 테이블

```php
@livewire('table-delete-create', ['actions'=>$actions])
```


### ajax 통신
* table-ajax : ajax 통신을 통한 테이블 출력

## form 입력 및 수정

### 팝업폼


#### 팝업창
* from-popup : 팝업창 Create/Edit/Delete
* form-popup-create
* from-popup-edit

#### ajax 통신
* from-popup-ajax : 팝업창 Create/Edit/Delete
* form-popup-create-ajax
* from-popup-edit-ajax

### 일반폼

#### 라이브와이어
* from-create
* form-edit
* form-delete

#### ajax 통신
* from-create-ajax
* form-edit-ajax
* form-delete-ajax

