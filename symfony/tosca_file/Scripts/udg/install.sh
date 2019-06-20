#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

# install ansible dependencies
apt-get update
apt-get install -y python
apt-get install -y ansible

echo $USER

#su - ubuntu && cd $HOME

echo "Git CLONE ansible playbooks"
git clone https://fsismondi:RsAG6CiMWwxY1SGnng_Y@gitlab.distantaccess.com/mandint/iot-vno-automated-deployment.git -vvv
cd iot-vno-automated-deployment/ansible

echo "Setting up secrets"
mv secrets.yaml.example secrets.yaml

echo "Running tag <common> of UDG deployment"
ansible-playbook  main.yaml -i localhost --tags common -v

echo "Running tag <common> of UDG deployment"
ansible-playbook  main.yaml -i localhost --tags install -v

# # install UDG dependencies and components
# echo "Running tag <common> of UDG deployment"
# ansible-playbook  main.yaml -i localhost --tags common -v
#
# echo "Running tag <lamp> of UDG deployment"
# ansible-playbook  main.yaml -i localhost --tags lamp -v
#
# echo "Running tag <gui> of UDG deployment"
# ansible-playbook  main.yaml -i localhost --tags gui -v
#
# echo "Running tag <configure> of UDG deployment"
# ansible-playbook  main.yaml -i localhost --tags configure -v

# echo "Running tag <db> of UDG deployment"
# ansible-playbook  main.yaml -i localhost --tags db -v
#
# echo "Running tag <udg> of UDG deployment"
# ansible-playbook  main.yaml -i localhost --tags udg -v
