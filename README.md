# テスト駆動開発の実践
<!-- TOC -->

- [テスト駆動開発の実践](#テスト駆動開発の実践)
    - [サンプルの動作](#サンプルの動作)
    - [テスト駆動開発とは](#テスト駆動開発とは)
    - [テスト駆動開発のプロセス概要](#テスト駆動開発のプロセス概要)
        - [TDD でない実装](#tdd-でない実装)
        - [TDD の実装](#tdd-の実装)
        - [Laravel サンプルアプリケーションによる TDD 実践](#laravel-サンプルアプリケーションによる-tdd-実践)
        - [アプリケーションの仕様確認(p467)](#アプリケーションの仕様確認p467)
        - [アプリケーションの作成・事前準備](#アプリケーションの作成・事前準備)
        - [最初のテスト](#最初のテスト)
            - [最初の TODO リスト](#最初の-todo-リスト)
            - [テストファイルの作成](#テストファイルの作成)
            - [テストメソッドの追加](#テストメソッドの追加)
            - [テストメソッドの実装](#テストメソッドの実装)
            - [最低限の実装](#最低限の実装)
    - [テストに備えるデータベース設定](#テストに備えるデータベース設定)
        - [Laravel 5 IDE helper Generator について](#laravel-5-ide-helper-generator-について)
        - [each() の使い方](#each-の使い方)
    - [テスト駆動開発の実践（リファクタリング）](#テスト駆動開発の実践リファクタリング)
        - [リファクタリングの流れ](#リファクタリングの流れ)
        - [イメージ](#イメージ)
        - [リファクタリングとは](#リファクタリングとは)
        - [リファクタリングを担保するために](#リファクタリングを担保するために)
        - [実装確認（Route 内の処理を Controller へ）](#実装確認route-内の処理を-controller-へ)
        - [実装確認（validation 処理を独自実装から Laravel 機能利用へ）](#実装確認validation-処理を独自実装から-laravel-機能利用へ)
        - [実装確認（ビジネスロジックを Controller から Service へ）](#実装確認ビジネスロジックを-controller-から-service-へ)
        - [実装確認（最終形）](#実装確認最終形)
    - [appendix](#appendix)
        - [本章の sample code](#本章の-sample-code)
        - [mysql 8.0  user への権限付与](#mysql-80--user-への権限付与)
        - [目次作成](#目次作成)

<!-- /TOC -->
## サンプルの動作

- 以下の手順で開発環境を構築できます。

```sh
# github からソースをクローンします。
git clone https://github.com/ShoheiImamura/laravel-chapter11.git

# laradock ディレクトリに移動します。
cd .\laravel-chapter11\

# laradock 用の .env ファイルを作成します。
cp env-tdd_sample .env

# docker を立ち上げます。
docker-compose up -d php-fpm nginx mysql workspace

# laravel プロジェクト用の .env ファイルを作成します。
docker-compose run workspace cp .env.tdd_sample .env

# compsoer install を実行します。
docker-compose run workspace composer install

# アプリケーションキーを生成します。
docker-compose run workspace php artisan key:generate
```

- http://localhost:80 でアクセスできます。

## テスト駆動開発とは

- 開発手法の一つ
- ゴールは、「きれいな実装」で「きちんと動作する」コード
- コンセプトは、「できるだけ小さく」
  - テストはできるだけ小さく
  - 最初の実装に掛ける時間はできるだけ短く
  - リファクタリングのテスト実行間隔はできるだけ短く

## テスト駆動開発のプロセス概要

- 目的は、「プログラマの集中力が途切れなくすること」
- TDD のプロセスは、関心の対象をできるだけ小さくするプロセス

![](/images/TDDプロセスイメージ.png)

1. 「きちんと動作する」ことを確認するコードを書く
2. 実装はせず、まずテストが「失敗すること」を確認する
3. きれいでなくてもいいから、できるだけ早くテストを成功させるための実装を行う
4. テストが成功することを確認する
5. テストが失敗しないことを確認しながら、「きれいな実装」を目指してリファクタリングする

### TDD でない実装

![TDD でない実装](/images/sample.png)

### TDD の実装

![TDD の実装](/images/tdd_sample.png)

### Laravel サンプルアプリケーションによる TDD 実践

![](/images/TDDプロセスの実際.png)

### アプリケーションの仕様確認(p467)

- 機能要件
- 画面デザイン、画面遷移図
- データベース仕様
- API エンドポイント
  - 参考：[0からREST APIについて調べてみた](https://qiita.com/masato44gm/items/dffb8281536ad321fb08)

### アプリケーションの作成・事前準備

- 今回は、開発環境を laradock で構築しています。
  - [プロジェクトルート](./tdd_sample/)

### 最初のテスト

- まずは、TODO リストを作成する
- TODO リストの項目ごとに、「テスト作成→最低限の実装→リファクタリング」のサイクルを回す
- 最初の TODO リストは機能要求や仕様を元に作成される
- 開発を進めていく中で TODO リストが分解、追加されていく

#### 最初の TODO リスト

- [ ] api/customersにGETメソッドでアクセスできる
- [ ] api/customersにPOSTメソッドでアクセスできる
- [ ] api/customers/{customer_id}にGETメソッドでアクセスできる
- [ ] api/customers/{customer_id}にPUTメソッドでアクセスできる
- [ ] api/customers/{customer_id}にDELETEメソッドでアクセスできる
- [ ] api/reportsにGETメソッドでアクセスできる
- [ ] api/reportsにPOSTメソッドでアクセスできる
- [ ] api/reports/{report_id}にGETメソッドでアクセスできる
- [ ] api/reports/{report_id}にPOSTメソッドでアクセスできる
- [ ] api/reports/{report_id}にPUTメソッドでアクセスできる
- [ ] api/reports/{report_id}にDELETEメソッドでアクセスできる

#### テストファイルの作成

- artisan コマンドでテストファイルを作成する
  - API のテストは、Feature 配下に作成する

#### テストメソッドの追加

- メソッド名に TODO リストの項目をそのまま使うとわかりやすい
  - メソッド名は、日本語で問題ない
  - `「/」` はメソッド名に使えない
- 一つのテストメソッドで、一つの項目を検証する

#### テストメソッドの実装

- 「検証」部分を先に記述し、「実行」部分を後に記述する
  - 「検証」を記述した時点で、テストを実行し「失敗」を確認する
  - 「実行」を記述し、再度テストを実行し「失敗」を確認する
  - 上記は、失敗の内容が変わることを確認できる

#### 最低限の実装

![](/images/TDDプロセスイメージ_最低限の実装.png)

- 目的は、「テストを通すこと」

```php
// テストコード tests/Feature/ReportTest.php
public function api_customersにGETメソッドでアクセスできる()
{
    $response = $this->get('api/customers');    // 実行部分
    $response->assertStatus(200);               // 検証部分
}
```

```php
// 実装 Routes/api.php
Route::get('customers', function() {});         // 200 のレスポンスを返す
```

- テストを実行し成功することを確認すれば TODO は完了となる

## テストに備えるデータベース設定

- phpunit.xml にテスト用データベースの設定を記述する
- テーブル定義用のマイグレーションファイルを作成する
- テストデータ投入用のシーダーを作成する

![](/images/DB準備.png)

- データベース操作用の、Modelクラスを用意する

![](/images/Model.png)

- ダミーデータ作成用に、Factory クラスを用意する

### Laravel 5 IDE helper Generator について

- artisan コマンドで、Eloquent の phpDocs を自動生成する
- Eloquent のプロパティやリレーションが補完されて便利

使い方

```sh
# doctrine/dbal のインストール
# barryvdh/laravel-ide-helper のインストール
composer require "doctrine/dbal" "barryvdh/laravel-ide-helper"
```

```sh
# artisan コマンドの実行
php artisan ide-helper:models -W -R
```

### each() の使い方

- 参考：[Laravel 5.5 コレクション](https://readouble.com/laravel/5.5/ja/collections.html) 、[Laravel 5.5 データベースのテスト](https://readouble.com/laravel/5.5/ja/database-testing.html)

```php
$users = factory(App\User::class, 3)
           ->create() // create() メソッドでインスタンスのコレクションが返される
           ->each(function ($u) { // コレクションに対して each() を適用
                $u->posts()->save(factory(App\Post::class)->make());
            });
```

## テスト駆動開発の実践（リファクタリング）

### リファクタリングの流れ

1. Route 内の処理を Controller へ移設する
2. validation 処理を Laravel のフレームワークを利用した形式に変える
3. Controller 内のビジネスロジックを Service へ移設する

### イメージ

- Route 内に処理記述されている

![](/images/リファクタリング_Route.png)

---
- 処理を Route から Controller へ移設
  - Route の役割を、http アクセスのルーティングのみとする
  - まだ controller から model を操作してしまっている

![](/images/リファクタリング_Controller.png)

---
- ビジネスロジックを Controller から Service へ移設
  - Controller の役割を、処理（Service）の選択、Response の作成のみとする
  - ビジネスロジックは、Service に任せる

![](/images/リファクタリング_Service.png)

※ DB 操作は、ビジネスロジックとは関係ないため、更に Repository 層を設ける場合もある(p113参照)

### リファクタリングとは

- プログラムの外部から見た動作を変えずにソースコードの内部構造を整理すること

### リファクタリングを担保するために

- リファクタリング前後にテストを実行する
- テストが成功の場合、リファクタリングがうまくいっている
  - （外部から見た動作が変わらない）
- テストが失敗の場合、リファクタリングがうまくいっていない
  - （外部からみた動作が変わってしまっている）

### 実装確認（Route 内の処理を Controller へ）

- [routes/api.php](/tdd_sample/routes/api.php)
- [app/Http/Controllers/ApiController.php](/tdd_sample/app/Http/Controllers/ApiController.php)

```sh
# Route 処理 移設前
git checkout 9974eaba

# Route 処理 移設後(getCustomers)
git checkout 19ceaaa0

# Route 処理 移設後(postCustomer)
git checkout 959f5019

# Route 処理 移設(残りすべて)
git checkout 4bc1d164
```

### 実装確認（validation 処理を独自実装から Laravel 機能利用へ）

- バリデーション内容
  - 必須項目 "name" をチェックする
  - リクエスト不正の場合、422:HTTP_UNPROCESSABLE_ENTITY を返却する

- [app/Http/Controllers/ApiController.php](/tdd_sample/app/Http/Controllers/ApiController.php)

```sh
# 独自実装
git checkout 4bc1d164

# Laravel 機能(validation()) 利用
git checkout 5d84e5c3
```

- [422 Unprocessable Entity](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422) とは
  - ステータスコード：422
  - content type、syntax は OK
  - リクエスト内容が NG

### 実装確認（ビジネスロジックを Controller から Service へ）

- [app/Http/Controllers/ApiController.php](/tdd_sample/app/Http/Controllers/ApiController.php)
- [app/Services/CustomerService.php](/tdd_sample/app/Services/CustomerService.php)

```sh
# 独自実装
git checkout 9a306d0f

# Laravel 機能(validation()) 利用
git checkout 57dbe2a2
```

### 実装確認（最終形）

- TODO（テストメソッド）リストの追加、分類

```sh
# CustomerTest
  - api_customers_customer_idにDELETEメソッドで訪問記録がある顧客の場合
  - api_customers_customer_idにDELETEメソッドで訪問記録がない顧客が削除できる
  - api_customers_customer_idに存在しないcustomer_idでPUTメソッドでアクセスすると404が返却される
  - api_customers_customer_idにPUTメソッドで顧客名が編集できる
  - api_customers_customer_idに存在しないcustomer_idでGETメソッドでアクセスすると404が返却される
  - api_customers_customer_idにGETメソッドでアクセスすると顧客情報が返却される
  - POST_api_customersのエラーレスポンスの確認
  # ここから↓
  - POST_api_customersにnameが空の場合422UnprocessableEntityが返却される
  - POST_api_customersにnameが含まれない場合422UnprocessableEntityが返却される
  - api_customersに顧客名をPOSTするとcustomersテーブルにそのデータが追加される
  - api_customersにGETメソッドでアクセスすると2件の顧客リストが返却される
  - api_customersにGETメソッドで取得できる顧客リストのJSON形式は要件通りである
  - api_customersにGETメソッドでアクセスするとJSONが返却される
  - api_customersにGETメソッドでアクセスできる
  - api_customersにPOSTメソッドでアクセスできる
  - api_customers_customer_idにGETメソッドでアクセスできる
  - api_customers_customer_idにPUTメソッドでアクセスできる
  - api_customers_customer_idにDELETEメソッドでアクセスできる
  # ここまで↑ は実装済み
# ReportTest
  - api_reports_report_idにDELETEメソッドで訪問記録が削除できる
  - api_reports_report_idに存在しないreport_idでPUTメソッドでアクセスすると404が返却される
  - api_reports_report_idにPUTメソッドで訪問記録が編集できる
  - api_reports_report_idに存在しないreport_idでGETメソッドでアクセスすると404が返却される
  - api_reports_report_idにGETメソッドでアクセスすると訪問記録が返却される
  - POST_api_reportsに存在しないcustomer_idがPOSTされた場合422UnprocessableEntityが返却される
  - POST_api_reportsにvisit_dateが不正な日付の場合422UnprocessableEntityが返却される($params)
  - POST_api_reportsに必須データがない場合422UnprocessableEntityが返却される($params)
  - api_reportsにPOSTするとreportsテーブルにそのデータが追加される
  - api_reportsにGETメソッドでアクセスすると4件の訪問記録が返却される
  - api_reportsにGETメソッドで取得できる顧客リストのJSON形式は要件通りである
  - api_reportsにGETメソッドでアクセスするとJSONが返却される
  - api_reportsにGETメソッドでアクセスできる
  - api_reportsにPOSTメソッドでアクセスできる
  - api_reports_report_idにGETメソッドでアクセスできる
  - api_reports_report_idにPUTメソッドでアクセスできる
  - api_reports_report_idにDELETEメソッドでアクセスできる
```

- Controller 実処理追加

```php
class ApiController extends Controller
{
    public function getCustomers(CustomerService $customer_service);
    public function postCustomer(Request $request, CustomerService $customer_service);
    public function getCustomer($customer_id, CustomerService $customer_service);
    public function putCustomer($customer_id, Request $request, CustomerService $customer_service);
    public function deleteCustomer($customer_id, CustomerService $customer_service);
    public function getReports(ReportService $report_service);
    public function postReport(Request $request, ReportService $report_service);
    public function getReport($report_id, ReportService $report_service);
    public function putReport($report_id, Request $request, ReportService $report_service);
    public function deleteReport($report_id, ReportService $report_service);
}
```

- Service 実処理追加、分類

```php
// CustomerService
class CustomerService
{
    public function getCustomers();
    public function postCustomer($name);
    public function getCustomer($customer_id);
    public function updateCustomer($customer_id, $name);
    public function exists($customer_id);
    public function deleteCustomer($customer_id);
    public function hasReports($customer_id);
}

// ReportService
class ReportService
{
    public function getReports();
    public function postReport(array $params);
    public function getReport($report_id);
    public function exists($report_id);
    public function updateReport($report_id, array $params);
    public function deleteReport($report_id);
}
```

- route, model は変更なし

## appendix

### 本章の sample code

- [laravel-socym/tdd_sample](https://github.com/laravel-socym/tdd_sample)

### mysql 8.0  user への権限付与

- user の確認

```sql
SELECT user, host FROM mysql.user;
```

- 権限の付与

```sql
GRANT ALL ON test_database.* TO 'test_user';
```

### 目次作成

- [Visual Studio CodeでMarkdown目次(TOC)を作成する便利Plugin](https://qiita.com/bj1024/items/16ec641dc88f74028192)