#!/bin/bash
mkdir -p "$1"
rm -rf "$1/$2"
cd "$1"

git clone git@bitbucket.org:onestic-front/pwa-base.git "$2"
cd "$2"
if [ "$3" = "valid" ]; then
    git checkout beta
fi
if [ "$3" = "b2b" ]; then
    git checkout b2b
fi
rm -rf .git
rm -rf public/images



# se tiver terceiro parametro, clona o repositório da PWA
# apaga as imagens do boilerplate e copia as do repositório
# clonado, juntamente com o .git dele
if [ "$#" -eq 4 ]; then
    git clone $4 git_temp
    cd git_temp
    mv .git ./..
    if [ -d "public/images" ]; then
        mv public/images ../public
    fi
    cd ..
    rm -rf git_temp
fi
