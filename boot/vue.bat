@echo off
cd ..
cd ./vue
call npm i --silent
call npm run build --silent
call npm run preview
call o
pause