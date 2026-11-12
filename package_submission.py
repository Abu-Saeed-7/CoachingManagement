#!/usr/bin/env python3
"""
Packaging Script for LMS Digital Submission (Native Windows / Cross-Platform)
Output: Group13_CoachingManagement_FinalDB.zip
"""

import os
import shutil
import zipfile

def package_project():
    root = os.path.dirname(os.path.abspath(__file__))
    pkg_name = "Group13_CoachingManagement_FinalDB"
    build_dir = os.path.join(root, "build_package", pkg_name)
    zip_path = os.path.join(root, f"{pkg_name}.zip")

    print(f"==> Preparing build directory: {build_dir}")
    if os.path.exists(os.path.join(root, "build_package")):
        shutil.rmtree(os.path.join(root, "build_package"), ignore_errors=True)
    if os.path.exists(zip_path):
        os.remove(zip_path)

    os.makedirs(os.path.join(build_dir, "database"), exist_ok=True)
    os.makedirs(os.path.join(build_dir, "source_code"), exist_ok=True)

    # 1. Database folder
    print("==> Copying database scripts and ERD...")
    shutil.copyfile(os.path.join(root, "database", "schema.sql"), os.path.join(build_dir, "database", "schema.sql"))
    shutil.copyfile(os.path.join(root, "database", "sample_data.sql"), os.path.join(build_dir, "database", "sample_data.sql"))
    
    erd_src = os.path.join(root, "database", "Group-13(240221 & 240222).png")
    if os.path.exists(erd_src):
        shutil.copyfile(erd_src, os.path.join(build_dir, "database", "ERD.png"))

    # 2. Source code
    print("==> Copying source code (backend, frontend, configs)...")
    shutil.copytree(os.path.join(root, "backend"), os.path.join(build_dir, "source_code", "backend"))
    shutil.copytree(os.path.join(root, "frontend"), os.path.join(build_dir, "source_code", "frontend"))
    
    os.makedirs(os.path.join(build_dir, "source_code", "database"), exist_ok=True)
    shutil.copyfile(os.path.join(root, "database", "schema.sql"), os.path.join(build_dir, "source_code", "database", "schema.sql"))
    shutil.copyfile(os.path.join(root, "database", "sample_data.sql"), os.path.join(build_dir, "source_code", "database", "sample_data.sql"))

    for fname in ["index.php", "landing.css", ".env.example", "README.md"]:
        fpath = os.path.join(root, fname)
        if os.path.exists(fpath):
            shutil.copyfile(fpath, os.path.join(build_dir, "source_code", fname))

    # 3. Reports & Presentation Slides
    print("==> Copying presentation slides (.pptx), reports (.docx), and PDFs...")
    docs_dir = os.path.join(root, "docs")
    if os.path.exists(docs_dir):
        for f in os.listdir(docs_dir):
            if f.endswith(".pdf") or f.endswith(".pptx") or f.endswith(".docx"):
                shutil.copyfile(os.path.join(docs_dir, f), os.path.join(build_dir, f))
                print(f"    Included: {f}")

    # 4. Create ZIP archive
    print(f"==> Compressing into {zip_path}...")
    with zipfile.ZipFile(zip_path, "w", zipfile.ZIP_DEFLATED) as zout:
        for foldername, subfolders, filenames in os.walk(os.path.join(root, "build_package")):
            for filename in filenames:
                filepath = os.path.join(foldername, filename)
                arcname = os.path.relpath(filepath, os.path.join(root, "build_package"))
                zout.write(filepath, arcname)

    # Cleanup temp build
    shutil.rmtree(os.path.join(root, "build_package"), ignore_errors=True)

    file_size_mb = os.path.getsize(zip_path) / (1024 * 1024)
    print("=" * 78)
    print(f" [SUCCESS] Submission package created: {zip_path} ({file_size_mb:.2f} MB)")
    print("=" * 78)

if __name__ == "__main__":
    package_project()

