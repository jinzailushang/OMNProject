#!/bin/bash
# 用法：
# 1. 初次使用请拷贝本文件到上一级目录，非初次使用请直接执行第二步
#    cp deploy.sh ../
# 2. ../deploy.sh

git config --global core.autocrlf false

CLGS="\033[0;32m" #green
CLOS="\033[0;33m" #orange
CLE="\033[0m"

printf "${CLOS}Commiting...${CLE}\n"
git commit . -m "commit untrack files"
printf "${CLGS}Committed successful${CLE}\n\n"

printf "${CLOS}Switch to develop branch${CLE}\n"
git checkout develop
printf "${CLGS}Switched successful${CLE}\n\n"

printf "${CLOS}Pulling origin changes${CLE}\n"
git pull origin develop
printf "${CLGS}Pulled successful${CLE}\n\n"

printf "${CLOS}Pushing changes to origin${CLE}\n"
git push origin develop
printf "${CLGS}Pushed successful${CLE}\n\n"

printf "${CLOS}Switch to master branch${CLE}\n"
git checkout master
printf "${CLGS}Switched successful${CLE}\n\n"

printf "${CLOS}Reverting changes${CLE}\n"
git reset --hard
git rebase --continue
#git clean -f -d
printf "${CLGS}Reverted successful${CLE}\n\n"

printf "${CLOS}Pulling origin changes${CLE}\n"
git pull origin master
printf "${CLGS}Pulled successful${CLE}\n\n"

printf "${CLOS}Merging develop branch${CLE}\n"
git merge develop
printf "${CLGS}Merged successful${CLE}\n\n"

printf "${CLOS}Pushing changes & tags to origin${CLE}\n"
git push origin master
git push origin --tags
printf "${CLGS}Pushed successful${CLE}\n\n"

printf "${CLOS}Switching back to develop branch${CLE}\n"
git checkout develop
printf "${CLGS}Switched successful${CLE}\n"
