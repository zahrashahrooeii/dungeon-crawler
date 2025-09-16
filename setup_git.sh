#!/bin/bash

# Setup script for initializing the dungeon crawler Git repository

echo "🎮 Setting up Dungeon Crawler Git Repository"
echo "============================================="

# Initialize git repository
echo "📁 Initializing Git repository..."
git init

# Add all files
echo "📝 Adding files to Git..."
git add .

# Create initial commit
echo "💾 Creating initial commit..."
git commit -m "Initial commit: Dungeon Crawler Game

- Complete text-based dungeon crawler implementation
- 12 interconnected rooms with monsters and treasure
- Turn-based combat system with 5 monster types
- Comprehensive unit test suite (21 test cases)
- Clean code structure with separation of concerns
- GitHub Actions CI/CD pipeline
- Professional project structure with documentation

Features:
✅ All core requirements implemented
✅ Optional enhancements included
✅ Multi-version PHP testing (7.4-8.2)
✅ Automated testing and quality checks
✅ Release automation with GitHub Actions"

echo ""
echo "✅ Git repository initialized successfully!"
echo ""
echo "Next steps:"
echo "1. Create a new repository on GitHub: https://github.com/new"
echo "2. Add the remote origin:"
echo "   git remote add origin https://github.com/zahrashahrooeii/dungeon-crawler.git"
echo "3. Push to GitHub:"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "To create a release:"
echo "   git tag v1.0.0"
echo "   git push origin v1.0.0"
echo ""
echo "🎉 Your dungeon crawler is ready for GitHub!"
