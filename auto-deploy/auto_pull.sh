#!/bin/bash
eval `ssh-agent -s`
ssh-add /root/.ssh/quynd_rsa
cd /home/memart/memart.vn/public_html && git pull origin master
sudo chown -R memart:memart /home/memart/memart.vn/public_html
sudo chmod o+rx auto-deploy/auto_pull.sh
