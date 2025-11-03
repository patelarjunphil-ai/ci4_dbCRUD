#!/bin/bash

# This script copies the application files to your local CodeIgniter project directory.
#
# Usage:
# 1. Place this script in the root of the project you want to copy from.
# 2. Open your terminal.
# 3. Make the script executable by running: chmod +x install_files.sh
# 4. Run the script with the path to your local CodeIgniter project as the argument:
#    ./install_files.sh /path/to/your/local/codeigniter-project
#
# Example:
#    ./install_files.sh ~/sites/my-product-app

# --- CONFIGURATION ---
# Source directory (where this script is located)
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

# --- VALIDATION ---
# Check if a destination directory was provided
if [ -z "$1" ]; then
  echo "Error: No destination directory provided."
  echo "Usage: $0 /path/to/your/local/codeigniter-project"
  exit 1
fi

DESTINATION_DIR="$1"

# Check if the destination directory exists
if [ ! -d "$DESTINATION_DIR" ]; then
  echo "Error: Destination directory '$DESTINATION_DIR' not found."
  echo "Please make sure your local CodeIgniter project folder exists."
  exit 1
fi

echo "Source Directory: $SOURCE_DIR"
echo "Destination Directory: $DESTINATION_DIR"
echo "---"

# --- FILE COPYING ---

echo "Copying application files..."

# Copy the 'app' directory
# The -T option treats the source as a file, ensuring the contents are copied into the destination
cp -r "$SOURCE_DIR/app" "$DESTINATION_DIR/app"
echo "✅ Copied 'app' directory."

# Copy the 'public' directory contents
# Create the public/assets/js directory structure if it doesn't exist
mkdir -p "$DESTINATION_DIR/public/assets/js"
cp "$SOURCE_DIR/public/assets/js/main.js" "$DESTINATION_DIR/public/assets/js/main.js"
echo "✅ Copied 'public/assets/js/main.js'."

# Copy the composer.json file
cp "$SOURCE_DIR/composer.json" "$DESTINATION_DIR/composer.json"
echo "✅ Copied 'composer.json'."

echo "---"
echo "🚀 File installation complete!"
echo ""
echo "--- NEXT STEPS ---"
echo "1. Navigate to your project directory: cd $DESTINATION_DIR"
echo "2. Install dependencies: composer install"
echo "3. Rename 'env' to '.env' and configure your database settings."
echo "4. Run migrations: php spark migrate"
echo "5. Start the server: php spark serve"
echo "--------------------"

exit 0
