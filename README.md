# テスト駆動開発の実践（前半）
<!-- TOC -->

- [テスト駆動開発の実践（前半）](#テスト駆動開発の実践前半)
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
        - [Route 内の処理を Controller へ](#route-内の処理を-controller-へ)
        - [mysql 8.0  user への権限付与](#mysql-80--user-への権限付与)

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

- リファクタリング後にテストを実行する
- テストが成功の場合、リファクタリングがうまくいっている
  - （外部から見た動作が変わらない）
- テストが失敗の場合、リファクタリングがうまくいっていない
  - （外部からみた動作が変わってしまっている）

### Route 内の処理を Controller へ



### mysql 8.0  user への権限付与

- user の確認

```sql
SELECT user, host FROM mysql.user;
```

- 権限の付与

```sql
GRANT ALL ON test_database.* TO 'test_user';
```