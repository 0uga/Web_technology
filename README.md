# 手順書

## 1. EC2インスタンスに接続
EC2インスタンスを起動し、SSHで接続します

## 2. パッケージのインストールと設定
gitをインストールします
```bash
sudo yum install git -y
```

dockerをインストールし起動します
```bash
sudo yum install -y docker
sudo systemctl start docker
```

ec2-userに権限を与えて反映させます
```bash
sudo usermod -a -G docker ec2-user
exit
```
一度ログアウトして再ログインをしてもらいます<br>


docker composeをインストールします
```bash
DOCKER_CONFIG=${DOCKER_CONFIG:-$HOME/.docker}
mkdir -p $DOCKER_CONFIG/cli-plugins
curl -SL https://github.com/docker/compose/releases/download/v5.1.2/docker-compose-linux-x86_64 -o $DOCKER_CONFIG/cli-plugins/docker-compose
chmod +x $DOCKER_CONFIG/cli-plugins/docker-compose
```

インストールできたかどうかの確認
```bash
docker compose version
```

buildのインストールをします
```bash
mkdir -p ~/.docker/cli-plugins/
curl -SL https://github.com/docker/buildx/releases/download/v0.17.1/buildx-v0.17.1.linux-amd64 -o ~/.docker/cli-plugins/docker-buildx
chmod +x ~/.docker/cli-plugins/docker-buildx
```

screen のインストールをします
```bash
sudo yum install screen -y
```

## 3. リポジトリをクローン
GitHubからリポジトリをクローンします
```bash
git clone https://github.com/0uga/zenki.git
```
ディレクトリを移動します
```bash
cd zenki
```

## 4. コンテナのビルドと起動
screenを起動します
```bash
screen
```
creen起動後の操作
> [!TIP]
> Ctrl+aの後にcで新規作成<br>
> Ctrl+aの後にnで次のウィンドウに移動<br>
> Ctrl+aの後にpで前のウィンドウに移動

コンテナを起動します
```bash
docker compose up
```

新しいウィンドウを作成し、移動してからmysqlを起動します
```bash
docker compose exec mysql mysql example_db
```

mysqlが起動したら、テーブルを作成します
```sql
CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `image_filename` TEXT DEFAULT NULL
);
```

テーブルの作成が完了したら、MySQLから切断します。
```sql
exit
```

## 5. 動作確認
下記のURLにアクセスしてください
```text
http://<EC2のパブリックIPアドレス>/kadai.php
```
テキストが投稿でき、5MB以下の画像をアップロードできれば構築完了になります
