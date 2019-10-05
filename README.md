# テスト駆動開発の実践

## はじめに

-

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
