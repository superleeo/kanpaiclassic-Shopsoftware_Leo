#!/bin/bash

# ============================================================================
# 妙味日本料理铁板烧 - 快速启动脚本
# Miaowei Teppanyaki - Quick Setup Script
# ============================================================================

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║  欢迎使用 Kanpai Classic III 购物系统                           ║"
echo "║  Welcome to Kanpai Classic III Shop Software                   ║"
echo "║  妙味日本料理铁板烧 | Miaowei Teppanyaki                      ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# 检查环境
echo "📋 检查系统环境..."
echo ""

# 检查PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | cut -d' ' -f2)
    echo "✅ PHP已安装: $PHP_VERSION"
else
    echo "❌ 错误: PHP未找到，请先安装PHP 7.4+"
    exit 1
fi

# 检查MySQL
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version | cut -d' ' -f6)
    echo "✅ MySQL已安装: $MYSQL_VERSION"
else
    echo "⚠️  警告: MySQL命令行工具未找到"
    echo "        请确保MySQL/MariaDB正在运行"
fi

# 检查必要的目录权限
echo ""
echo "📁 检查目录权限..."

DIRS_TO_CHECK=(
    "/Users/superleo/Documents/FLOW-Shopsoftware_MiaoWei/admin/backup"
    "/Users/superleo/Documents/FLOW-Shopsoftware_MiaoWei/downloads"
    "/Users/superleo/Documents/FLOW-Shopsoftware_MiaoWei/tmp"
    "/Users/superleo/Documents/FLOW-Shopsoftware_MiaoWei/pictures/original"
)

for dir in "${DIRS_TO_CHECK[@]}"; do
    if [ -d "$dir" ]; then
        # 尝试在目录中创建一个测试文件
        if touch "$dir/.write-test" 2>/dev/null; then
            rm "$dir/.write-test"
            echo "✅ 目录可写: $dir"
        else
            echo "⚠️  目录可能不可写: $dir"
        fi
    else
        echo "⚠️  目录不存在: $dir"
    fi
done

echo ""
echo "═══════════════════════════════════════════════════════════════════"
echo ""
echo "📚 后续步骤:"
echo ""
echo "1️⃣  系统安装"
echo "   - 访问: http://localhost/admin/install/install.php"
echo "   - 按照向导完成安装"
echo "   - 会创建 admin/config.inc.php"
echo ""
echo "2️⃣  导入初始数据 (可选)"
echo "   - 文件: INIT_SHOP_DATA.sql"
echo "   - 命令: mysql -u root -p flow_shop_miaowei < INIT_SHOP_DATA.sql"
echo ""
echo "3️⃣  访问店铺前台"
echo "   - URL: http://localhost/index.php"
echo ""
echo "4️⃣  访问管理后台"
echo "   - URL: http://localhost/admin/index.php"
echo "   - 使用安装时设置的管理员账号"
echo ""
echo "═══════════════════════════════════════════════════════════════════"
echo ""
echo "📖 完整指南:"
echo "   - 请查看: SETUP_GUIDE_CN.md"
echo ""
echo "💡 提示:"
echo "   - 在部署前，务必更改默认密码"
echo "   - 启用HTTPS/SSL证书"
echo "   - 配置备份策略"
echo "   - 配置邮件服务"
echo ""
echo "═══════════════════════════════════════════════════════════════════"
echo ""
echo "✨ 祝您使用愉快！"
echo ""
