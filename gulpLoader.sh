#!/bin/bash

echo '******************************************************'
echo '*                                                    *'
echo '*               Welcome to GulpLoader                *'
echo '*                                                    *'
echo '******************************************************'

npm init
npm install gulp --save-dev
npm install gulp-sass --save-dev
npm install browser-sync --save-dev

npm install --save gulp-uglifycss

echo '******************************************************'
echo '*                                                    *'
echo '*     Gulp et ses dépendances sont  initialisés      *'
echo '*                                                    *'
echo '******************************************************'