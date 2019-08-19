#!/bin/bash

echo "running VNF START step"

echo "Adding IP route to 6LoWPAN network"
sudo ip route add 2001:41e0:6002:1800::/64 via fc00::314 dev ens3

echo "Restarting UDGaaF"
sudo supervisorctl stop udg
cd /home/mandint/UDG && sudo rm -rf felix-cache
sudo supervisorctl start udg