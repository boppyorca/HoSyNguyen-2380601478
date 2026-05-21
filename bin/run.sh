#!/bin/bash

# Script để chạy CLI tools dễ dàng

show_menu() {
    echo ""
    echo "╔════════════════════════════════════════════════╗"
    echo "║        WEBBANHANG - CLI TOOLS                 ║"
    echo "╚════════════════════════════════════════════════╝"
    echo ""
    echo "1️⃣  Nhập danh mục từ bàn phím"
    echo "2️⃣  Nhập sản phẩm từ bàn phím"
    echo "3️⃣  Xem cấu trúc dự án"
    echo "4️⃣  Thoát"
    echo ""
}

check_php() {
    if ! command -v php &> /dev/null; then
        echo "❌ PHP không được cài đặt!"
        echo "📦 Vui lòng cài đặt PHP 7.0 hoặc cao hơn"
        exit 1
    fi
}

run_import_category() {
    echo "🚀 Khởi động nhập danh mục..."
    echo ""
    php "$(dirname "$0")/import-category.php"
}

run_import_product() {
    echo "🚀 Khởi động nhập sản phẩm..."
    echo ""
    php "$(dirname "$0")/import-product.php"
}

show_structure() {
    echo ""
    echo "📁 Cấu trúc dự án:"
    echo ""
    tree -I '.git|node_modules' -L 2 "$(dirname "$0")/.." || find "$(dirname "$0")/.." -maxdepth 2 -type d | sed 's|^|  |'
    echo ""
}

# Main program
check_php

while true; do
    show_menu
    read -p "Chọn tùy chọn (1-4): " choice

    case $choice in
        1)
            run_import_category
            ;;
        2)
            run_import_product
            ;;
        3)
            show_structure
            ;;
        4)
            echo ""
            echo "👋 Tạm biệt!"
            echo ""
            exit 0
            ;;
        *)
            echo ""
            echo "❌ Tùy chọn không hợp lệ!"
            echo ""
            ;;
    esac
done
