# Render Freeでの動作確認用公開

既存のWeb Service（ECsite）のDockerfile Pathは `./Dockerfile`、Docker Commandは空欄にする。
Compute Planは **Free** のまま使用する。有料DB・ディスク・外部メールサービスは作成しない。
このDockerfileは `DEMO_MODE=true` を既定値とする。

## 動作

- ビルド時にComposerとnpmのロックファイルから依存関係をインストールし、型検査・Viteビルドを行う。
- ApacheはRenderの `PORT` で待ち受け、公開ディレクトリを `public/` に限定する。
- 起動時にSQLite DBを作成し、マイグレーションと専用のデモ商品初期化を行う。
- 以前の `DATABASE_URL` / `DB_CONNECTION` はデモモードでは使用しない。
- APP_KEYが未設定なら起動時に生成する。既存の有効なAPP_KEYはそのまま使用できる。
- メールはarrayドライバーを使用し、外部への送信や本文のログ出力を行わない。
- ユーザーや注文の既存Seederは呼び出さず、公開管理者アカウントは作成しない。
- 初期商品は架空の商品2点とSVG画像。データは休止・再デプロイ・再起動などで失われる。
- 本番販売・決済・個人情報の保存には使用しない。画面上にデモの注意書きを表示する。

## ローカル確認

```sh
docker build -t ecsite-render-demo .
docker run --rm -p 127.0.0.1:18080:10000 -e APP_ENV=local ecsite-render-demo
```

http://localhost:18080 で商品一覧、カート、Vue画面を確認する。
Renderでは `APP_ENV=production` により生成URLをHTTPSにする。
既存のMac用dockerブランチ・Compose・DBには変更を加えない。

## 無料枠

15分無通信で休止し、次のアクセス時には起動待ちが発生する。
Free Web Serviceのローカルファイルは永続化されない。
無料の稼働時間・ビルド時間・転送量には上限がある。
支払方法があるWorkspaceでは超過課金の可能性があるため、Billingの利用量・Spend Limitを確認する。
この修正はプラン変更や支払方法登録を行わない。

https://render.com/docs/free
