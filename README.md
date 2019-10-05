# テスト駆動開発の実践

## サンプルの動作

- github から clone できます。
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

