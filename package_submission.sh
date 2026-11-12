#!/usr/bin/env bash
# ==============================================================================
# Packaging Script for LMS Digital Submission
# Output: Group13_CoachingManagement_FinalDB.zip
# Requirements:
#   - Soft copy of report (if present in docs/ or root)
#   - /database/ folder with schema.sql and sample_data.sql
#   - /source_code/ folder with Frontend, Backend, database, and README.md
# ==============================================================================

set -e

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PACKAGE_NAME="Group13_CoachingManagement_FinalDB"
TEMP_DIR="${PROJECT_ROOT}/build_package/${PACKAGE_NAME}"
ZIP_OUTPUT="${PROJECT_ROOT}/${PACKAGE_NAME}.zip"

echo "==> Preparing build directory: ${TEMP_DIR}"
rm -rf "${PROJECT_ROOT}/build_package"
rm -f "${ZIP_OUTPUT}"
mkdir -p "${TEMP_DIR}/database"
mkdir -p "${TEMP_DIR}/source_code"

# 1. Populate /database folder
echo "==> Copying database scripts and ERD..."
cp "${PROJECT_ROOT}/database/schema.sql" "${TEMP_DIR}/database/schema.sql"
cp "${PROJECT_ROOT}/database/sample_data.sql" "${TEMP_DIR}/database/sample_data.sql"
cp "${PROJECT_ROOT}/database/Group-13(240221 & 240222).png" "${TEMP_DIR}/database/ERD.png"

# 2. Populate /source_code folder
echo "==> Copying source code (backend, frontend, configs)..."
cp -r "${PROJECT_ROOT}/backend" "${TEMP_DIR}/source_code/"
cp -r "${PROJECT_ROOT}/frontend" "${TEMP_DIR}/source_code/"
mkdir -p "${TEMP_DIR}/source_code/database"
cp "${PROJECT_ROOT}/database/schema.sql" "${TEMP_DIR}/source_code/database/schema.sql"
cp "${PROJECT_ROOT}/database/sample_data.sql" "${TEMP_DIR}/source_code/database/sample_data.sql"
cp "${PROJECT_ROOT}/index.php" "${TEMP_DIR}/source_code/"
cp "${PROJECT_ROOT}/landing.css" "${TEMP_DIR}/source_code/"
cp "${PROJECT_ROOT}/.env.example" "${TEMP_DIR}/source_code/"
cp "${PROJECT_ROOT}/README.md" "${TEMP_DIR}/source_code/"

# 3. Include Project Report & Presentation Slides (if exists)
if compgen -G "${PROJECT_ROOT}/*.pdf" > /dev/null; then
    echo "==> Copying project report PDF..."
    cp "${PROJECT_ROOT}"/*.pdf "${TEMP_DIR}/"
elif compgen -G "${PROJECT_ROOT}/docs/*.pdf" > /dev/null; then
    echo "==> Copying project report PDF from docs/..."
    cp "${PROJECT_ROOT}/docs"/*.pdf "${TEMP_DIR}/"
else
    echo "==> NOTE: No PDF report found yet. A placeholder notice will be added."
    cat << 'EOF' > "${TEMP_DIR}/PLACE_REPORT_PDF_HERE.txt"
Add your final printed workbook / project report PDF here before final LMS upload:
e.g. Report_Group13_CoachingManagement.pdf
EOF
fi

if compgen -G "${PROJECT_ROOT}/docs/*.pptx" > /dev/null; then
    echo "==> Copying presentation slide deck (.pptx)..."
    cp "${PROJECT_ROOT}/docs"/*.pptx "${TEMP_DIR}/"
fi

# 4. Create ZIP archive
echo "==> Creating final archive: ${PACKAGE_NAME}.zip..."
cd "${PROJECT_ROOT}/build_package"
zip -r -q "${ZIP_OUTPUT}" "${PACKAGE_NAME}"

# Cleanup temporary build folder
rm -rf "${PROJECT_ROOT}/build_package"

echo "=============================================================================="
echo " [SUCCESS] Submission package created at:"
echo " ${ZIP_OUTPUT}"
echo "=============================================================================="
unzip -l "${ZIP_OUTPUT}"

