#!/bin/bash

# MedTrack Frontend Deployment Script
# This script helps deploy the frontend to GitHub Pages

echo "🚀 MedTrack Frontend Deployment Script"
echo "======================================"

# Check if git is installed
if ! command -v git &> /dev/null; then
    echo "❌ Git is not installed. Please install Git first."
    exit 1
fi

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo "❌ Not in a git repository. Please run this script from your git repository."
    exit 1
fi

# Get repository URL
REPO_URL=$(git remote get-url origin)
if [ -z "$REPO_URL" ]; then
    echo "❌ No remote origin found. Please add a remote origin first."
    echo "   Example: git remote add origin https://github.com/username/repo-name.git"
    exit 1
fi

echo "📁 Repository: $REPO_URL"

# Check if we're on main branch
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "main" ]; then
    echo "⚠️  You're currently on branch '$CURRENT_BRANCH'. Consider switching to main branch."
    read -p "Do you want to continue? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Deployment cancelled."
        exit 1
    fi
fi

# Check for uncommitted changes
if ! git diff-index --quiet HEAD --; then
    echo "⚠️  You have uncommitted changes. Please commit or stash them first."
    git status --short
    echo
    read -p "Do you want to commit all changes? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        read -p "Enter commit message: " COMMIT_MSG
        if [ -z "$COMMIT_MSG" ]; then
            COMMIT_MSG="Update frontend"
        fi
        git add .
        git commit -m "$COMMIT_MSG"
        echo "✅ Changes committed successfully."
    else
        echo "❌ Please commit or stash your changes before deploying."
        exit 1
    fi
fi

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

# Push changes
echo "📤 Pushing changes to GitHub..."
git push origin main

echo ""
echo "✅ Deployment completed successfully!"
echo ""
echo "🌐 Your site should be available at:"
echo "   https://$(echo $REPO_URL | sed 's/.*github.com\///' | sed 's/\.git//' | sed 's/^/username.github.io\//')"
echo ""
echo "📋 Next steps:"
echo "   1. Go to your GitHub repository"
echo "   2. Click Settings → Pages"
echo "   3. Select 'Deploy from a branch'"
echo "   4. Choose 'main' branch and '/(root)' folder"
echo "   5. Click Save"
echo ""
echo "⏱️  It may take a few minutes for your site to be available."
echo ""
echo "🔍 To check deployment status, visit your repository's Actions tab."
