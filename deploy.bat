@echo off
chcp 65001 >nul
echo 🚀 MedTrack Frontend Deployment Script
echo ======================================

REM Check if git is installed
git --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Git is not installed. Please install Git first.
    pause
    exit /b 1
)

REM Check if we're in a git repository
git rev-parse --git-dir >nul 2>&1
if errorlevel 1 (
    echo ❌ Not in a git repository. Please run this script from your git repository.
    pause
    exit /b 1
)

REM Get repository URL
for /f "tokens=*" %%i in ('git remote get-url origin') do set REPO_URL=%%i
if "%REPO_URL%"=="" (
    echo ❌ No remote origin found. Please add a remote origin first.
    echo    Example: git remote add origin https://github.com/username/repo-name.git
    pause
    exit /b 1
)

echo 📁 Repository: %REPO_URL%

REM Check if we're on main branch
for /f "tokens=*" %%i in ('git branch --show-current') do set CURRENT_BRANCH=%%i
if not "%CURRENT_BRANCH%"=="main" (
    echo ⚠️  You're currently on branch '%CURRENT_BRANCH%'. Consider switching to main branch.
    set /p CONTINUE="Do you want to continue? (y/N): "
    if /i not "%CONTINUE%"=="y" (
        echo Deployment cancelled.
        pause
        exit /b 1
    )
)

REM Check for uncommitted changes
git diff-index --quiet HEAD -- >nul 2>&1
if errorlevel 1 (
    echo ⚠️  You have uncommitted changes. Please commit or stash them first.
    git status --short
    echo.
    set /p COMMIT="Do you want to commit all changes? (y/N): "
    if /i "%COMMIT%"=="y" (
        set /p COMMIT_MSG="Enter commit message: "
        if "%COMMIT_MSG%"=="" set COMMIT_MSG=Update frontend
        git add .
        git commit -m "%COMMIT_MSG%"
        echo ✅ Changes committed successfully.
    ) else (
        echo ❌ Please commit or stash your changes before deploying.
        pause
        exit /b 1
    )
)

REM Pull latest changes
echo 📥 Pulling latest changes...
git pull origin main

REM Push changes
echo 📤 Pushing changes to GitHub...
git push origin main

echo.
echo ✅ Deployment completed successfully!
echo.
echo 🌐 Your site should be available at:
echo    https://username.github.io/repository-name
echo.
echo 📋 Next steps:
echo    1. Go to your GitHub repository
echo    2. Click Settings → Pages
echo    3. Select 'Deploy from a branch'
echo    4. Choose 'main' branch and '/(root)' folder
echo    5. Click Save
echo.
echo ⏱️  It may take a few minutes for your site to be available.
echo.
echo 🔍 To check deployment status, visit your repository's Actions tab.
echo.
pause
