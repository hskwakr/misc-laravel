#!/usr/bin/env bash
set -euo pipefail

# 関数定義
usage() {
    echo "Usage: $0 <directory_name>" >&2
}

check_directory() {
    local directory_path="$1"
    if [ ! -d "$directory_path" ]; then
        echo "Error: Directory '$directory_path' does not exist" >&2
        return 1
    fi

    if [ ! -f "$directory_path/composer.json" ]; then
        echo "Error: composer.json not found in '$directory_path'" >&2
        return 1
    fi

    if [ -f "$directory_path/vendor/bin/sail" ]; then
        echo "Error: vendor/bin/sail already exists in '$directory_path'" >&2
        return 1
    fi

    return 0
}

check_docker() {
    if ! command -v docker >/dev/null 2>&1; then
        echo "Error: docker command not found" >&2
        return 1
    fi

    return 0
}

# 引数のチェック
if [ "$#" -ne 1 ]; then
    usage
    exit 1
fi

project_dir="$1"

# ディレクトリの存在チェック
check_directory "$project_dir" || exit 1

# dockerコマンドの存在チェック
check_docker || exit 1

cd "$project_dir"

# composer install
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

# .env.exampleから.envを作成
if [ -f ".env.example" ] && [ ! -f ".env" ]; then
    cp .env.example .env
fi

# sailコマンドの存在チェック
if [ -f "vendor/bin/sail" ]; then
    # コンテナ起動
    vendor/bin/sail up -d

    vendor/bin/sail artisan key:generate
    vendor/bin/sail artisan migrate:fresh --seed
fi
